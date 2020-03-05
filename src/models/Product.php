<?php

namespace luya\estore\models;

use app\buttons\DuplicateActiveButton;
use Yii;
use luya\admin\ngrest\base\NgRestModel;
use luya\admin\ngrest\plugins\CheckboxRelationActiveQuery;

/**
 * Product.
 *
 * File has been created with `crud/create` command on LUYA version 1.0.0-dev.
 *
 * @property \luya\estore\models\Set $sets
 * @property integer $id
 * @property text $name
 * @property integer $producer_id
 * @property integer $cover_image_id
 * @property text $images_list
 * @property text $teaser
 * @property text $text
 * @property Article lastArticle
 */
class Product extends NgRestModel
{
    /**
     * @inheritdoc
     */
    public $i18n = ['name', 'teaser', 'text'];

    /**
     * @var array
     */
    public $adminGroups = [];
    
    /**
     * @var array
     */
    public $adminSets = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'estore_product';
    }
    
    /**
     * @inheritdoc
     */
    public static function ngRestApiEndpoint()
    {
        return 'api-estore-product';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('estoreadmin', 'ID'),
            'name' => Yii::t('estoreadmin', 'Name'),
            'producer_id' => Yii::t('estoreadmin', 'Producer ID'),
            'adminGroups' => Yii::t('estoreadmin', 'Categories'),
            'adminSets' => Yii::t('estoreadmin', 'Attribute Sets'),
	        'cover_image_id' => Yii::t('estoreadmin', 'Cover Image ID'),
	        'images_list' => Yii::t('estoreadmin', 'Images List'),
	        'teaser' => Yii::t('estoreadmin', 'Teaser'),
	        'text' => Yii::t('estoreadmin', 'Text'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'producer_id', 'cover_image_id', 'images_list', 'teaser', 'text'], 'required'],
            [['name', 'images_list', 'teaser', 'text'], 'string'],
	        [['producer_id', 'cover_image_id'], 'integer'],
            [['adminGroups', 'adminSets'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function genericSearchFields()
    {
        return ['name', 'images_list', 'teaser', 'text'];
    }

    /**
     * @inheritdoc
     */
    public function ngRestAttributeTypes()
    {
        return [
            'name' => 'text',
            'producer_id' => ['selectModel', 'modelClass' => Producer::class],
	        'cover_image_id' => 'image',
	        'images_list' => 'imageArray',
	        'teaser' => 'text',
	        'text' => 'textarea',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function ngRestScopes()
    {
        return [
            ['list', ['cover_image_id', 'images_list', 'name', 'producer_id', 'teaser', 'text']],
            [['create', 'update'], ['cover_image_id', 'images_list', 'name', 'producer_id', 'teaser', 'text', 'adminGroups', 'adminSets']],
            ['delete', true],
        ];
    }
    
    public function ngRestExtraAttributeTypes()
    {
        return [
            'adminGroups' => [
                'class' => CheckboxRelationActiveQuery::class,
                'query' => $this->getGroups(),
                'labelField' => ['name'],
            ],
            'adminSets' => [
                'class' => CheckboxRelationActiveQuery::class,
                'query' => $this->getSets(),
                'labelField' => ['name'],
            ]
        ];
    }
    
    public function ngRestRelations()
    {
        return [
            ['label' => Yii::t('estoreadmin', 'Articles'), 'targetModel' => Article::class, 'apiEndpoint' => Article::ngRestApiEndpoint(), 'dataProvider' => $this->getArticles()],
            ['label' => Yii::t('estoreadmin', 'Groups'), 'targetModel' => Group::class, 'apiEndpoint' => Group::ngRestApiEndpoint(), 'dataProvider' => $this->getGroups()],
        ];
    }

    public function extraFields()
    {
        return ['adminGroups', 'adminSets', 'articles', 'groups', 'producer', 'coverImage', 'imagesList', 'sets', 'setAttributes'];
    }

    public function ngRestActiveButtons()
    {
        return [
            ['class' => DuplicateActiveButton::class],
        ];
    }

    public function getLastArticle()
    {
        return $this->getArticles()->orderBy(['id'=>SORT_DESC])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::class, ['product_id' => 'id']);
    }
    
    public function getGroups()
    {
        return $this->hasMany(Group::class, ['id' => 'group_id'])->viaTable(ProductGroupRef::tableName(), ['product_id' => 'id']);
    }

	public function getSets()
	{
		return $this->hasMany(Set::class, ['id' => 'set_id'])->viaTable(ProductSetRef::tableName(), ['product_id' => 'id']);
	}

	public function getSetAttributeRef()
	{
		return $this->hasMany(SetAttributeRef::class, ['set_id' => 'set_id'])->viaTable(ProductSetRef::tableName(), ['product_id' => 'id']);

	}

	public function getSetAttributes(){
		return $this->hasMany(SetAttribute::class, ['id' => 'attribute_id'])->via('setAttributeRef');
	}

	public function getProducer()
	{
		return $this->hasOne(Producer::class, ['id' => 'producer_id']);
	}

	public function getImagesList()
	{
		$arr = [];

		foreach($this->images_list as $image){
			$item = Yii::$app->storage->getImage($image["imageId"]);
			if($item)
				$arr[] = $item->getSource(true);
		}

		return $arr;
	}

	public function getCoverImage()
	{
		$img = Yii::$app->storage->getImage($this->cover_image_id);

		if($img){
			return $img->getSource(true);
		}

		return '/assets/img/product-image.png';
	}

}
