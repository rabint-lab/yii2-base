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

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>
<?= "<?php " ?>$form = ActiveForm::begin(); ?>

<div class="clearfix"></div>
<div class="form-box <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">
    <div class="row">
        <div class="col-sm-8">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card block block-rounded">
                        <div class="card-header block-header">
                            <h3 class="block-title"><?= "<?= " ?>Html::encode($this->title) ?></h3>
                            <div class="box-tools pull-left float-left">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>

                        <div class="card-body block-content block-content-full">
                            
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
                        <div class="card-header block-header">
                            <h3 class="block-title"><?= '<?='; ?> <?= $generator->generateString('Title') ?><?= " ?>"; ?></h3>
                            <div class="box-tools pull-left float-left">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body block-content block-content-full">
                            ...
                        </div>
                    </div>
                </div>
                <?= "<?php  " ?> } <?= " ?>\n"; ?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="row">
                <!-- =================================================================== -->
                <div class="col-sm-12">
                    <div class="box box-success">
                        <div class="card-header block-header">
                            <h3 class="block-title"><?= '<?='; ?> <?= $generator->generateString('Save') ?><?= " ?>"; ?></h3>
                            <div class="box-tools pull-left float-left">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body block-content block-content-full">
                            <?= "<?php  " ?> //echo  $form->field($model, 'published_at')->widget('trntv\yii\datetimepicker\DatetimepickerWidget')<?= " ?>\n"; ?>
                            <?= "<?php  " ?> //echo  $form->field($model, 'status')->checkbox()<?= " ?>\n"; ?>
                        </div>
                        <div class="card-footer block-content block-content-full bg-gray-light">
                            <div class="pull-left float-left">
                                <?= "<?= " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('Create') ?> : <?= $generator->generateString('Update') ?>, ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
                            </div>
                        </div><!-- /.box-footer-->
                    </div>
                </div>
                <!-- =================================================================== -->
                <?= "<?php  " ?>if (FALSE AND !$model->isNewRecord) { <?= " ?>\n"; ?>
                <div class="col-sm-12">
                    <div class="box box-warning box-solid">
                        <div class="card-header block-header">
                            <h3 class="block-title"><?= '<?='; ?> <?= $generator->generateString('Stat') ?><?= " ?>"; ?></h3>
                            <div class="box-tools pull-left float-left">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fas fa-minus"></i></button>
                                <button class="btn btn-box-tool" data-widget="remove"><i class="fas fa-times"></i></button>
                            </div><!-- /.box-tools -->
                        </div><!-- /.box-header -->
                        <div class="card-body block-content block-content-full no-padding">
                            <ul class="nav nav-stacked">
                                <li>
                                    <a href="#">
                                        <?= '<?='; ?> <?= $generator->generateString('visit count') ?><?= " ?>\n"; ?>
                                        <span class="pull-left float-left badge bg-blue">0</span>
                                    </a>
                                </li>
                            </ul>
                        </div><!-- /.block-content block-content-full -->
                    </div><!-- /.box -->
                </div>
                <?= "<?php  " ?> } <?= " ?>\n"; ?>
                <!-- =================================================================== -->

            </div>
        </div>

    </div>
</div>

<?= "<?php " ?>ActiveForm::end(); ?>