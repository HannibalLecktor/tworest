<?php

use yii\db\Schema;
use yii\db\Migration;

class m151010_143846_add_image_to_messages extends Migration
{
    public function up()
    {
        $this->addColumn('messages', 'image', $this->string(255));
    }

    public function down()
    {
        $this->dropColumn('messages', 'image');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
