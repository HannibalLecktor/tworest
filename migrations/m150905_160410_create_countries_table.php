<?php

use yii\db\Schema;
use yii\db\Migration;

class m150905_160410_create_countries_table extends Migration
{
    public function up() {
        $this->createTable('countries', [
            'id'         => $this->primaryKey(),
            'name'       => $this->string(64)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer(),
        ]);

        $this->batchInsert('countries',
            [
                'name',
                'created_at',
                'updated_at'
            ],
            [
                ['Россия', 0, 0],
                ['Германия', 0, 0],
                ['Франция', 0, 0]
            ]
        );
    }

    public function down() {
        $this->dropTable('countries');

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
