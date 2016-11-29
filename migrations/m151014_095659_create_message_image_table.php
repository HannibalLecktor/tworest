<?php

use yii\db\Schema;
use yii\db\Migration;

class m151014_095659_create_message_image_table extends Migration
{
    public function up() {
        $this->createTable('message_images', [
            'id'         => $this->primaryKey(),
            'image'      => $this->string(255)->notNull(),
            'message_id' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer()
        ]);
    }

    public function down() {

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
