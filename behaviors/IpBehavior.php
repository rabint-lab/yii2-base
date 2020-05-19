<?php

namespace rabint\behaviors;

use common\models\base\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * Description of IpBehavior
 *
 * @author plus
 */
class IpBehavior extends \yii\base\Behavior {

    public $ipAttribute = 'ip';
    public $agentAttribute = 'agent';
    public $updatable = false;

    /**
     * @inheritdoc
     */
    public function events() {
        if ($updatable) {
            return [
                ActiveRecord::EVENT_BEFORE_INSERT => 'setIpAndAgent',
                ActiveRecord::EVENT_BEFORE_UPDATE => 'setIpAndAgentOnUpdate'
            ];
        } else {
             return [
                ActiveRecord::EVENT_BEFORE_INSERT => 'setIpAndAgent',
            ];
        }
    }

    /**
     * @param $event \yii\web\UserEvent
     */
    public function setIpAndAgentOnUpdate() {
        if ($this->updatable) {
            $this->setIpAndAgent();
        }
    }

    public function setIpAndAgent() {
//        $scenarios = $this->owner->scenarios();
//        $fields = $scenarios[$this->owner->scenario];
//        if (!in_array($this->slugAttributeName, $fields)) {
//            return;
//        }
        /* @var $owner BaseActiveRecord */
        $owner = $this->owner;
        $owner->{$this->ipAttribute} = \rabint\helpers\user::ip();
        $owner->{$this->agentAttribute} = \rabint\helpers\user::agent();
    }

}
