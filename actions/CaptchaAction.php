<?php
/**
 * Created by PhpStorm.
 * User: mojtaba
 * Date: 3/4/19
 * Time: 9:40 AM
 */

namespace rabint\actions;

use Yii;
use yii\web\Response;

class CaptchaAction extends \yii\captcha\CaptchaAction
{
    public $autoRegenerate = true;

//    public function run()
//    {
//        if ($this->autoRegenerate && Yii::$app->request->getQueryParam(self::REFRESH_GET_VAR) === null) {
//            $this->setHttpHeaders();
//            Yii::$app->response->format = Response::FORMAT_RAW;
//            return $this->renderImage($this->getVerifyCode(true));
//        }
//        return parent::run();
//    }
}