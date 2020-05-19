<?php
/**
 * Created by PhpStorm.
 * User: mojtaba
 * Date: 2/17/19
 * Time: 10:10 AM
 */

namespace rabint\cheatsheet;

use Yii;
class Education
{
    public static function levels()
    {
        return [
            'کمتر از دیپلم' => \Yii::t('rabint', 'کمتر از دیپلم'),
            'دیپلم' => \Yii::t('rabint', 'دیپلم'),
            'کاردانی' => Yii::t('rabint', 'کاردانی'),
            'کارشناسی' => Yii::t('rabint', 'کارشناسی'),
            'کارشناسی ارشد' => Yii::t('rabint', 'کارشناسی ارشد'),
            'دکترا' => Yii::t('rabint', 'دکترا'),
            'بالاتر از دکترا' => Yii::t('rabint', 'بالاتر از دکترا'),
        ];
    }
}