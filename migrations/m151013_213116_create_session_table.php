<?php

use yii\db\Schema;
use yii\db\Migration;

class m151013_213116_create_session_table extends Migration
{
    public function up()
    {
        $this->db->createCommand('
            CREATE TABLE IF NOT EXISTS session
            (
              id CHAR(40) PRIMARY KEY NOT NULL,
              expire INT,
              data BYTEA,
              user_id INT DEFAULT 0
            );
        ')->execute();
    }

    public function down()
    {
        $this->dropTable('session');
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
