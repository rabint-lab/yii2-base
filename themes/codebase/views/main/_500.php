<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */
?>
<div class = "site-error">

    <h1><?= \Yii::t('rabint', 'متاسفانه خطای داخلی رخ داده است'); ?></h1>

    <div class="alert alert-danger">
        <?php echo nl2br(Html::encode($message)) ?>
    </div>

    <p class="class">
        <?= \Yii::t('rabint', 'کاربر گرامی !'); ?>
    </p>
    <p>
        <?= \Yii::t('rabint', 'با عرض پوزش سیستم در هنگام انجام  درخواست شما با خطا مواجه گردیده است.'); ?>
    </p>
    <p>
        <?= \Yii::t('rabint', 'گزارش خطا در سیستم بطور خودکار ثبت گردیده است و در اسرع وقت توسط پشتیبان های فنی وبسایت بر طرف می گردد.'); ?>
    </p>
    <p class="class">
        <?= \Yii::t('rabint', 'از صبر و شکیبایی شما سپاسگزاریم'); ?>
    </p>

</div>
