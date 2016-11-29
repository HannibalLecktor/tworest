<?php

use yii\db\Schema;
use yii\db\Migration;

class m150929_182436_add_fields_to_users extends Migration
{
    public function up()
    {
        $this->addColumn('users', 'is_admin', $this->boolean());
    }

    public function down()
    {
        echo "m150929_182436_add_fields_to_users cannot be reverted.\n";

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
