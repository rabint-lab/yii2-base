<?php

namespace rabint\services\sms\webservice;

class kavenegar extends \rabint\services\sms\ServiceAbstract
{

    protected $apiKey;
    protected $debug;

    const APIPATH = "http://api.kavenegar.com/v1/%s/%s/%s.json/";

    public function __construct($apiKey, $debug = false)
    {
        if (!extension_loaded('curl')) {
            die('cURL library is not loaded');
            exit;
        }
        if (is_null($apiKey)) {
            die('apiKey is empty');
            exit;
        }
        $this->apiKey = $apiKey;
        $this->debug = $debug;
    }

    private function get_path($method, $base = 'sms')
    {
        return sprintf(self::APIPATH, $this->apiKey, $base, $method);
    }

    private function execute($url, $data = null)
    {

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded',
            'charset: utf-8'
        );
        $fields_string = "";
        if (!is_null($data)) {
            $fields_string = http_build_query($data);
        }
        if ($this->debug) {
            echo "[Request_param] : " . $fields_string . "\r\n";
        }

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $fields_string);

        $response = curl_exec($handle);
        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $content_type = curl_getinfo($handle, CURLINFO_CONTENT_TYPE);
        $curl_errno = curl_errno($handle);
        $curl_error = curl_error($handle);
        if ($curl_errno) {
            throw new HttpException($curl_error, $curl_errno);
        }
        $json_response = json_decode($response);
        if ($code != 200 && is_null($json_response)) {
            throw new HttpException("Request have errors", $code);
        } else {
            $json_return = $json_response->return;
            if ($json_return->status != 200) {
                throw new ApiException($json_return->message, $json_return->status);
            }
            if ($this->debug == true) {
                echo "[Responsive_Message] : " . $json_return->message . "\r\n";
                echo "[Responsive_Status] : " . $json_return->status . "\r\n";
                if (is_null($json_response->entries)) {
                    echo "[Responsive_Entries] : Null \n===========================\r\n";
                } else {
                    echo "=========================\r\n\r\n";
                }
            }
            return $json_response->entries;
        }
    }

    /**
     *
     * @param type $receptor
     * @param type $sender
     * @param type $message
     * @param type $date
     * @param type $type
     * @param type $localid
     * @return type
     */
    public function Send($receptor, $sender, $message, $date = null, $type = null, $localid = null)
    {
        if (is_array($receptor)) {
            $receptor = implode(",", $receptor);
        }
        if (is_array($localid)) {
            $localid = implode(",", $localid);
        }
        $path = $this->get_path("send");
        $params = array(
            "receptor" => $receptor,
            "sender" => $sender,
            "message" => $message,
            "date" => $date,
            "type" => $type,
            "localid" => $localid
        );
        return $this->execute($path, $params);
    }

    public function SendArray($receptor, $sender, $message, $date = null, $type = null, $localmessageid = null)
    {
        if (!is_array($receptor)) {
            $receptor = (array)$receptor;
        }
        if (!is_array($sender)) {
            $sender = (array)$sender;
        }
        if (!is_array($message)) {
            $message = (array)$message;
        }
        $repeat = count($receptor);
        if (!is_null($type) && !is_array($type)) {
            $type = array_fill(0, $repeat, $type);
        }
        if (!is_null($localmessageid) && !is_array($localmessageid)) {
            $localmessageid = array_fill(0, $repeat, $localmessageid);
        }
        $path = $this->get_path("sendarray");
        $params = array(
            "receptor" => json_encode($receptor),
            "sender" => json_encode($sender),
            "message" => json_encode($message),
            "date" => $date,
            "type" => json_encode($type),
            "localmessageid" => json_encode($localmessageid)
        );
        return $this->execute($path, $params);
    }

    public function Status($messageid)
    {
        $path = $this->get_path("status");
        $params = array(
            "messageid" => is_array($messageid) ? implode(",", $messageid) : $messageid
        );
        return $this->execute($path, $params);
    }

    public function StatusLocalMessageid($localid)
    {
        $path = $this->get_path("statuslocalmessageid");
        $params = array(
            "localid" => is_array($localid) ? implode(",", $localid) : $localid
        );
        return $this->execute($path, $params);
    }

    public function Select($messageId)
    {
        $params = array(
            "messageid" => is_array($messageid) ? implode(",", $messageid) : $messageid
        );
        $path = $this->get_path("select");
        return $this->execute($path, $params);
    }

    public function SelectOutbox($startdate, $enddate, $sender)
    {
        $path = $this->get_path("selectoutbox");
        $params = array(
            "startdate" => $startdate,
            "enddate" => $enddate,
            "sender" => $sender
        );
        return $this->execute($path, $params);
    }

    public function LatestOutbox($pagesize, $sender)
    {
        $path = $this->get_path("latestoutbox");
        $params = array(
            "pagesize" => $pagesize,
            "sender" => $sender
        );
        return $this->execute($path, $params);
    }

    public function CountOutbox($startdate, $enddate, $status = 0)
    {
        $path = $this->get_path("countoutbox");
        $params = array(
            "startdate" => $startdate,
            "enddate" => $enddate,
            "status" => $status
        );
        return $this->execute($path, $params);
    }

    public function Cancel($messageid)
    {
        $path = $this->get_path("cancel");
        $params = array(
            "messageid" => is_array($messageid) ? implode(",", $messageid) : $messageid
        );
        return $this->execute($path, $params);
    }

    public function Receive($linenumber, $isread = 0)
    {
        $path = $this->get_path("receive");
        $params = array(
            "linenumber" => $linenumber,
            "isread" => $isread
        );
        return $this->execute($path, $params);
    }

    public function CountInbox($startdate, $enddate, $linenumber, $isread = 0)
    {
        $path = $this->get_path("countinbox");
        $params = array(
            "startdate" => $startdate,
            "enddate" => $enddate,
            "linenumber" => $linenumber,
            "isread" => $isread
        );
        return $this->execute($path, $params);
    }

    public function CountPostalcode($postalcode)
    {
        $path = $this->get_path("countpostalcode");
        $params = array(
            "postalcode" => $postalcode
        );
        return $this->execute($path, $params);
    }

    public function SendbyPostalcode($postalcode, $sender, $message, $mcistartindex, $mcicount, $mtnstartindex, $mtncount, $date)
    {
        $path = $this->get_path("sendbypostalcode");
        $params = array(
            "postalcode" => $postalcode,
            "sender" => $sender,
            "message" => $message,
            "mcistartindex" => $mcistartindex,
            "mcicount" => $mcicount,
            "mtnstartindex" => $mtnstartindex,
            "mtncount" => $mtncount,
            "date" => $date
        );
        return $this->execute($path, $params);
    }

    public function AccountInfo()
    {
        $path = $this->get_path("info", "account");
        return $this->execute($path);
    }

    public function AccountConfig($apilogs, $dailyreport, $debug, $defaultsender, $mincreditalarm, $resendfailed)
    {
        $path = $this->get_path("config", "account");
        $params = array(
            "apilogs" => $apilogs,
            "dailyreport" => $dailyreport,
            "debug" => $debug,
            "defaultsender" => $defaultsender,
            "mincreditalarm" => $mincreditalarm,
            "resendfailed" => $resendfailed
        );
        return $this->execute($path, $params);
    }

    public function VerifyLookup($receptor, $token, $template)
    {
        $path = $this->get_path("lookup", "verify");
        $params = array(
            "receptor" => $receptor,
            "token" => $token,
            "template" => $template
        );
        return $this->execute($path, $params);
    }

    public function getCredit()
    {

    }

    public function sendBulk($to, $message, $sender = null)
    {

    }

    public function send($to, $message, $sender = null)
    {

    }

}

class BaseRuntimeException extends \RuntimeException
{

    public function getName()
    {
        return 'BaseRuntimeException';
    }

    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);
    }

    public function errorMessage()
    {
        return "\r\n" . $this->getName() . "[{$this->code}] : {$this->message}\r\n";
    }

}

class HttpException extends BaseRuntimeException
{

    public function getName()
    {
        return 'HttpException';
    }

}

class ApiException extends BaseRuntimeException
{

    public function getName()
    {
        return 'ApiException';
    }

}

abstract class General
{

    const Enabled = "enabled";
    const Disabled = "disabled";

}

abstract class ApiLogs extends General
{

    const Justforfault = "justforfault";
    const Enabled = "enabled";
    const Disabled = "disabled";

}
