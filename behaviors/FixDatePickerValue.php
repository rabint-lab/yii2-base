<?php

namespace rabint\behaviors;

use yii;
use yii\db\Schema;
use beensa\models\ActiveRecordLogModel as ActiveRecordLog;
use yii\db\ActiveRecord;
use beensa\helpers\user;
use rabint\helpers\locality;

/**
 *  useage example :
 *   public function behaviors()
 *   {
 *       return [
 *           [
 *               'class' => \beensa\behaviors\FixDatePickerValue::class,
 *              'fields' => ['foo','bar','...'],
 *              'return' => \beensa\behaviors\FixDatePickerValue::RETURN_DATE
 *           ]
 *       ];
 *   }
 */
class FixDatePickerValue extends \yii\base\Behavior
{
    const RETURN_TIMESTAMP = 'timestamp';
    const RETURN_DATE = 'date';
    const RETURN_DATETIME = 'datetime';
    
    
    public $return = self::RETURN_DATETIME;
    public $fields;
    public $format = 'Y-m-d H:i:s';
    
        
    public function events(){
            return [
                ActiveRecord::EVENT_BEFORE_INSERT => 'setData',
                ActiveRecord::EVENT_BEFORE_UPDATE => 'setData',
                   ActiveRecord::EVENT_AFTER_FIND => 'afterFind'
            ];
        }
    
    public function setData(){
        if(!is_array($this->fields))new yii\base\Exception ('datePicker array field not defined');
        foreach($this->fields as $field){
            if(!is_numeric($field)){
                switch($this->return){
                    case self::RETURN_DATE:
                        $res = locality::anyToGregorian ($this->owner->$field,'Y-m-d');
                        break;
                    case self::RETURN_TIMESTAMP:
                        $res = locality::anyToTimeStamp($this->owner->$field);
                        
                        break;
                    case self::RETURN_DATETIME:
                    default:
                        $res = locality::anyToGregorian ($this->owner->$field,'Y-m-d');
                        break;
                }
                $this->owner->$field = $res;
                
            }
        }
    }
    
    public function afterFind(){
        foreach($this->fields as $field){

            $this->owner->$field = ($this->owner->$field ? locality::anyToJalali($this->owner->$field,$this->format):'');
        }
    }
	
}