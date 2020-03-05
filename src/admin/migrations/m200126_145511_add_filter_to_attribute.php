<?php

use yii\db\Migration;

/**
 * Class m200126_145511_add_filter_to_attribute
 */
class m200126_145511_add_filter_to_attribute extends Migration
{
	public function safeUp()
	{
		$this->addColumn('{{%estore_set_attribute}}', 'is_filter', $this->boolean());
	}

	public function safeDown()
	{
		$this->dropColumn('{{%estore_set_attribute}}', 'is_filter');
	}
}
