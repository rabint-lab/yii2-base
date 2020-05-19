<?php

use yii\db\Migration;

class m191104_141811_create_table_system_city extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%system_city}}', [
            'id' => $this->integer(11)->unsigned()->notNull()->append('AUTO_INCREMENT PRIMARY KEY')->comment('شناسه'),
            'title' => $this->string(190)->comment('نام شهر'),
            'title_en' => $this->string(190)->comment('نام انگلیسی'),
            'state' => $this->string(190)->comment('استان'),
            'slug' => $this->string(7)->comment('نام مخفف'),
            'code' => $this->char(7)->comment('پیش شماره تلفن'),
            'status' => $this->tinyInteger(1)->comment('قابل نمایش است'),
            'weight' => $this->integer(11)->comment('ترتیب نمایش'),
            'tags' => $this->text()->comment('کلمات کلیدی'),
            # 'created_at' => $this->integer(4)->unsigned()->comment('تاریخ ایجاد'),
            # 'updated_at' => $this->integer(4)->unsigned()->comment('تاریخ بروزرسانی'),
        ], $tableOptions);

        $this->createIndex('idx_city_title', '{{%system_city}}', 'title');
    }

    public function down()
    {
        $this->dropTable('{{%system_city}}');
    }
}
