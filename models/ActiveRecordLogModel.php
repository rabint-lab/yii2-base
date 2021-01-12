<?php

namespace rabint\models;

use Yii;
use beensa\helpers\locality;
use yii\db\Schema;

class ActiveRecordLogModel extends \yii\db\ActiveRecord
{
    
    
    /**
     * {@inheritdoc}
     */
    
    public static function tableName()
    {
        $tableList = Yii::$app->db->schema->getTableNames();
        $tableName = 'ActiveRecordLog_'.locality::jdate('Ym');
        if(!in_array($tableName, $tableList)){

            $fields['id'] = Schema::TYPE_PK;
            $fields['action'] = Schema::TYPE_STRING;
            $fields['old_value'] = Schema::TYPE_TEXT;
            $fields['new_value'] = Schema::TYPE_TEXT;
            $fields['model'] = Schema::TYPE_STRING;
            $fields['model_id'] = Schema::TYPE_INTEGER;
            $fields['field'] = Schema::TYPE_STRING;
            $fields['created_at'] = Schema::TYPE_INTEGER;
            $fields['user_id'] = Schema::TYPE_STRING;
            $fields['user_alias'] = Schema::TYPE_STRING;
            Yii::$app->db->createCommand()->createTable($tableName, $fields)->execute();
            sleep(2);
        }
        return $tableName;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_id','created_at'], 'integer'],
            [['action','model','field','user_id'], 'string'],
            [['old_value','new_value'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'شناسه',
            'model' => 'مدل مربوطه',
            'model_id' => 'شناسه مدل',
            'field' => 'فیلد',
            'created_at' => 'تاریخ',
            'user_id' => 'شناسه کاربر',
            'new_value' => 'مقدار جدید',
            'old_value' => 'مقدار قدیم',
        ];
    }
}
