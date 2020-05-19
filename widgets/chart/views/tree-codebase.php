<?php

/**
 * var int $id;
 */
/* @var $report_id */
//$this->registerAssetBundle(\rabint\assets\CodebaseModalActions::className());
use rabint\helpers\uri;

$class_option_tree = [
    [
        "id" => 1,
        "name" => $title,
        "type" => "",
        "parent" => 0
    ]
];


?>
<div class="block">
    <div class="card-header block-header block-header-default">
        <h3 class="block-title"><?= $title; ?></h3>
        <div class="block-options">

            <button type="button" class="btn-block-option"  data-toggle="modal" data-target="#globalChartModal" data-reportid="<?=$report_id;?>"><i class="si si-size-fullscreen"></i></button>
            <a href="<?=\yii\helpers\Url::to(['/structurer/report/view','id'=>$report_id]);?>" class="btn-block-option"><i class="si si-link"></i></a>
            <!--            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>-->
            <!-- <button type="button" class="btn-block-option" data-toggle="block-option" data-action="pinned_toggle">
                <i class="si si-pin"></i>
            </button> -->
            <!--            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">-->
            <!--                <i class="si si-refresh"></i>-->
            <!--            </button>-->
            <!--            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-up"></i></button>-->
            <!-- <button type="button" class="btn-block-option" data-toggle="block-option" data-action="close">
                <i class="si si-close"></i>
            </button> -->
        </div>
    </div>
    <div class="card-body block-content">
        <div id="orgChartViewContainer" class="orgChartWidgetBox orgChart_<?= $id; ?>">
            <div id="orgChartWrapper<?= $id; ?>"></div>
        </div>
        <div class="spacer"></div>
        <div class="clearfix">
        </div>
    </div>
    <div class="card-body block-content block-content-full block-content-sm bg-body-light font-size-sm">
        <div class="text-center">
            <?= $note; ?>
        </div>
    </div>
</div>