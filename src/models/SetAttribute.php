<?php

namespace luya\estore\models;

use luya\estore\admin\plugins\JsonObjectArrayPlugin;
use luya\estore\admin\plugins\JsonObjectPlugin;
use Yii;
use luya\admin\ngrest\base\NgRestModel;
use luya\admin\ngrest\plugins\SelectArray;
use luya\admin\base\TypesInterface;
use yii\helpers\Json;

/**
 * Set Attribute.
 *
 * File has been created with `crud/create` command on LUYA version 1.0.0-dev.
 *
 * @property integer $id
 * @property integer $type
 * @property string $input
 * @property string $name
 * @property string $values
 * @property integer $is_i18n
 * @property boolean $is_filter
 */
class SetAttribute extends NgRestModel
{
    /**
     * @inheritdoc
     */
    public $i18n = ['name', 'values'];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'estore_set_attribute';
    }

    /**
     * @inheritdoc
     */
    public static function ngRestApiEndpoint()
    {
        return 'api-estore-setattribute';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('estoreadmin', 'ID'),
            'type' => Yii::t('estoreadmin', 'Type'),
            'name' => Yii::t('estoreadmin', 'Name'),
            'values' => Yii::t('estoreadmin', 'Values'),
            'input' => Yii::t('estoreadmin', 'Input'),
	        'is_i18n' => Yii::t('estoreadmin', 'is_i18n'),
	        'is_filter' => Yii::t('estoreadmin', 'Is Filter'),
        ];
    }

    public function attributeHints()
    {
        return [
            'type' => Yii::t('estoreadmin', 'Type'),
            'name' => Yii::t('estoreadmin', 'Name'),
            'values' => Yii::t('estoreadmin', 'Values'),
            'input' => Yii::t('estoreadmin', 'Input'),
            'is_i18n' => Yii::t('estoreadmin', 'is_i18n'),
	        'is_filter' => Yii::t('estoreadmin', 'Is Filter'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'is_i18n', 'is_filter'], 'integer'],
            [['input', 'name'], 'required'],
            [['values'], 'string'],
            [['input', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function genericSearchFields()
    {
        return ['name', 'values'];
    }

    /**
     * @inheritdoc
     */
    public function ngRestAttributeTypes()
    {
        return [
            'type' => [
                'class' => SelectArray::class,
                'data' => [
                    1 => Yii::t('estoreadmin', 'Integer'),
                    2 => Yii::t('estoreadmin', 'Boolean'),
                    3  => Yii::t('estoreadmin', 'String'),
                    4  => Yii::t('estoreadmin', 'Color')
                ],
            ],
            'name' => 'text',
            'values' => ['class' => JsonObjectArrayPlugin::class],
	        'is_i18n' => 'toggleStatus',
	        'is_filter' => 'toggleStatus',
            'input' => ['selectArray', 'data' => [
                TypesInterface::TYPE_TEXT => Yii::t('estoreadmin', 'text'),
                TypesInterface::TYPE_TEXTAREA => Yii::t('estoreadmin', 'textarea'),
                TypesInterface::TYPE_CHECKBOX => Yii::t('estoreadmin', 'checkbox'),
                TypesInterface::TYPE_SELECT => Yii::t('estoreadmin', 'select'),
            ]]
        ];
    }

    /**
     * @inheritdoc
     */
    public function ngRestScopes()
    {
        return [
            ['list', ['type', 'name', 'values']],
            [['create', 'update'], ['type', 'name', 'values', 'is_i18n', 'is_filter', 'input']],
            ['delete', false],
        ];
    }

}
