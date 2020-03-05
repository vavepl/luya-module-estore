<?php

namespace luya\estore\models;

use Yii;
use luya\admin\ngrest\base\NgRestModel;

/**
 * Group.
 *
 * File has been created with `crud/create` command on LUYA version 1.0.0-dev.
 *
 * @property integer $id
 * @property integer $parent_group_id
 * @property integer $cover_image_id
 * @property text $images_list
 * @property text $name
 * @property text $teaser
 * @property text $text
 */
class Group extends NgRestModel
{

    public $childs;
    public $children;

    /**
     * @inheritdoc
     */
    public $i18n = ['images_list', 'name', 'teaser', 'text'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'estore_group';
    }

    /**
     * @inheritdoc
     */
    public static function ngRestApiEndpoint()
    {
        return 'api-estore-group';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('estoreadmin', 'ID'),
            'parent_group_id' => Yii::t('estoreadmin', 'Parent Group ID'),
            'cover_image_id' => Yii::t('estoreadmin', 'Cover Image ID'),
            'images_list' => Yii::t('estoreadmin', 'Images List'),
            'name' => Yii::t('estoreadmin', 'Name'),
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
            [['parent_group_id', 'cover_image_id'], 'integer'],
            [['images_list', 'name', 'teaser', 'text'], 'string'],
            [['name'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function genericSearchFields()
    {
        return ['images_list', 'name', 'teaser', 'text'];
    }

    /**
     * @inheritdoc
     */
    public function ngRestAttributeTypes()
    {
        return [
            'parent_group_id' => ['selectModel', 'modelClass' => Group::class, 'valueField' => 'id', 'labelField' => 'name'],
            'cover_image_id' => 'image',
            'images_list' => 'imageArray',
            'name' => 'text',
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
            ['list', ['parent_group_id', 'cover_image_id', 'images_list', 'name', 'teaser', 'text']],
            [['create', 'update'], ['parent_group_id', 'cover_image_id', 'images_list', 'name', 'teaser', 'text']],
            ['delete', false],
        ];
    }

    public function ngRestRelations()
    {
        return [
            ['label' => Yii::t('estoreadmin', 'Products'), 'targetModel' => Product::class, 'apiEndpoint' => Product::ngRestApiEndpoint(), 'dataProvider' => $this->getProducts()],
        ];
    }

    public function getProducts()
    {
        return $this->hasMany(Product::class, ['id' => 'product_id'])->viaTable(ProductGroupRef::tableName(), ['group_id' => 'id']);
    }

    public function getGroup()
    {
	    return $this->hasOne(Group::class, ['id' => 'parent_group_id']);

    }

	public function extraFields()
	{
		return ['products', 'coverImage', 'imagesList', 'parentGroup'];
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

	public function getParentGroup()
	{
		if($this->group){
			return $this->group->name;
		}

		return null;
	}

    public static function buildTree(array $elements, $parentId = 0, $level = '') {

        $branch = array();

        $level_ini = $level;

        foreach ($elements as $element) {

            $level = $level_ini;

            if ($element->parent_group_id == $parentId) {

                $element->name = $level.$element->name;

                $level .= '_';

                $children = self::buildTree($elements, $element->id, $level);

                $branch["n".$element->id] = $element->name;

                if ($children) {

                    $branch = yii\helpers\ArrayHelper::merge($branch,$children);

                }

            }

        }

        return $branch;

    }

    public static function buildTree2(array $elements, $parentId = null) {
        $branch = array();

        foreach ($elements as $element) {
            if ($element->parent_group_id == $parentId) {
                $children = self::buildTree2($elements, $element->id);

                $tmp = [
                	"id" => $element->id,
	                "name" => $element->name,
	                "parent_group_id" => $element->parent_group_id,
	                "children" => []
                ];

				if ($children) {
                    $tmp['children'] = $children;
                }

                $branch[] = $tmp;

                unset($elements[$element->id]);
            }
        }
        return $branch;
    }

}
