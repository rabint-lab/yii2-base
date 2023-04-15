<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\bootstrap4\Modal;
use yii\helpers\Url;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
echo "<?php\n";
?>
use yii\helpers\Url;
use yii\bootstrap4\Html;
use rabint\widgets\GridView;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;

$this->context->layout = "@themeLayouts/full";


?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index"  id="ajaxCrudDatatable">
        
    <h2 class="ajaxModalTitle" style="display: none"><?='<?= '?> $this->title; ?></h2>
    <div class="content-search">
        <?="<?php echo "?>$this->render('_search',['model'=>$searchModel]);?>
    </div>
    <div id="ajaxCrudDatatable">
        <?="<?= "?>GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'modelTitle' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>,
            'bulkActions' => $this->context::bulkActions(),
        ])?>
    </div>
</div>
