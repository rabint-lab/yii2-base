<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use yii\bootstrap4\Html;


/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */


$this->title = <?= $generator->generateString('Create')." .  ' ' . ". $generator->generateString(Inflector::camel2words(StringHelper::basename($generator->modelClass))); ?> . ' ' . $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box-form <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-create"  id="ajaxCrudDatatable">

    <h2 class="ajaxModalTitle" style="display: none"><?='<?= '?> $this->title; ?></h2>
    <?= "<?= " ?>$this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
