<?php

use yii\db\Schema;
use yii\db\Migration;

class m150905_161106_create_private_chats_table extends Migration
{
    public function up() {
        $this->createTable('private_chats', [
            'id'         => $this->primaryKey(),
            'owner_id'   => $this->integer()->notNull(),
            'guest_id'   => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer(),
        ]);
    }

    public function down() {
        $this->dropTable('private_chats');

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
