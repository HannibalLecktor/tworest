<?php

use yii\db\Schema;
use yii\db\Migration;

class m150930_185044_add_foreignkey_users extends Migration
{
    public function up()
    {
        $this->addForeignKey('users_districts_id', 'users', 'district_id', 'districts', 'id');
    }

    public function down()
    {
        echo "m150930_185044_add_foreignkey_users cannot be reverted.\n";

        return false;
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
