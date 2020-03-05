<?php

namespace luya\estore\admin\apis;

use app\helpers\CorsCustom;
use luya\estore\models\Product;
use luya\helpers\ArrayHelper;
use vavepl\inpost\Api\Client;
use vavepl\inpost\Enum\Services;
use vavepl\inpost\Enum\Size;
use vavepl\inpost\Models\Address;
use vavepl\inpost\Models\CustomAttributes;
use vavepl\inpost\Models\Parcel;
use vavepl\inpost\Models\ParcelDimensions;
use vavepl\inpost\Models\ParcelWeight;
use vavepl\inpost\Models\Receiver;
use vavepl\inpost\Objects\Shipment\Shipment;
use Yii;
use yii\db\ActiveQuery;
use yii\filters\VerbFilter;

/**
 * Product Controller.
 *
 * File has been created with `crud/create` command on LUYA version 1.0.0-dev.
 */
class ProductController extends \luya\admin\ngrest\base\Api
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = 'luya\estore\models\Product';

	public $authOptional = ['index', 'view', 'test'];

	public $filterSearchModelClass = 'luya\estore\models\ProductFilter';

	public function prepareIndexQuery()
	{
		return parent::prepareIndexQuery()->joinWith(['groups','setAttributes','articles' => function (\yii\db\ActiveQuery $query) {
			$query->joinWith(['prices' => function (\yii\db\ActiveQuery $query) {
				if(isset(Yii::$app->request->getQueryParams()['sortPrice'])) {
					$query->orderBy('estore_article_price.price ' . Yii::$app->request->getQueryParams()['sortPrice']);
				}
			}]);
		}])->groupBy('id');
	}

	public function getDataFilter()
	{
		return [
			'class' => 'yii\data\ActiveDataFilter',
			'searchModel' => $this->filterSearchModelClass,
			'attributeMap' => [
				'groups' => 'estore_group.id',
				'setAttributes' => 'estore_set_attribute.id',
				'price' => 'estore_article_price.price'
			]
		];
	}

	protected function verbs()
	{
		$verbs = ArrayHelper::merge(parent::verbs(),[
			'index' => ['GET', 'OPTIONS'],
			'view' => ['GET', 'OPTIONS'],
		]);
		return $verbs;
	}

	/**
	 * @return array
	 */
	public function behaviors()
	{
		$behaviors = parent::behaviors();

		// remove authentication filter
		$auth = $behaviors['authenticator'];
		unset($behaviors['authenticator']);

		// add CORS filter
		$behaviors['corsFilter'] = [
			'class' => CorsCustom::class,
			/*'cors' => [
				"Origin" => "*",
				"Access-Control-Request-Method" => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
				"Access-Control-Request-Headers" => "*",
				"Access-Control-Max-Age" => 86400,
				"Access-Control-Expose-Headers" => ["*"]
			]*/
			'cors' => [
				'Origin' => CorsCustom::allowedDomains(),
				'Access-Control-Allow-Credentials' => true,
				'Access-Control-Request-Method'    => ['POST', 'GET', 'PUT', 'OPTIONS'],
				'Access-Control-Allow-Headers' => ['X-Requested-With','content-type', 'api-cart', 'Authorization'],
				"Access-Control-Expose-Headers" => ["X-Pagination-Current-Page", "X-Pagination-Page-Count", "X-Pagination-Per-Page", "X-Pagination-Total-Count", "X-Cruft-Length"]
			],
		];

		// re-add authentication filter
		$behaviors['authenticator'] = $auth;
		// avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
		$behaviors['authenticator']['except'] = ['options'];

		return $behaviors;
	}

	public function actionAttributes($id)
    {
        $model = $this->findModel($id);

        $data = [];

        foreach ($model->getSets()->with(['setAttributes'])->all() as $set) {
            $data[] = [
                'set' => $set,
                'attributes' => $set->setAttributes,
            ];
        }

        return $data;
    }

    public function actionTest() // todo inpost form
    {
    	$inPost = new Client(
    		'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJzYW5kYm94LWFwaS1zaGlweC1wbC5lYXN5cGFjazI0Lm5ldCIsInN1YiI6InNhbmRib3gtYXBpLXNoaXB4LXBsLmVhc3lwYWNrMjQubmV0IiwiZXhwIjoxNTgyMjkyMjkyLCJpYXQiOjE1ODIyOTIyOTIsImp0aSI6IjFjMmFiYjhlLTdkM2UtNDBhNS1iMjU3LTVhZDIwYmNjMzNiNyJ9.15XBEw5mQJgiMToYiZsoy02DJl-QCCVbSgdJYw80hwD0K5wi91DNoybCCNZeBrM4rgLVBU3_13TGEZ3fJRJDxg',
            929,
		    Client::SANDBOX_API_ENDPOINT);

    	$receiver = new Receiver();
    	$receiver->phone = "509799224";
    	$receiver->email = 'biuro@vave.pl';
	    $receiver->name = "Nazwa";
    	$parcel = new Parcel();

    	$parcel->weight = new ParcelWeight();
	    $parcel->weight->amount = "25";
		$parcel->weight->unit = 'kg';
		$parcel->template = Size::SMALL;
		$parcel->dimensions = new ParcelDimensions();
	    $parcel->dimensions->height = 2400;
		$parcel->dimensions->width = 2400;
		$parcel->dimensions->length = 3500;
		$parcel->dimensions->unit = 'mm';

    	$shipment = new Shipment($receiver, $parcel,Services::LOCKER_STANDARD);
    	$shipment->custom_attributes = new CustomAttributes();
    	$shipment->custom_attributes->target_point = "WWK01A";
    	$shipment->custom_attributes->dropoff_point = "CAW01A";
    	$shipment->custom_attributes->sending_method = "parcel_locker";

    	return $inPost->createShipment($shipment);
    }

}
