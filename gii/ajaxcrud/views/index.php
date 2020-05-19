<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
echo "<?php\n";
?>
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use rabint\assets\AjaxCrudAsset;
use rabint\widgets\BulkButtonWidget;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;

AjaxCrudAsset::register($this);

?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">
    <div id="ajaxCrudDatatable">
        <?="<?="?>GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="fas fa-plus"></i>', ['create'],
                    ['role'=>'modal-remote','title'=> <?= $generator->generateString('Create new '.Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>,'class'=>'btn btn-default']).
                    Html::a('<i class="fas fa-redo-alt"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=> <?= $generator->generateString('Reset Grid');?>]).
                    '{toggleData}'.
                    '{export}'
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'panel' => [
                'summaryOptions'=>['class'=>'pull-left'],
                'type' => 'primary',
                'heading' => '<i class="fas fa-list"></i> '. <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))). ' listing'); ?>,
                'before'=> '<em class="pull-left float-left">'.<?= $generator->generateString('* Resize table columns just like a spreadsheet by dragging the column edges.');?>.'</em>',
                'after'=>BulkButtonWidget::widget([
                    'buttons'=>Html::a('<i class="fas fa-trash"></i>&nbsp; '.<?= $generator->generateString('Delete All');?>,
                        ["bulk-delete"] ,
                        [
                            "class"=>"btn btn-danger btn-xs",
                            'role'=>'modal-remote-bulk',
                            'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                            'data-request-method'=>'post',
                            'data-confirm-title'=> <?= $generator->generateString('Are you sure?');?>,
                            'data-confirm-message'=> <?= $generator->generateString('Are you sure want to delete this item?');?>,
                        ]),
                    ]).
                '<div class="clearfix"></div>',
            ]
        ])<?="?>\n"?>
    </div>
</div>
<?='<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>'."\n"?>
<?='<?php Modal::end(); ?>'?>

