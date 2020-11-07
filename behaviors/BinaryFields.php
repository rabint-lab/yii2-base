<?php

namespace rabint\behaviors;

use yii;
use beensa\helpers\locality;
use yii\db\Schema;
use beensa\models\ActiveRecordLogModel as ActiveRecordLog;
use yii\db\ActiveRecord;
use beensa\helpers\user;

/**
 *  useage example :
 *   public function behaviors()
 *   {
 *       return [
 *           [
 *               'class' => \beensa\behaviors\BinaryFields::class,
 *              'fields' => ['foo','bar','...'],
 *              'return' => \beensa\behaviors\RETURN_ARRAY::RETURN_DATE
 *           ]
 *       ];
 *   }
 */
class BinaryFields extends \yii\base\Behavior
{
    const RETURN_ARRAY = 'array';
    const RETURN_JSON = 'json';
    
    
    public $return = self::RETURN_ARRAY;
    public $fields;
        
    public function events(){
            return [
                ActiveRecord::EVENT_BEFORE_INSERT => 'setData',
                ActiveRecord::EVENT_BEFORE_UPDATE => 'setData',
                   ActiveRecord::EVENT_AFTER_FIND => 'getData'
            ];
        }
    
    public function setData(){
        if(!is_array($this->fields))new yii\base\Exception ('binary field\'s array field not defined');
        foreach($this->fields as $field){
                if(is_array($this->owner->$field))
                    $this->owner->$field = array_sum($this->owner->$field);
        }
    }
    
    public function getData(){
        foreach($this->fields as $field){
            if(!empty($this->owner->$field)){
                $data = (string) base_convert($this->owner->$field, 10, 2);
                $data = array_reverse(str_split($data));
                $res = [];
                foreach ($data as $k => $f) {
                    $res[$k] = ($f) ? pow(2, $k) : 0;
                }
                switch($this->return){
                    case self::RETURN_JSON:
                        $this->owner->$field = yii\helpers\Json::encode($res);
                        break;
                        
                    default:
                    case self::RETURN_ARRAY:
                        $this->owner->$field = $res;
                        break;
                            
                }
            }
        }
    }
	
}
