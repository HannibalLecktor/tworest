<?php

use yii\db\Schema;
use yii\db\Migration;

class m150129_041626_create_users_table extends Migration
{
    public function up() {
        $this->createTable('users', [
            'id'                   => $this->primaryKey(),
            'username'             => $this->string(64)->notNull(),
            'first_name'           => $this->string(64),
            'last_name'            => $this->string(64),
            'phone'                => $this->string(64),
            'auth_key'             => $this->string(32)->notNull(),
            'password_hash'        => $this->string(64)->notNull(),
            'password_reset_token' => $this->string(64),
            'email'                => $this->string(64)->notNull(),
            'status'               => $this->smallInteger()->notNull()->defaultValue(10),
            'district_id'         => $this->integer(),
            'created_at'           => $this->integer()->notNull(),
            'updated_at'           => $this->integer(),
        ]);
    }

    public function down() {
        $this->dropTable('users');
    }
}
