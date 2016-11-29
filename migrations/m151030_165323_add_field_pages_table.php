<?php

use yii\db\Schema;
use yii\db\Migration;

class m151030_165323_add_field_pages_table extends Migration
{
    public function up()
    {
        $this->addColumn('pages', 'sort', $this->integer());
    }

    public function down()
    {
        echo "m151030_165323_add_field_pages_table cannot be reverted.\n";

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
