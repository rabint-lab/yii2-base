<?php

namespace rabint\services\sms;

use rabint\classes\sms\ServiceAbstract;
use yii\base\BaseObject;

/**
 * Author: Mojtaba Akbarzadeh
 * Author Email: akbarzadeh.mojtaba@gmail.com
 */
class sms extends BaseObject
{

    /**
     *
     * @return ServiceAbstract
     */
    static function getService()
    {
        $smsConf = config("SERVICE.sms", NULL);
        if ($smsConf == null) {
            return FALSE;
        }
        $service = new \ReflectionClass($smsConf['serviceClass']);
//        var_dump($smsConf['serviceConfig']);
//        die('---');
        $serviceInstance = $service->newInstanceArgs([$smsConf['serviceConfig']]);
        return $serviceInstance;
    }

    /**
     *
     * @return \rabint\rabint\classes\sms\webservice\kavenegar
     */
    static function send($to, $message)
    {
        $service = static::getService();
//        var_dump($service);
//        die('---');
        try {
            return $service->send($to, $message);
        } catch (\Exception $ex) {
//            var_dump($ex);
            return FALSE;
        }
    }

    /**
     *
     * @return \rabint\rabint\classes\sms\webservice\kavenegar
     */
    static function sendVerify($to, $param1, $param2 = null, $param3 = null, $template = null)
    {
        $service = static::getService();
//        var_dump($service);
//        die('---');
        try {
            return $service->sendVerifySms($to, $param1, $param2, $param3, $template);
        } catch (\Exception $ex) {
//            var_dump($ex);
            return FALSE;
        }
    }


    /**
     *
     * @return \rabint\rabint\classes\sms\webservice\kavenegar
     */
    static function sendBulk($to, $message)
    {
        $service = static::getService();
        try {
            return $service->sendBulk($to, $message, $sender = '');
        } catch (\Exception $ex) {
            return FALSE;
        }
    }

    static function sendStrong($to, $params)
    {
        return static::send($to, $message);
//        try {
//            $json = file_get_contents('https://api.kavenegar.com/v1//verify/lookup.json?'
//                    . 'receptor=' . $to
//                    . '&token=' . $params['token']
//                    . '&template=verify');
//            $return = json_decode($json, true);
//            return $return['return']['status'] == 200 ? true : false;
//        } catch (\Exception $ex) {
//            return FALSE;
//        }
    }

    static function credit()
    {
        $service = static::getService();
        return $service->getCredit($to, $sender = '', $message);
    }

}
