<?php

/* @var $this \yii\web\View */
/* @var $content string */

$this->beginContent('@themeLayouts/common.php',[
    'pageClass'=>'sidebar-inverse enable-page-overlay side-scroll page-header-fixed page-header-modern ',
]);
?>

<?= $content ?>


<?php $this->endContent() ?>