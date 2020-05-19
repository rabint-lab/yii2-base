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

class CaptchaNumericAction extends \yii\captcha\CaptchaAction
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
    protected function generateVerifyCode()
    {
        if ($this->minLength > $this->maxLength) {
            $this->maxLength = $this->minLength;
        }
        if ($this->minLength < 3) {
            $this->minLength = 3;
        }
        if ($this->maxLength > 8) {
            $this->maxLength = 8;
        }
        $length = mt_rand($this->minLength, $this->maxLength);
        $digits = '0123456789';
        $code = '';
        for ($i = 0; $i < $length; ++$i) {
            $code .= $digits[mt_rand(0, 9)];
        }
        return $code;
    }
}