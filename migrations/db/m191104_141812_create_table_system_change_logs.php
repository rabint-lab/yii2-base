<?php

use yii\db\Migration;

class m191104_141812_create_table_system_change_logs extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%system_change_log}}', [
            'id' => $this->integer(10)->unsigned()->notNull()->append('AUTO_INCREMENT PRIMARY KEY')->comment('شناسه'),
            'user_id' => $this->integer(10)->unsigned()->comment('کاربر تغییر دهنده'),
            'date' => $this->integer(10)->unsigned()->comment('تاریخ ویرایش'),
            'action' => $this->string(45)->comment('نوع تغییر'),
            'model' => $this->string(190)->comment('جدول'),
            'record_id' => $this->integer(10)->unsigned()->comment('رکورد مورد تغییر'),
            'changes' => $this->text()->comment('تغییرات'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%system_change_log}}');
    }
}
