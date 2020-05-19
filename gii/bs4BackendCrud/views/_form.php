<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \common\models\base\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
$isModalAjax = Yii::$app->request->isAjax;

$this->context->layout = "@themeLayouts/full";

?>
<?= "<?php " ?>$form = ActiveForm::begin(); ?>


<div class="clearfix"></div>
<div class="form-block <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">
    <div class="row">
        <div class="col-sm-<?= '<?= '?>$isModalAjax?'12':'8';?>">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card block block-rounded <?= '<?= '?>$isModalAjax?'ajaxModalBlock':'';?>">
                        <div class="card-header block-header block-header-default">
                            <h3 class="block-title"><?= "<?= " ?>Html::encode($this->title) ?></h3>
                        </div>

                        <div class="card-body block-content">
                            
<?php
foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes)) {
        echo "                            <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
    }
}
?>
                        </div>
                    </div>
                </div>
                <!-- =================================================================== -->
                <?= "<?php  " ?>if (FALSE AND !$model->isNewRecord) { <?= " ?>\n"; ?>
                <div class="col-sm-12">
                    <div class="card block block-rounded">
                        <div class="card-header block-header block-header-default">
                            <h3 class="block-title"><?= '<?='; ?> <?= $generator->generateString('Title') ?><?= " ?>"; ?></h3>
                        </div>
                        <div class="card-body block-content">
                            ...
                        </div>
                    </div>
                </div>
                <?= "<?php  " ?> } <?= " ?>\n"; ?>
            </div>
        </div>
        <div class="col-sm-<?= '<?= '?>$isModalAjax?'12':'4';?>">
            <div class="row">
                <!-- =================================================================== -->
                <div class="col-sm-12">
                    <div class="card block block-success">
                        <div class="card-header block-header block-header-default">
                            <h3 class="block-title"><?= '<?='; ?> <?= $generator->generateString('Save') ?><?= " ?>"; ?></h3>
                        </div>
                        <div class="card-body block-content">
                            <?= "<?php  " ?> //echo  $form->field($model, 'published_at')->widget('trntv\yii\datetimepicker\DatetimepickerWidget')<?= " ?>\n"; ?>
                            <?= "<?php  " ?> //echo  $form->field($model, 'status')->checkblock()<?= " ?>\n"; ?>
                        </div>
                        <div class="card-body block-content block-content-full">
                            <div class="text-center">
                                <?= "<?= " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('Create') ?> : <?= $generator->generateString('Update') ?>, ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
                            </div>
                        </div><!-- /.block-content block-content-full-->
                    </div>
                </div>
                <!-- =================================================================== -->
                <?= "<?php  " ?>if (FALSE AND !$model->isNewRecord) { <?= " ?>\n"; ?>
                <div class="col-sm-12">
                    <div class="card block block-warning block-solid">
                        <div class="card-header block-header block-header-default">
                            <h3 class="block-title"><?= '<?='; ?> <?= $generator->generateString('Stat') ?><?= " ?>"; ?></h3>
                            <div class="block-tools text-center">
                                <button class="btn btn-block-tool" data-widget="collapse"><i class="fas fa-minus"></i></button>
                                <button class="btn btn-block-tool" data-widget="remove"><i class="fas fa-times"></i></button>
                            </div><!-- /.block-tools -->
                        </div><!-- /.block-header -->
                        <div class="card-body block-content no-padding">
                            <ul class="nav nav-stacked">
                                <li>
                                    <a href="#">
                                        <?= '<?='; ?> <?= $generator->generateString('visit count') ?><?= " ?>\n"; ?>
                                        <span class="text-center badge bg-blue">0</span>
                                    </a>
                                </li>
                            </ul>
                        </div><!-- /.block-content -->
                    </div><!-- /.block -->
                </div>
                <?= "<?php  " ?> } <?= " ?>\n"; ?>
                <!-- =================================================================== -->

            </div>
        </div>

    </div>
</div>

<?= "<?php " ?>ActiveForm::end(); ?>