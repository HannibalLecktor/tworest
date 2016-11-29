<?php

use yii\db\Schema;
use yii\db\Migration;

class m150905_160445_create_districts_table extends Migration
{
    public function up() {
        $this->createTable('districts', [
            'id'         => $this->primaryKey(),
            'name'       => $this->string(64)->notNull(),
            'city_id'    => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer(),
        ]);
        $this->addForeignKey('districts_city_id', 'districts', 'city_id', 'cities', 'id');
    }

    public function down() {
        $this->dropTable('districts');

        return true;
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
