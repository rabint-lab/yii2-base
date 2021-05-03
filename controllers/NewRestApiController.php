<?php

namespace rabint\controllers;

use rabint\helpers\user;
use rabint\user\behaviors\UserHttpHeaderAuth;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\web\Response;

class NewRestApiController extends \yii\rest\Controller
{
    var $renderHead = true;
    var $debug = false;
    var $allowGzip = false;
    var $format = Response::FORMAT_JSON;

    var $debugData = [];

    protected $globalErrors = [
        1 => 'parameters error',
        2 => 'validation error',
    ];
    private $error = [
        'error' => 0,
        'message' => '',
    ];
    private $head = [];

    private $outputFilters = [];

    public function init()
    {
        parent::init();
        $this->allowGzip = config('SERVICE.api.gzip', $this->allowGzip);
        $this->format = config('SERVICE.api.format', $this->format);
        $this->debug = config('SERVICE.api.debug', $this->debug);
        \Yii::$app->user->enableSession = false;
    }

    public function behaviors()
    {

        $parentBehavior = parent::behaviors(); // TODO: Change the autogenerated stub
        $parentBehavior['contentNegotiator']['formats'] = [
            'application/json' => $this->format,
        ];

        $parentBehavior['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                UserHttpHeaderAuth::className(),
            ],
        ];

//        $parentBehavior['corsFilter'] = [
//            'class' => \yii\filters\Cors::className(),
//            'cors' => [
//                // restrict access to
//                'Origin' => ['localhost:4200'],
//                // Allow only POST and PUT methods
//                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
//                // Allow only headers 'X-Wsse'
//                //'Access-Control-Request-Headers' => ['X-Wsse'],
//                // Allow credentials (cookies, authorization headers, etc.) to be exposed to the browser
//                'Access-Control-Allow-Credentials' => true,
//                // Allow OPTIONS caching
//                'Access-Control-Max-Age' => 3600,
//                // Allow the X-Pagination-Current-Page header to be exposed to the browser.
//                //'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
//            ],
//        ];

        return $parentBehavior;
    }


    public function beforeAction($action)
    {
        $res = parent::beforeAction($action);
        $this->initApi();
        return $res;
    }

    public function afterAction($action, $result)
    {
        $return = parent::afterAction($action, $result); // TODO: Change the autogenerated stub
        if (!empty($this->outputFilters)) {
            foreach ($this->outputFilters as $filter) {
                $return = $filter($return);
            }
        }
        return $this->output($return);
    }

    public function initApi()
    {
        /**
         * add aditional headers
         */
        $this->addHeader('_csrf', Yii::$app->request->csrfToken);
        $this->addHeader('request_time', microtime(true));
        //Yii::$app->response->headers->add('X-Powered-By', 'RabintCMF/3.1.3 (rabint.ir)');

        //Check access
        $hasGlobalAccess = false;

        /**
         * check global access by ApiAccessToken
         */
        $apikey = Yii::$app->request->headers->get('apikey', '');

        $additionalTokens = config('SERVICE.api.ApiAccessToken', []);

        if (!empty($additionalTokens)) {
            foreach ($additionalTokens as $additionalToken) {
                if ($additionalToken['token'] == $apikey) {
                    $allowIps = $additionalToken['token']['allowed_ips'] ?? [];
                    if (empty($allowIps)) {
                        $hasGlobalAccess = true;
                        break;
                    } else {
                        $ip = user::realIP();
                        if (in_array($ip, $allowIps)) {
                            $hasGlobalAccess = true;
                            break;
                        }
                    }
                }
            }
        }

        if (!$hasGlobalAccess) {
            return [
                'error' => 1,
                'error_message' => Yii::t('app', 'Global Access Token Error!!!'),
            ];
        }

        /**
         * parse agent and do check user can access or no
         * agent example: RazaviPlus/1.0 (Linux; Android 5.0.1; fa_IR; aid:4e4c5141b7cb261e)
         */


        $debug = config('SERVICE.api.debug', $this->debug);
        if ($debug) {
            if (\rabint\helpers\user::isGuest()) {
                $user = "__Guest__";
            } else {
                $user = \rabint\helpers\user::id();
            }
            $this->debugData = [
                'charset' => \Yii::$app->charset,
                '_GET' => $_GET,
                '_POST' => $_POST,
                '_FILES' => $_FILES,
                '_Filterd_Get' => Yii::$app->request->get(),
                '_Filterd_POST' => Yii::$app->request->post(),
                'user' => $user,
//                'globalApiToken' => $apikey,
//                'headers' => Yii::$app->request->getHeaders(),
//                '_SERVER' => $_SERVER
            ];
        }
    }

    public function addHeader($key, $val)
    {
        $this->head[$key] = $val;
    }

    public function getHeader()
    {
        $headers = Yii::$app->response->getHeaders()->toArray();
        foreach ($headers as $kay => $header) {
            if (is_array($header) && count($header) == 1) {

                $header = current($header);
            }
            $this->addHeader($kay, $header);
        }
        return $this->head;
    }

    /**
     * @param mixed $return
     * @param $function
     * @return mixed
     */
    protected function addResponseBodyfilter($function)
    {
        $this->outputFilters[] = $function;
    }

    private function output($data = null, $status = 200)
    {
        Yii::$app->response->statusCode = $status;
        $this->addHeader('response_time', microtime(true));
        if ($this->renderHead) {
            $output = [
                'head' => $this->error + $this->getHeader(),
                'body' => $data
            ];
            if ($this->debug) {
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
        //$this->setFormat($this->format);
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
    public function error($errorCode, $message = null, $status = 200)
    {

        $this->error = [
            'error' => $errorCode,
            'message' => !empty($message) ? $message : (isset($this->globalErrors[$errorCode]) ? $this->globalErrors[$errorCode] : "unknown api action error!"),
        ];
        return null;
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
        Yii::$app->getResponse()->format = $this->format;
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