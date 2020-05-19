<?php

namespace rabint\components\log;

use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: mojtaba
 * Date: 7/28/18
 * Time: 11:52 AM
 */

class DbLog extends \yii\log\DbTarget
{

    /**
     * Stores log messages to DB.
     * Starting from version 2.0.14, this method throws LogRuntimeException in case the log can not be exported.
     * @throws Exception
     * @throws LogRuntimeException
     */
    public function export()
    {
        if ($this->db->getTransaction()) {
            // create new database connection, if there is an open transaction
            // to ensure insert statement is not affected by a rollback
            $this->db = clone $this->db;
        }

        $tableName = $this->db->quoteTableName($this->logTable);
        $sql = "INSERT INTO $tableName ([[level]], [[category]], [[log_time]], [[prefix]], [[message]], [[customer_id]], [[user_id]], [[user_email]], [[system_status]])
                VALUES (:level, :category, :log_time, :prefix, :message, :customer_id, :user_id, :user_email, :system_status)";
        $command = $this->db->createCommand($sql);
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

            $customer_id = isset($_SESSION['customer']) ? $_SESSION['customer']['id'] : null;
            $user_id = \app\helpers\UserHelper::getUserID();
            $user_email = \app\helpers\UserHelper::getUserEmail();

            $system_status = [];
            $system_status['URL'] = Url::current([],true);
            $system_status['MODE'] = (YII_DEBUG) ? 'debug' : 'product';
            $system_status['ENV'] = YII_ENV;
            $system_status['get'] = $_GET;
            $system_status['post'] = $_POST;
            $system_status['server'] = $_SERVER;
            $system_status['session'] = $_SESSION;

            $system_status = json_encode($system_status);
            if ($command->bindValues([
                    ':level' => $level,
                    ':category' => $category,
                    ':log_time' => $timestamp,
                    ':prefix' => $this->getMessagePrefix($message),
                    ':message' => $text,
                    ':customer_id' => $customer_id,
                    ':user_id' => $user_id,
                    ':user_email' => $user_email,
                    ':system_status' => $system_status,
                ])->execute() > 0) {
                continue;
            }
//            throw new LogRuntimeException('Unable to export log through database!');
        }
    }
}