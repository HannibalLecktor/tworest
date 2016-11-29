<?php

use yii\db\Schema;
use yii\db\Migration;

class m150905_160435_create_cities_table extends Migration
{
    public function up() {
        $this->createTable('cities', [
            'id'         => $this->primaryKey(),
            'name'       => $this->string(64)->notNull(),
            'country_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer(),
        ]);

        $this->addForeignKey('cities_country_id', 'cities', 'country_id', 'countries', 'id');

        $this->batchInsert('cities',
            [
                'country_id',
                'name',
                'created_at',
                'updated_at'
            ],
            [
                [1, 'Москва', 0, 0],
                [1, 'Пермь', 0, 0],
                [1, 'Челябинск', 0, 0],

                [2, 'Велтэн', 0, 0],
                [2, 'Берлин', 0, 0],
                [2, 'Дрезден', 0, 0],

                [3, 'Париж', 0, 0],
                [3, 'Канны', 0, 0],
                [3, 'Марсель', 0, 0]
            ]
        );
    }

    public function down() {
        $this->dropTable('cities');

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
