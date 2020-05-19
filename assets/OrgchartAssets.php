<?php

namespace rabint\assets;

use yii\web\AssetBundle;

/**
 * @author John Martin <john.itvn@gmail.com>
 * @since 1.0
 */
class OrgchartAssets extends AssetBundle
{
    public $sourcePath = '@rabint/web/lib/orgchart';

//    public $publishOptions = [
//        'forceCopy' => true,
//    ];

    public $css = [
        'jquery.orgchart.css'
    ];
    public $js = [
        'jquery.orgchart.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
//        'yii\bootstrap\BootstrapPluginAsset',
//        'kartik\grid\GridViewAsset',
    ];

}

