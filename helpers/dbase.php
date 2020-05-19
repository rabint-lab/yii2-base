<?php

namespace rabint\helpers;

use yii\db\Schema;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class dbase {
    
    
    /*
     * tableName varchar name of table
     * attribiutes is list of fields of table
     */
    public static function getDataType($dataType){
        
        $type = '';
        if ($dataType == 'file') {
            $type = Schema::TYPE_STRING;
        }else
        if ($dataType == 'text') {
            $type = Schema::TYPE_TEXT;
        }else
        if ($dataType == 'int') {
            $type = Schema::TYPE_INTEGER;
        }else
        if ($dataType == 'boolean') {
            $type = Schema::TYPE_INTEGER;
        }else
        if ($dataType == 'varchar') {
            $type = Schema::TYPE_STRING;
        }else
        if ($dataType == 'decimal') {
            $type = Schema::TYPE_DOUBLE;
        }else
        if ($dataType == 'select') {
            $type = Schema::TYPE_STRING;
        }else{
            $type = $dataType;
        }
        return $type;
    }
    
    public static function createModel($tableName,$fields){
        
        $tabels = Yii::$app->db->schema->getTableNames();
        if (in_array($tableName, $tabels)) {
            $table_schema = (array)Yii::$app->db->schema->getTableSchema($tableName);
            $columns = $table_schema['columns'];
            $current_fields = [];
            foreach ($columns as $column => $attributes) {
                if ($column == 'id') {
                    continue;
                }
                $current_fields[] = $column;
            }
            foreach ($entity['fields'] as $field) {
                $new_fields[] = $field['slug'];
            }
            $items_to_add = array_diff($new_fields, $current_fields);
        }else{
            
        }
        
        Yii::$app->db->createCommand()->createTable($tableName, $fields)->execute();
        
    }
    
    public static function checkDataByType($data,$type){
        
        switch($type){
            case Schema::TYPE_STRING :
            case Schema::TYPE_TEXT : 
            case 'select' : 
                return TRUE;
                break;
            case Schema::TYPE_INTEGER:
            case Schema::TYPE_DOUBLE :
            case Schema::TYPE_BIGINT:
            case 'sum':
                if(is_numeric($data) or is_int($data))return TRUE;
                break;
            case Schema::TYPE_BOOLEAN:
                if(is_bool($data) or in_array($data, [0,1]))return TRUE;
        }
        return false;
    }
    
    public static function readyValueInsert($data,$type){
        
        switch($type){
            case Schema::TYPE_DATE:
            case Schema::TYPE_DATETIME:
                $value = locality::anyToGregorian($data);
                break;
            default :
                $value = $data;
                break;
        }
        return $value;
    }
    
    public static function readyValueExport($data,$entity){
        
        switch($entity['type']){
            case Schema::TYPE_DATE:
            case Schema::TYPE_DATETIME:
                $value = locality::anyToJalali($data);
                break;
            default :
                $value = $data;
                break;
        }
        return $value;
    }
}

