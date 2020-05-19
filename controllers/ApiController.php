<?php

namespace rabint\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ApiController extends Controller
{

    const DEBUG = true;

    /**
     * RESPOND_TYPES
     */
    const RESPOND_TYPE_JSON = 'json';
    const RESPOND_TYPE_JSONP = 'jsonp';
    const RESPOND_TYPE_XML = 'xml';
    const RESPOND_TYPE_HTML = 'html';
    const RESPOND_TYPE_TEXT = 'text';
    const RESPOND_TYPE_BLOB = 'blob';

    public $enableCsrfValidation = false;
    //for an action >> Yii::$app->controller->enableCsrfValidation = false;
    //or in beforeAction >> $this->enableCsrfValidation = false;

    var $needLogin = true;
    var $renderHead = true;
    var $allowGzip = false;
    var $debugData = [];
    var $format = 'json';
    var $errors = [];
    var $error = [
        'error' => 0,
        'message' => '',
    ];
    private $head = [];

    public function errorCodes()
    {
        return [
            0 => \Yii::t('rabint', 'عملیات با موفقیت انجام شد'),
            '404' => \Yii::t('rabint', 'درخواست یافت نشد'),
            '403' => \Yii::t('rabint', 'شما به این درخواست دسترسی ندارید'),
        ];
    }

    public function beforeAction($action)
    {
//        if (Yii::$app->keyStorage->get('app.maintenance') === 'enabled') {
//            if ($this->className() != 'app\controllers\MainController' OR $action->id != 'maintenance')
//                return $this->redirect(['/site/maintenance']);
//        }
        $this->initApi();
        if ($this->needLogin and \rabint\helpers\user::isGuest()) {
            $res = $this->error(5);
            $res->send();
            exit('');
        }
        return parent::beforeAction($action);
    }

    public function initApi()
    {
//        RazaviPlusApp/1.0 (Linux; Android 6.0; fa_IR; aid:19003c4dbb8f5bcb)


        $this->setFormat($this->format);
        $this->addHeader('_csrf', Yii::$app->request->csrfToken);
        $this->addHeader('request_time', time());
        $token = Yii::$app->request->cookies->getValue('token', '');
//        var_dump($token);
//        $request = Yii::$app->getRequest();
//        $data = Yii::$app->getSecurity()->validateData("", $request->cookieValidationKey);
//        $data = @unserialize($data);
//        var_dump($data);
//        die('----');

        if (!empty($token)) {
            $user = \common\models\UserTokenAndroid::getUserByToken($token);
            if (empty($user)) {
                Yii::$app->user->logout();
            } else {
                Yii::$app->user->login($user, 60);
            }
        } else {
            Yii::$app->user->logout();
        }
//      Yii::$app->response->headers->add('X-Powered-By', 'RabintCMF/3.1.3 (rabint.ir)');

        /**
         * parse agent and do check user can access or no
         * agent example: RazaviPlus/1.0 (Linux; Android 5.0.1; fa_IR; aid:4e4c5141b7cb261e)
         */
        if (self::DEBUG) {
            if (\rabint\helpers\user::isGuest()) {
                $user = "isGuest";
            } else {
                $user = \rabint\helpers\user::id();
            }
            $this->debugData = [
                'charset' => \Yii::$app->charset,
                '_GET' => $_GET,
                '_Filterd_Get' => Yii::$app->request->get(),
//                '_Cookie' => $_COOKIE,
//                '_CookieToken' => isset($_COOKIE['token'])?$_COOKIE['token']:"notSet",
                '_POST' => $_POST,
                '_Filterd_POST' => Yii::$app->request->post(),
                '_FILES' => $_FILES,
                'user' => $user,
                'token' => Yii::$app->request->cookies->getValue('token', ''),
                '_SERVER' => [
                    'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD']??null,
                    'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR']??null,
                    'HTTP_CACHE_CONTROL' => $_SERVER['HTTP_CACHE_CONTROL']??null,
                    'HTTP_CONNECTION' => $_SERVER['HTTP_CONNECTION']??null,
                    'HTTP_ACCEPT_ENCODING' => $_SERVER['HTTP_ACCEPT_ENCODING']??null,
                    'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT']??null,
                    'HTTP_COOKIE' => $_SERVER['HTTP_COOKIE']??null,
                ],
            ];
        }
    }

    public function addHeader($key, $val)
    {
        $this->head[$key] = $val;
    }

    public function getHeader()
    {
        return $this->head;
    }

    /**
     *
     * @param type $data
     * @param type $status
     * @return \yii\web\Response
     */
    public function output($data = null, $status = 200)
    {
        Yii::$app->response->statusCode = $status;
        if ($this->renderHead) {
            $output = [
                'head' => $this->error + $this->getHeader(),
                'body' => $data
            ];
            if (self::DEBUG) {
                $output['debug'] = ['HttpStatus' => $status] + $this->debugData;
            }
        } else {
            $output = $data;
        }

        /**
         * Gzip handle
         */
        if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') && $this->allowGzip) {

            \yii\base\Event::on(
                \yii\web\Response::className(), \yii\web\Response::EVENT_BEFORE_SEND, function ($event) {
                ob_start("ob_gzhandler");
            }
            );
            \yii\base\Event::on(
                \yii\web\Response::className(), \yii\web\Response::EVENT_AFTER_SEND, function ($event) {
                ob_end_flush();
            }
            );
        }
        $this->setFormat($this->format);
        $response = Yii::$app->getResponse();
        $response->data = $output;
        return $response;
    }

    /**
     *
     * @param type $data
     * @param type $status
     * @return \yii\web\Response
     */
    public function error($errorCode = null, $status = 200) {
        $errorMessage = $this->errors;
        //$errorMessage = include Yii::getAlias('@rabint/api/errorMessage.php');
        $this->error = [
            'error' => $errorCode,
            'message' => $errorMessage[$errorCode]??'unknown error',
        ];
        return $this->output(null, $status);
    }

    /**
     *
     * @param type $data
     * @param type $status
     * @return \yii\web\Response
     */
    public function listOutput($data)
    {
        $content = \rabint\widgets\JsonListView::widget($data);
        $dataProvider = $data['dataProvider'];
        /**
         * add pagination data
         */
        $this->addHeader('pagination', [
            'size' => $dataProvider->getCount(),
            'current' => $dataProvider->getPagination()->getPage() + 1,
            'total' => $dataProvider->getTotalCount(),
        ]);
        /**
         * output
         */
        return $this->output($content, $status);
    }

    /* =================================================================== */

    protected function setFormat($format)
    {
        $this->format = $format;
        $response = Yii::$app->getResponse();
        switch ($format) {
            case static::RESPOND_TYPE_XML:
                $response->format = \yii\web\Response::FORMAT_XML;
                break;
            case static::RESPOND_TYPE_JSON:
                $response->format = \yii\web\Response::FORMAT_JSON;
                break;
            case static::RESPOND_TYPE_JSONP:
                $response->format = \yii\web\Response::FORMAT_JSONP;
                break;
            case static::RESPOND_TYPE_BLOB:
//                $response->sendContentAsFile($content, $attachmentName, $codes);
                $response->format = \yii\web\Response::FORMAT_RAW;
                break;
            case static::RESPOND_TYPE_HTML:
                $response->format = \yii\web\Response::FORMAT_HTML;
                break;
            case static::RESPOND_TYPE_TEXT:
            default :
                $response->format = \yii\web\Response::FORMAT_RAW;
        }
    }

    public function render($view, $params = [], $handleAjax = true)
    {
        if ($handleAjax && Yii::$app->request->isAjax) {
            return $this->renderAjax($view, $params);
        }

        return parent::render($view, $params); // TODO: Change the autogenerated stub
    }

//    public function actionReceive() {
//
//        $err = '';
//        if (!isset($_GET['pass']) OR $_GET['pass'] != 'sTR7s5Axz01') {
//            $err .= 'AuthenticationError! ';
//        }
//        /* ------------------------------------------------------ */
//
//        $from = (isset(Yii::$app->request->post()['from'])) ? Yii::$app->request->post()['from'] : NULL;
//        $to = (isset(Yii::$app->request->post()['to'])) ? Yii::$app->request->post()['to'] : NULL;
//        $message = (isset(Yii::$app->request->post()['message'])) ? Yii::$app->request->post()['message'] : NULL;
//        $messageid = (isset(Yii::$app->request->post()['messageid'])) ? Yii::$app->request->post()['messageid'] : NULL;
//
//        /* ------------------------------------------------------ */
//
//        if ($from == NULL OR $to == NULL OR $message == NULL OR $messageid == NULL) {
//            $err = 'parameterError ';
//        }
//        /* ------------------------------------------------------ */
//        $message = trim($message);
//        $message = base64_decode($message);
//        $message = str_replace('&amp;', '&', $message);
//
//        if (strpos($message, 'act::') === 0) {
//            $targetLink = str_replace('act::', 'http://pmoshfi.ir/', $message);
//            $actionType = self::AT_ACTION;
//        } elseif (strpos($message, 'get::') === 0) {
//            $targetLink = str_replace('get::', 'http://pmoshfi.ir/', $message);
//            $actionType = self::AT_SENDRESPOND;
//        } else {
//            $err = 'actionNotFind ';
//        }
//        /* ------------------------------------------------------ */
//        $logFileAddr = \Yii::getAlias('@app/web/smsLog') . '/' . date('Y-m') . '.log';
//
//        if (!empty($err)) {
//            file_put_contents(
//                    $logFileAddr, "\nerrReq: " . $message . "\n" .
//                    'res: ' . $err . "\n" .
//                    'from: ' . $from . "\t to: " . $to . "\t mId: " . $messageid . "\n" .
//                    'By: ' . print_r($_SERVER['REMOTE_ADDR'], TRUE) . ', at: ' . date('Y-m-d H:i:s') . "\n________________________________________________________________________________\n", FILE_APPEND);
//            die('0');
//        }
//        $targetLink = \rabint\helpers\collection::addUrlParam($targetLink, ['medium' => 'Api-SMS']);
//        $res = file_get_contents($targetLink);
//        if ($actionType == self::AT_SENDRESPOND) {
//            $cell = Customer::CellFormatter($from);
//            if ($cell) {
//                \rabint\sms::send($from, $res);
//            }
//        }
//        /* =================================================================== */
//        file_put_contents(
//                $logFileAddr, "\n" . $actionType . ': ' . $targetLink . "\n" .
//                'res: ' . $res . "\n" .
//                'from: ' . $from . "\t to: " . $to . "\t mId: " . $messageid . "\n" .
//                'By: ' . print_r($_SERVER['REMOTE_ADDR'], TRUE) . ', at: ' . date('Y-m-d H:i:s') . "\n________________________________________________________________________________\n", FILE_APPEND);
//        die('1');
//    }
}
