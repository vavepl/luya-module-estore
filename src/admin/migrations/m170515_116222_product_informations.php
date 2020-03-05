<?php

use yii\db\Migration;

class m170515_116222_product_informations extends Migration
{
    public function safeUp()
    {
	    $this->addColumn('{{%estore_product}}', 'teaser', $this->text());
	    $this->addColumn('{{%estore_product}}', 'text', $this->text());
    }

    public function safeDown()
    {
	    $this->dropColumn('{{%estore_product}}', 'text');
	    $this->dropColumn('{{%estore_product}}', 'teaser');
    }
}
