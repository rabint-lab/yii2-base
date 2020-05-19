<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->searchModelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>
<?= "<?php " ?>$form = ActiveForm::begin([
//'action' => ['index'],
'method' => 'get',
]); ?>
<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><?= "<?= " ?><?= $generator->generateString('Search') ?> ?></h3>
    </div>
    <div class="panel-body">

        <div class="search_box <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-search">

            <div class="row">

                <?php
                $count = 0;
                foreach ($generator->getColumnNames() as $attribute) {
                    if (++$count < 6) {
                        echo "        <div class=\"col-sm-4\"><?= " . $generator->generateActiveSearchField($attribute) . " ?></div>\n\n";
                    } else {
                        echo "        <!--<div class=\"col-sm-4\"><?php // echo " . $generator->generateActiveSearchField($attribute) . " ?></div>-->\n\n";
                    }
                }
                ?>

            </div>

        </div>
    </div>
    <div class="panel-footer">
        <?= "<?php // echo " ?>Html::resetButton(<?= $generator->generateString('Reset') ?>, ['class' => 'btn btn-default pull-left']) ?>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Reset') ?>, ['index'], ['class' => 'btn btn-default pull-left']) ?>
        <?= "<?= " ?>Html::submitButton(<?= $generator->generateString('Search') ?>, ['class' => 'btn btn-primary pull-left']) ?>
        <div class="clearfix"></div>
    </div>
</div>
<?= "<?php " ?>ActiveForm::end(); ?>