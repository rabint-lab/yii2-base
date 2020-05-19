<?php
/**
 * Created by PhpStorm.
 * User: mojtaba
 * Date: 10/21/18
 * Time: 4:41 PM
 */

namespace rabint\components\log;


use yii\helpers\VarDumper;
use yii\log\Target;
use rabint\helpers\user::;

class SlackTarget extends Target
{

    public $token;
    public $channelId;
    public $channelId400 = "";

    public function init()
    {
        parent::init();
    }

    /**
     * Stores log messages to DB.
     * Starting from version 2.0.14, this method throws LogRuntimeException in case the log can not be exported.
     * @throws Exception
     * @throws LogRuntimeException
     */
    public function export()
    {
        foreach ($this->messages as $message) {

            list($text, $level, $category, $timestamp) = $message;
            if (!is_string($text)) {
                // exceptions may not be serializable if in the call stack somewhere is a Closure
                if ($text instanceof \Throwable || $text instanceof \Exception) {
                    $text = (string)$text;
                } else {
                    $text = VarDumper::export($text);
                }
            }
            switch ($level) {
                case E_WARNING:
                    $severity = "WARNING";
                    break;
                case E_ERROR:
                    $severity = "ERROR";
                    break;
                case E_NOTICE:
                    $severity = "NOTICE";
                    break;
                case E_COMPILE_ERROR:
                    $severity = "COMPILE_ERROR";
                    break;
                case E_COMPILE_WARNING:
                    $severity = "COMPILE_WARNING";
                    break;
                case E_PARSE:
                    $severity = "PARSE";
                    break;
                case E_DEPRECATED:
                    $severity = "DEPRECATED";
                    break;
                default:
                    $severity = "unknown";
            }

            $url = \Yii::$app->request->getAbsoluteUrl();
            $referrer = \Yii::$app->request->referrer;
            $user = (user::isGuest()) ? 'Guest' : \Yii::$app->user->identity->email . '(' . \Yii::$app->user->identity->id . ") ";
            $ip = user::getRealUserIP();

            $date = date('Y-m-d H:i:s', $timestamp);

            $slackMessage = <<<EOT

`Severity` => {$severity}
`level` => {$level}
`category` => {$category}
`log_time` => {$date} ({$timestamp})
`prefix` => {$this->getMessagePrefix($message)}
`message` => {$text}
`url` => {$url}
`referer` => {$referrer}
`user` => {$user}
`ip` => {$ip}

__________________________________________________

EOT;

            if(($category == "yii\web\HttpException:404" OR $category == "yii\web\HttpException:403") AND !empty($this->channelId400)) {
                $channelId = $this->channelId400;
            } else {
                $channelId = $this->channelId;
            }

            @file_get_contents("https://slack.com/api/chat.postMessage" .
                "?token=" . $this->token .
                "&channel=" . $channelId .
                "&text=" . urlencode($slackMessage) . "&pretty=1");

        }
    }
}
