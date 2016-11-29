<?php

use yii\db\Schema;
use yii\db\Migration;

class m151010_143835_add_avatar_to_users extends Migration
{
    public function up()
    {
        $this->addColumn('users', 'avatar', $this->string(255));
    }

    public function down()
    {
        echo "m151010_143835_add_avatar_to_users cannot be reverted.\n";

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
