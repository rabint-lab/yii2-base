<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use rabint\themes\codebase\ThemeAsset;

ThemeAsset::register($this);
rabint\assets\Bootstrap4RtlAsset::register($this);

$opts = \rabint\option\models\Option::get('general');

$this->title = $opts[0]['subject']." | ".$opts[0]['slogan'];
?>
<?php $this->beginPage() ?>
<!doctype html>
<html  lang="<?= Yii::$app->language ?>" dir="<?= rabint\helpers\locality::langDir(Yii::$app->language); ?>" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>

     <?=$content;?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
