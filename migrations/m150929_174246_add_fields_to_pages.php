<?php

use yii\db\Schema;
use yii\db\Migration;

class m150929_174246_add_fields_to_pages extends Migration
{
    public function up()
    {
        $this->addColumn('pages', 'created_at', $this->integer()->notNull());
        $this->addColumn('pages', 'updated_at', $this->integer());
    }

    public function down()
    {
        echo "m150929_174246_add_fields_to_pages cannot be reverted.\n";

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
