<?php

namespace rabint\behaviors;

use yii;
use rabint\helpers\locality;
use yii\db\Schema;
use rabint\models\ActiveRecordLogModel as ActiveRecordLog;
use yii\db\ActiveRecord;
use rabint\helpers\user;

/**
 *  useage example :
 *   public function behaviors()
 *   {
 *       return [
 *           [
 *               'class' => \rabint\behaviors\ActiveRecordLogableBehavior::class,
 *           ]
 *       ];
 *   }
 */
class ActiveRecordLogableBehavior extends \yii\base\Behavior
{
	private $_oldattributes = array();
        
        public $user_alias = '';
        
    public function events(){
            return [
                ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
                ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
                ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
            ];
        }
    
	public function afterUpdate()
	{
            if (!$this->owner->isNewRecord) {

                // new attributes
                $newattributes = $this->owner->getAttributes();
                $oldattributes = $this->getOldAttributes();

                // compare old and new
//                pr(debug_backtrace(),1);
                foreach ($newattributes as $name => $value) {
                    if (!empty($oldattributes)) {
                            $old = $oldattributes[$name];
                    } else {
                            $old = '';
                    }

                    if ($value != $old) {
                        $log=new ActiveRecordLog;
                        
                        $log->old_value=        $old;
                        $log->new_value=        $value;
                        $log->action=		'CHANGE';
                        $log->model=		get_class($this->owner);
                        $log->model_id=		$this->owner->getPrimaryKey();
                        $log->field=		$name;
                        $log->created_at=     time();
                        $log->user_id=		user::id();
                        $log->user_alias=       $this->user_alias;
                        
                        if(!$log->save())
                            pr($log->errors,1);
                    }
                }
            }
	}

	public function afterDelete()
	{
            $log=new ActiveRecordLog;
            
            $log->action=		'DELETE';
            $log->model=		get_class($this->owner);
            $log->model_id=		$this->owner->getPrimaryKey();
            $log->field=		'';
            $log->created_at= new CDbExpression('NOW()');
            $log->user_id=		Yii::app()->user->id;
            $log->user_alias=       $this->user_alias;
            $log->save();
	}

	public function afterFind()
	{
		// Save old values
		$this->setOldAttributes($this->owner->getAttributes());
	}

	public function getOldAttributes()
	{
		return $this->_oldattributes;
	}

	public function setOldAttributes($value)
	{
		$this->_oldattributes=$value;
	}
}