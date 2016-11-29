<?php

use yii\db\Schema;
use yii\db\Migration;

class m150928_165831_create_pages_table extends Migration
{
    public function up()
    {
        $this->createTable('pages', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'text' => $this->text(),
            'language_id' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('pages');
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
