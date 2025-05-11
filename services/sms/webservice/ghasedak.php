<?php

namespace rabint\services\sms\webservice;

class ghasedak extends \rabint\services\sms\ServiceAbstract
{

    public $from;
    public $api_key;

    public function send($to, $message, $sender = null)
    {
        try {
            $lineNumber = $sender ?? $this->from;
            $receptor = $to;
            $api = new \Ghasedak\GhasedakApi($this->api_key);
            return $api->SendSimple($receptor, $message, $lineNumber);
        } catch (\Ghasedak\Exceptions\ApiException $e) {
            Yii::error($e->errorMessage(), 'ghasedak');
            return false;
//            echo $e->errorMessage();
//            die('---');
        } catch (\Ghasedak\Exceptions\HttpException $e) {
            Yii::error($e->errorMessage(), 'ghasedak');
            return false;
//            echo $e->errorMessage();
//            die('---');
        }
    }

    public function sendVerifySms($to, $param1, $param2 = null, $param3 = null, $template = null, $sender = null)
    {
        try {
            $api = new \Ghasedak\GhasedakApi($this->api_key);
            $template = empty($template) ? $this->verifyTemplete : $template;
            return $api->Verify($to, $template, $param1, $param2 = null, $param3 = null);
        } catch (\Ghasedak\Exceptions\ApiException $e) {
            Yii::error($e->errorMessage(), 'ghasedak');
            return false;
//            echo $e->errorMessage();
//            die('---');
        } catch (\Ghasedak\Exceptions\HttpException $e) {
            Yii::error($e->errorMessage(), 'ghasedak');
            return false;
//            echo $e->errorMessage();
//            die('---');
        }
    }


    public function sendBulk($to, $message, $sender = null)
    {
        return FALSE;
    }

    public function getCredit()
    {
        return 0;
    }

}
