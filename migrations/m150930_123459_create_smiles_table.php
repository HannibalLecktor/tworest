<?php

use yii\db\Migration;

class m150930_123459_create_smiles_table extends Migration
{
    public function up() {
        $this->createTable('smiles', [
            'id'    => $this->primaryKey(),
            'sym'   => $this->string(64),
            'image' => $this->string(255),
			'created_at' => $this->integer(),
			'updated_at' => $this->integer()
        ]);

        $this->batchInsert('smiles',
            [
                'sym',
                'image',
                'created_at',
                'updated_at'
            ],
            [
                [':smile:', 'smile.png', 0, 0],
                [':anguished:', 'anguished.png', 0, 0],
                [':heart:', 'heart.png', 0, 0],
                [':laughing:', 'laughing.png', 0, 0]
            ]
        );
    }

    public function down() {
        $this->dropTable('smiles');
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
