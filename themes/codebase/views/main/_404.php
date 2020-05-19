<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */
?>
<div class = "site-error">

    <h1><?= \Yii::t('rabint', 'صفحه مورد نظر شما پیدا نشد'); ?></h1>

    <div class="alert alert-danger">
        <?php echo nl2br(Html::encode($message)) ?>
    </div>

    <p class="class">
        <?= \Yii::t('rabint', 'کاربر گرامی !'); ?>
    <ul class="class">
        <li>
            <?= \Yii::t('rabint', 'ممکن است مطلب مورد نظر شما حذف شده باشد'); ?>
        </li>
        <li><?= \Yii::t('rabint', 'و یا پارامتر های ارسالی شما ناقص است'); ?></li>
        <li><?= \Yii::t('rabint', 'و یا نیاز است که شما وارد حساب کاربری خود گردید '); ?></li>

    </ul>
</div>
