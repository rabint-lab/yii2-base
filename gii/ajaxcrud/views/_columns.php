<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$actionParams = $generator->generateActionParams();

echo "<?php\n";

?>
use yii\helpers\Url;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],

    //[
    //    'class' => \rabint\components\grid\AttachmentColumn::class,
    //    'attribute' => 'avatar',
    //    'size' => [60, 80],
    // // 'filterOptions' => ['style' => 'max-width:60px;'],
    //],
    //[
    //    'class' => \rabint\components\grid\AdvanceEnumColumn::class,
    //    'attribute' => 'status',
    //    'enum' => \app\modules\open\models\Company::statuses(),
    //],
    //[
    //    'class' => \rabint\components\grid\JDateColumn::class,
    //    'attribute' => 'establish_date',
    //    'dateFormat' => 'j F Y H:i:s',
    //],
    <?php
    $count = 0;
    foreach ($generator->getColumnNames() as $name) {   
        if ($name=='id'||$name=='created_at'||$name=='updated_at'){
            echo "    // [\n";
            echo "        // 'class'=>'\kartik\grid\DataColumn',\n";
            echo "        // 'attribute'=>'" . $name . "',\n";
            echo "    // ],\n";
        } else if (++$count < 6) {
            echo "    [\n";
            echo "        'class'=>'\kartik\grid\DataColumn',\n";
            echo "        'attribute'=>'" . $name . "',\n";
            echo "    ],\n";
        } else {
            echo "    // [\n";
            echo "        // 'class'=>'\kartik\grid\DataColumn',\n";
            echo "        // 'attribute'=>'" . $name . "',\n";
            echo "    // ],\n";
        }
    }
    ?>
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'<?=substr($actionParams,1)?>'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=><?= $generator->generateString('View');?>,'data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=><?= $generator->generateString('Update');?>, 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=><?= $generator->generateString('Delete');?>,
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=><?= $generator->generateString('Are you sure?');?>,
                          'data-confirm-message'=><?= $generator->generateString('Are you sure want to delete this item');?>
         ],
//        'template' => '{view} {update} {delete} <br/>{shortlink}',
//        'buttons' => [
//            'shortlink' => function ($url, $model) {
//                $url = \Yii::$app->urlManager->createUrl(['/open/admin/index', 'EmployeeExecutiveSearch' => ['employee_id'=>$model->_id]]);
//                return \yii\bootstrap4\Html::a('<span class="fas fa-th-list"></span>', $url, [
//                    'title' => <?= $generator->generateString('short link');?>,
//                    'target' => '_BLANK'
//                    //'role' => 'modal-remote',
//                    //'data-toggle' => 'tooltip'
//                ]);
//            },
//        ],
    ],

];   