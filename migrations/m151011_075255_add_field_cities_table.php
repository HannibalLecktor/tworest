<?php

use yii\db\Schema;
use yii\db\Migration;

class m151011_075255_add_field_cities_table extends Migration
{
    public function up()
    {
        $this->addColumn('cities', 'image', $this->string(255));
    }

    public function down()
    {
        echo "m151011_075255_add_field_cities_table cannot be reverted.\n";

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
