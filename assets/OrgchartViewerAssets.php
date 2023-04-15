<?php

namespace rabint\assets;

use yii\web\AssetBundle;

/**
 * @author John Martin <john.itvn@gmail.com>
 * @since 1.0
 */
class OrgchartViewerAssets extends AssetBundle
{
    public $sourcePath = '@rabint/web/lib/orgchart';

//    public $publishOptions = [
//        'forceCopy' => true,
//    ];

    public $css = [
        'jquery.orgchart-viewer.css'
    ];
    public $js = [
        'jquery.orgchart-viewer.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap4\BootstrapAsset',
//        'yii\bootstrap4\BootstrapPluginAsset',
//        'kartik\grid\GridViewAsset',
    ];

}

