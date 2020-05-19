<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;

switch ($exception->statusCode) {
    case '403':
        $this->title = \Yii::t('rabint', 'خطای عدم دسترسی');
        break;
    case '404':
        echo $this->render('_404', [ 'name' => $name, 'message' => $message, 'exception' => $exception,]);
        return;
    case '410':
        echo $this->render('_410', [ 'name' => $name, 'message' => $message, 'exception' => $exception,]);
        return;
    case '400':
        echo $this->render('_404', [ 'name' => $name, 'message' => $message, 'exception' => $exception,]);
        return;
    case '500':
        echo $this->render('_500', [ 'name' => $name, 'message' => $message, 'exception' => $exception,]);
        return;
    default:
        break;
}
?>
<div class = "site-error">

    <h1><?php echo Html::encode($this->title)
?></h1>

    <div class="alert alert-danger">
        <?php echo nl2br(Html::encode($message)) ?>
    </div>

    <p class="class">
        <?= \Yii::t('rabint', 'کاربر گرامی!'); ?>
    </p>
    <p><?= \Yii::t('rabint', 'متاسفانه در هنگام اجرای درخواست شما ، خطایی رخ داده است. , لطفا دیگر این صفحه را درخواست ندهید و اگر می توانید با مدیریت سایت تماس بگیرید.'); ?></p>


</div>
