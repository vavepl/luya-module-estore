<?php

namespace luya\estore\admin\apis;

use app\helpers\CorsCustom;
use luya\helpers\ArrayHelper;

/**
 * Group Controller.
 *
 * File has been created with `crud/create` command on LUYA version 1.0.0-dev.
 */
class GroupController extends \luya\admin\ngrest\base\Api
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = 'luya\estore\models\Group';

	public $authOptional = ['index', 'view', 'categories'];

	public $filterSearchModelClass = 'luya\estore\models\GroupFilter';

	protected function verbs()
	{
		$verbs = ArrayHelper::merge(parent::verbs(),[
			'index' => ['GET', 'OPTIONS'],
			'view' => ['GET', 'OPTIONS'],
			'categories' => ['GET', 'OPTIONS']
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
	        'cors' => [
		        'Origin' => CorsCustom::allowedDomains(),
		        'Access-Control-Allow-Credentials' => true,
		        'Access-Control-Request-Method'    => ['POST', 'GET', 'PUT', 'OPTIONS'],
		        'Access-Control-Allow-Headers' => ['X-Requested-With','content-type', 'api-cart', 'Authorization'],
	        ],
        ];

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCategories()
    {
    	return $this->getModel()::find()->where(['!=', 'parent_group_id', 'null'])->all();
        return $this->getModel()::buildTree2($this->getModel()::find()->all());
    }


}
