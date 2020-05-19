<?php

namespace rabint\services\sms;

use yii\base\BaseObject;

Abstract class ServiceAbstract extends BaseObject {

    abstract public function send($to, $message, $sender = null);

    public function sendStrong($to, $message, $sender = null) {
        return $this->send($to, $message, $sender);
    }

    abstract public function sendBulk($to, $message, $sender = null);

    abstract public function getCredit();
}

?>