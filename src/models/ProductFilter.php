<?php

namespace luya\estore\models;

use yii\base\Model;

class ProductFilter extends Model
{
	public $id;
	public $name;
	public $groups;
	public $price;
	public $setAttributes;

	public function rules()
	{
		return [
			[['id'], 'integer'],
			[['name'], 'string'],
			[['groups', 'price', 'setAttributes'], 'safe']
		];
	}

}
