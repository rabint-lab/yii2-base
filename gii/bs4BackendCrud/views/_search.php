<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->searchModelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card block block-rounded block-mode-loading-refresh">
    <div class="card-header block-header">
        <h3 class="card-title block-title float-start">
            <?= "<?= " ?> \Yii::t('rabint', 'جستجو'); ?>
        </h3>
        <div class="block-options card-header-actions float-end">
            <button type="button" class="card-header-action btn-minimize btn-block-option block-minimize-btn btn btn-sm btn-link">
                <i class="btnicon fas fa-chevron-down"></i>
            </button>
        </div>
        <div class="clearfix"></div>
    </div>
    <?= "<?php " ?>$form = ActiveForm::begin([
    //'action' => ['index'],
    'method' => 'get',
    ]); ?>

    <div class="card-body block-content bg-body-light" style="display: none;">
        <div class="search_box <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-search">
            <div class="row">
                <?php
                $count = 0;
                foreach ($generator->getColumnNames() as $attribute) {
                    if (++$count < 6) {
                        echo "                <div class=\"col-sm-4\"><?= " . $generator->generateActiveSearchField($attribute) . " ?></div>\n\n";
                    } else {
                        echo "                <!--<div class=\"col-sm-4\"><?php // echo " . $generator->generateActiveSearchField($attribute) . " ?></div>-->\n\n";
                    }
                }
                ?>

                <?= "<?php " ?> /* ************************************************** * / ?>
                <div class="col-sm-4">
                    <div class="form-group field-cdrsearch-duration has-success">
                        <label class="control-label" for="cdrsearch-duration"><?= "<?= " ?>\Yii::t('app', 'تاریخ');
                            ?></label>
                        <div class="input-group">
                            <?= "<?= " ?>Html::activeInput('date', $model, 'calldate_from', ['class' => 'form-control',
                            'placeholder' => 'از']) ?>
                            <?= "<?= " ?>Html::activeInput('date', $model, 'calldate_to', ['class' => 'form-control',
                            'placeholder' => 'تا']) ?>
                        </div>
                        <div class="help-block">
                            <?= "<?= " ?>\Yii::t('app', 'بازه تاریخ مورد نظر بصورت شمسی یا قمری');?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group field-cdrsearch-duration has-success">
                        <label class="control-label" for="cdrsearch-duration"><?= "<?= " ?>\Yii::t('app', 'مدت مکالمه');
                            ?></label>
                        <div class="input-group">
                            <?= "<?= " ?> Html::activeInput('number', $model, 'duration_from', ['class' =>
                            'form-control', 'placeholder' => 'از']) ?>
                            <?= "<?= " ?> Html::activeInput('number', $model, 'duration_to', ['class' => 'form-control',
                            'placeholder' => 'تا']) ?>
                        </div>
                        <div class="help-block">
                            <?= "<?= " ?>\Yii::t('app', 'مدت مکالمه به ثانیه'); ?>
                        </div>
                    </div>
                </div>
                <?= "<?php " ?> /* ************************************************** */ ?>

            </div>
            <div class="row">
                <div class="card-body block-content block-content-full">
                    <div class=" center center-block">
                        <?= "<?= " ?> Html::submitButton(Yii::t('rabint', 'Search'), ['class' => 'btn btn-info
                        btn-noborder']) ?>
                        <?php // echo Html::resetButton(Yii::t('rabint', 'Reset'), ['class' => 'btn btn-default']) 
                        ?>
                        <?= "<?= " ?> Html::a(Yii::t('rabint', 'Reset'), ['index'], ['class' => 'btn btn-outline-info'])
                        ?>
                    </div>
                </div>
            </div>

        </div>
        <?= "<?php " ?>ActiveForm::end(); ?>
    </div>
</div>