<?php

use yii\db\Schema;
use yii\db\Migration;

class m150928_170612_create_languages_table extends Migration
{
    public function up()
    {
        $this->createTable('languages', [
            'id' => $this->primaryKey(),
            'code' => $this->string(5)->notNull(),
            'name' => $this->string(100),
        ]);

        $this->addForeignKey('pages_languages_id', 'pages', 'language_id', 'languages', 'id');

        $this->batchInsert('languages',
            [
                'code',
                'name',
            ],
            [
                ['en', 'English'],
                ['ru', 'Русский'],
                ['de', 'German'],
            ]
        );
    }

    public function down()
    {
        echo "m150928_170612_create_languages_table cannot be reverted.\n";

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
