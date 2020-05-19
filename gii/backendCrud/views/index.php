<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$controllerClass = $generator->controllerClass;
echo "<?php\n";
?>

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
<?= $generator->enablePjax ? 'use yii\widgets\Pjax;' : '' ?>

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grid-box <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card block block-rounded">
                <?= "<?= " ?> Html::beginForm(['bulk'], 'post'); ?>
                <div class="box-header">
                    <div class="box-tools pull-right float-right">
                        <div class="input-group input-group-sm" style="width: 350px;">
                            <span class="input-group-addon bg-gray"><?= \Yii::t('rabint', 'Bulk action'); ?></span>
                            <?= "<?= " ?> Html::dropDownList('action', '', ArrayHelper::getColumn(<?= $controllerClass ?>::bulkActions(), 'title'), ['class' => 'form-control', 'prompt' => '']); ?>
                            <div class="input-group-btn">
                                <?= "<?= " ?> Html::submitButton(\Yii::t('rabint', 'Do'), ['class' => 'btn btn-info']); ?>
                            </div>
                            <div class="input-group-btn">
                                <?= "<?= " ?> Html::a(\Yii::t('rabint', 'add new'), ['create'], ['class' => 'btn btn-success']); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body block-content block-content-full">
                    <?= $generator->enablePjax ? '<?php Pjax::begin(); ?>' : '' ?>

                    <?= "<?= " ?>GridView::widget([
                    'layout' => "<div class=\"pull-left float-left\">{summary}</div>\n{items}\n{pager}",
                    'dataProvider' => $dataProvider,
                    <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n                        'columns' => [\n" : "'columns' => [\n"; ?>
                    ['class' => 'yii\grid\CheckboxColumn'],
                    //['class' => 'yii\grid\SerialColumn'],
                    //                            [
                    //                                'attribute' => 'id',
                    //                                'filterOptions' => ['style' => 'max-width:100px;'],
                    //                                'format' => 'raw',
                    //                            ],
                    <?php
                    $count = 0;
                    if (($tableSchema = $generator->getTableSchema()) === false) {
                        foreach ($generator->getColumnNames() as $name) {
                            if (++$count < 6) {
                                echo "                           '" . $name . "',\n";
                            } else {
                                echo "                           // '" . $name . "',\n";
                            }
                        }
                    } else {
                        foreach ($tableSchema->columns as $column) {
                            $format = $generator->generateColumnFormat($column);
                            if (++$count < 6) {
                                echo "                           '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                            } else {
                                echo "                           // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                            }
                        }
                    }
                    ?>
                    ['class' => 'yii\grid\ActionColumn'],
                    ],
                    ]); ?>
                    <?= $generator->enablePjax ? '<?php Pjax::end(); ?>' : '' ?>

                </div>
                <?= "<?= " ?> Html::endForm(); ?> 
            </div>
        </div>
    </div>
</div>

