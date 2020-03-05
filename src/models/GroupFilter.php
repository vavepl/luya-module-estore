<?php

namespace luya\estore\models;

use yii\base\Model;

class GroupFilter extends Model
{
	public $id;
	public $name;
	public $parent_group_id;

	public function rules()
	{
		return [
			[['id', 'parent_group_id', 'name'], 'safe'],
		];
	}

}
