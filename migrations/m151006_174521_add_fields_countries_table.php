<?php

use yii\db\Schema;
use yii\db\Migration;

class m151006_174521_add_fields_countries_table extends Migration
{
    public function up()
    {
        $this->addColumn('countries', 'flag', $this->string(255));
        $this->addColumn('countries', 'capital_image', $this->string(255));
    }

    public function down()
    {
        echo "m151006_174521_add_fields_countries_table cannot be reverted.\n";

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
