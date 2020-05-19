<?php

use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

$this->beginContent('@themeLayouts/base.php');
?>
    <div class="container-fluid">
        <div class="col-sm-12">

            <?php
            echo Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ])
            ?>
            <?php foreach (Yii::$app->session->getAllFlashes() as $type => $body) {
                ?>
                <div class="alert alert-<?= $type; ?> alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php
                    switch ($type) {
                        case 'alert':
                        case 'danger':
//                            echo '<h4><i class="icon fas fa-ban"></i>'.Yii::t('rabint','اخطار').'</h4>';
                        case 'info':
//                            echo '<h4><i class="icon fas fa-info"></i>'.Yii::t('rabint','نکته').'</h4>';
                        case 'warning':
//                            echo '<h4><i class="icon fas fa-exclamation-triangle"></i>'.Yii::t('rabint','هشدار').'</h4>';
                        case 'success':
//                            echo '<h4><i class="icon fas fa-check"></i>'.Yii::t('rabint','پیام').'</h4>';
                    }
                    ?>
                    <?= print_r($body, true); ?>
                </div>
            <?php } ?>

            <?php echo $content ?>
        </div>

    </div>
<?php $this->endContent() ?>