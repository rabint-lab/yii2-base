<?php

//namespace rabint\migrations\db;

use yii\db\Migration;

/**
 * Class m181113_071904_create_db_session
 */
class m181113_071904_create_db_session extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%session}}', [
            //string('code', 30)->primary();
            'id' => $this->char(40),
            'user_id' => $this->integer()->null(),
            'expire' => $this->integer(),
            'data' => $this->binary(),
        ], $tableOptions);
        $this->addPrimaryKey('id', '{{%session}}', ['id']);
        $this->addForeignKey('fk_user_session', '{{%session}}', 'user_id', '{{%user}}', 'id', 'cascade', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_user_session', '{{%session}}');
        $this->dropTable('{{%session}}');
    }

}
