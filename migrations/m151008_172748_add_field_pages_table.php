<?php

use yii\db\Schema;
use yii\db\Migration;

class m151008_172748_add_field_pages_table extends Migration
{
    public function up()
    {
        $this->addColumn('pages', 'code', $this->string(255));
    }

    public function down()
    {
        echo "m151008_172748_add_field_pages_table cannot be reverted.\n";

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
