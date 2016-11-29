<?php

use yii\db\Schema;
use yii\db\Migration;

class m151007_190817_add_fields_languages_table extends Migration
{
    public function up()
    {
        $this->addColumn('languages', 'flag', $this->string(255));
    }

    public function down()
    {
        echo "m151007_190817_add_fields_languages_table cannot be reverted.\n";

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
