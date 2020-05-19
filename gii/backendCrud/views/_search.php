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

<div class="search_box <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-search">

    <div class="row">
        <?= "<?php " ?>$form = ActiveForm::begin([
            //'action' => ['index'],
            'method' => 'get',
        ]); ?>
        
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
        <div class="form-group  center center-block">
            <?= "<?= " ?>Html::submitButton(<?= $generator->generateString('Search') ?>, ['class' => 'btn btn-primary']) ?>
            <?= "<?php // echo " ?>Html::resetButton(<?= $generator->generateString('Reset') ?>, ['class' => 'btn btn-default']) ?>
            <?= "<?= " ?>Html::a(<?= $generator->generateString('Reset') ?>, ['index'], ['class' => 'btn btn-default']) ?>
        </div>

        <?= "<?php " ?>ActiveForm::end(); ?>
    </div>
    
</div>
