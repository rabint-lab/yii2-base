<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */
?>
<div class = "site-error">

    <h1><?= \Yii::t('rabint', 'مطلب حذف شده است!'); ?></h1>

    <div class="alert alert-danger">
        <?php echo nl2br(Html::encode($message)) ?>
    </div>

    <p class="class">
        <?= \Yii::t('rabint', 'کاربر گرامی !'); ?>
    </p>
    <p class="class">
        <?= \Yii::t('rabint', 'مطلب یا صفحه مورد نظر شما حذف شده است. لطفا دوباره این صفحه را درخواست ندهید.'); ?>
    </p>

</div>
