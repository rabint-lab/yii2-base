<?php

/* @var $this \yii\web\View */
/* @var $content string */

$this->beginContent('@themeLayouts/common.php');
?>

<div class="card block block-rounded">
    <div class="card-header block-header block-header-default ">
        <h5 class="block-title"><?= $this->title; ?></h5>
    </div>
    <div class="card-body block-content block-content-full">
        <?= $content ?>
    </div>
</div>

<?php $this->endContent() ?>