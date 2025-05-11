<?php

namespace rabint\services\sms\webservice;

use DateTimeImmutable;
use Ghasedak\DataTransferObjects\Request\OtpMessageWithParamsDTO;
use Ghasedak\DataTransferObjects\Request\ReceptorDTO;
use Ghasedak\DataTransferObjects\Request\SingleMessageDTO;
use Ghasedak\Exceptions\GhasedakSMSException;
use Ghasedak\GhasedaksmsApi;

class ghasedakSms extends \rabint\services\sms\ServiceAbstract
{

    public $from;
    public $api_key;

    public function send($to, $message, $sender = null)
    {
        try {
            $lineNumber = $sender ?? $this->from;
            $receptor = $to;
            $api = new GhasedaksmsApi($this->api_key);
            return $api->sendSingle(new SingleMessageDTO(
                sendDate: new DateTimeImmutable('now'),
                lineNumber: $lineNumber,
                receptor: $receptor,
                message: $message
            ));
        } catch (GhasedakSMSException $e) {
            Yii::error($e->getMessage(), 'GhasedakSms');
            return false;
//            echo $e->getMessage();
//            die('---');
        }
    }

    public function sendVerifySms($to, $param1, $param2 = null, $param3 = null, $template = null, $sender = null)
    {
        $sendDate = new DateTimeImmutable('now');
        try {
            $api = new GhasedaksmsApi($this->api_key);
            $template = empty($template) ? $this->verifyTemplete : $template;

            $response = $api->sendOtpWithParams(new OtpMessageWithParamsDTO(
                sendDate: $sendDate,
                receptors: [
                    new ReceptorDTO(
                        mobile: $to,
                        clientReferenceId: '1'
                    )
                ],
                templateName: $template,
                param1: $param1,
                param2: $param2,
                param3: $param3,
            ));
            return true;
        } catch (GhasedakSMSException $e) {
            Yii::error($e->getMessage(), 'GhasedakSms');
            return false;
//            var_dump($e->getMessage());
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

?>
