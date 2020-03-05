<?php

use yii\db\Migration;

class m170515_116142_product_images extends Migration
{
    public function safeUp()
    {
	    $this->addColumn('{{%estore_product}}', 'cover_image_id', $this->integer());
	    $this->addColumn('{{%estore_product}}', 'images_list', $this->text());

    }

    public function safeDown()
    {
	    $this->dropColumn('{{%estore_product}}', 'images_list');
	    $this->dropColumn('{{%estore_product}}', 'cover_image_id');
    }
}
