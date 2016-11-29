<?php

use yii\db\Schema;
use yii\db\Migration;

class m150905_161052_create_messages_table extends Migration
{
    public function up() {
        $this->createTable('messages', [
            'id'              => $this->primaryKey(),
            'text'            => $this->text()->notNull(),
            'district_id'     => $this->integer(),
            'private_chat_id' => $this->integer(),
            'user_id'         => $this->integer()->notNull(),
            'created_at'      => $this->integer()->notNull(),
            'updated_at'      => $this->integer(),
        ]);
    }

    public function down() {
        $this->dropTable('messages');

        return true;
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
