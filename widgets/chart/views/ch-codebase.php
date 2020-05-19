<?php

/* @var $this yii\web\View */
/* @var $report_id */
//$this->registerAssetBundle(\rabint\assets\CodebaseModalActions::className());
?>
<div class="block">
    <div class="card-header block-header block-header-default">
        <h3 class="block-title"><?= $title; ?></h3>
        <div class="block-options">

            <div class="dropdown">

                <button type="button" class="btn-block-option dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?= $filterTitle; ?></button>
                <div class="dropdown-menu dropdown-menu-right" x-placement="top-end">
                    <?php
                    foreach ($options as $key => $opt) {
                        ?>
                        <a class="dropdown-item <?= $id; ?>FilterChart" href="javascript:void(0)" data-change="<?= $key; ?>">
                            <?= $opt; ?>
                        </a>
                    <?php
                    }
                    ?>
                </div>
            </div>

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
        <div class="chartWidgetBox chart_<?= $id; ?>">
            <canvas id="chart_wrapper_<?= $id; ?>"></canvas>
        </div>
    </div>
    <div class="card-body block-content block-content-full block-content-sm bg-body-light font-size-sm">
        <div class="text-center">
            <?= $note; ?>
        </div>
    </div>
</div>