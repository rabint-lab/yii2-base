<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ListView;
<?= $generator->enablePjax ? 'use yii\widgets\Pjax;' : '' ?>

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="list_box <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">

    <h3><?= "<?= " ?>Html::encode($this->title) ?></h3>
    <div class="row">
<?php if(!empty($generator->searchModelClass)): ?>
<?= "    <?php "; ?>echo $this->render('_search', ['model' => $searchModel]); ?>
<?php endif; ?>

    <?= $generator->enablePjax ? '<?php Pjax::begin(); ?>' : '' ?>

    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            <?php 
            echo "/*\n            ";
            if (($tableSchema = $generator->getTableSchema()) === false) {
                foreach ($generator->getColumnNames() as $name) {
                        echo " '" . $name . "', ";
                }
            } else {
                foreach ($tableSchema->columns as $column) {
                    $format = $generator->generateColumnFormat($column);
                    echo " '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "', ";
                }
            }
            echo "\n            */";
            ?>
        
            ob_start();<?php echo " ?>\n";?>
        
            <div class="col-sm-12">
                <h4 class="title">
                    <?php echo '<?= ';?>Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);<?php echo "?>\n";?>
                </h4>
            </div>
            
            <?php echo '<?php ';?> return ob_get_clean();
        },
    ]) ?>

    <?= $generator->enablePjax ? '<?php Pjax::end(); ?>' : '' ?>
    
    </div>
</div>
