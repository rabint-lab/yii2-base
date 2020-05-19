<?php

namespace rabint\services\sms\webservice;

class sms3300 extends \rabint\classes\sms\ServiceAbstract {

    protected static $wsdl_link = "http://sms.3300.ir/almassms.asmx?wsdl"; //Web Service URL
//    protected static $wsdl_link = "http://94.232.173.124/almassms.asmx?wsdl"; //Web Service URL
    public static $tariff = "http://sms.3300.ir/almassms.asmx?wsdl";
    public static $creditUnit = 'rial';
    public $from;
    public $username;
    public $password;

    public function send($to, $message, $sender = null) {
        ini_set('soap.wsdl_cache_enabled', 0);
        ini_set('soap.wsdl_cache_ttl', 0);
        // Check credit for the gateway
//        if (!self::getCredit())
//            return FALSE;
//        $client = new \SoapClient(self::$wsdl_link);
        $client = new \nusoap_client(self::$wsdl_link, 'wsdl', '', '', '', '');
        $client->soap_defencoding = 'UTF-8';
        $client->decode_utf8 = FALSE;
        $param = [
            'pUsername' => $this->username,
            'pPassword' => $this->password,
            'messages' => ['string' => $message],
            'mobiles' => ['string' => $to],
            'Encodings' => ['int' => 2],
            'mclass' => ['int' => 1],
        ];
        $results = $client->call("SendSms2", $param);
//        $results = $client->SendSms2(['request' => $param]);
//        $results = $client->SendSms2( $this->username, $this->password, [$message], [$to],[], 2,1);
        if ($results['SendSms2Result'] < 0) {
            return true;
//            echo 'Method executed successfully without any errors' . '<br/>';
//            //var_dump($results);
//            foreach ($results['pMessageIds']['long'] as $pmId) {
//                if ($pmId < 1000)
//                    echo ++$c . ') ERROR' . $pmId . '-' . $MAGFA_errors[$pmId]['title'] . '<br/>';
//                else
//                    echo ++$c . ')' . 'Successfull(SMS ID : ' . $pmId . ')<br/>';
//            }
        } else {
            return FALSE;
//            echo 'Method execution failed' . '<br/>';
//            echo $METHOD_errors[$results['SendSms2Result']]['title'];
        }
    }

    public function sendBulk($to, $message, $sender = null) {
        return FALSE;
    }

    public function getCredit() {
        ini_set('soap.wsdl_cache_enabled', 0);
        ini_set('soap.wsdl_cache_ttl', 0);
        $client = new \SoapClient(self::$wsdl_link);
        $result = $client->accountInfo(self::$username, self::$password);
        return $result['balance'];
    }

}

?>