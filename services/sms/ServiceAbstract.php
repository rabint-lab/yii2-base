<?php

namespace rabint\services\sms;

use rabint\helpers\str;
use yii\base\BaseObject;

Abstract class ServiceAbstract extends BaseObject
{

    public $verifyText = "%params1%";
    public $verifyTemplete="";

    abstract public function send($to, $message, $sender = null);

    public function sendVerifySms($to, $param1, $param2 = null, $param3 = null, $template = null, $sender = null)
    {
        $message = str_replace(["%params1%", "%params2%", "%params3%"], [$param1, $param2, $param3], $this->verifyText);
        $this->send($to, $message, $sender);
    }

    public function sendStrong($to, $message, $sender = null)
    {
        return $this->send($to, $message, $sender);
    }

    abstract public function sendBulk($to, $message, $sender = null);

    abstract public function getCredit();
}

?>