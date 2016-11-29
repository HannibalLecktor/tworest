<?php

use yii\db\Schema;
use yii\db\Migration;

class m151007_172510_add_fields_district_table extends Migration
{
    public function up()
    {
        $this->addColumn('districts', 'image', $this->string(255));
    }

    public function down()
    {
        echo "m151007_172510_add_fields_district_table cannot be reverted.\n";

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
