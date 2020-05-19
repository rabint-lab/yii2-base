<?php

namespace rabint\services\sms\webservice;

class websmsIR extends \rabint\classes\sms\ServiceAbstract {

    protected static $wsdl_link = "http://s1.websms.ir/webservice/index.php?wsdl";
    public static $tariff = "http://spadsms.ir/";
    public static $creditUnit = 'rial';
    public static $from;
    public static $username;
    public static $password;

    public function send($to, $message, $sender = null) {
        ini_set('soap.wsdl_cache_enabled', 0);
        ini_set('soap.wsdl_cache_ttl', 0);
        // Check credit for the gateway
        if (!self::getCredit())
            return FALSE;

        $client = new \SoapClient(self::$wsdl_link);

        $result = $client->send(self::$username, self::$password, $to, self::$from, $message);
        if ($result['status'] == 0 || $result['status_message'] == 'sent') {
            return TRUE;
        }
        return FALSE;
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