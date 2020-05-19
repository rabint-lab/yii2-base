<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use rabint\themes\basic\ThemeAsset;

ThemeAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="//cdn.rawgit.com/morteza/bootstrap-rtl/v3.3.4/dist/css/bootstrap-rtl.min.css">
</head>
<body>
<?php $this->beginBody() ?>
<?php echo  $content; ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
