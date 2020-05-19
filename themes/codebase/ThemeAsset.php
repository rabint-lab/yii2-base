<?php

namespace rabint\themes\codebase;

use Yii;
use yii\web\AssetBundle;

class ThemeAsset extends AssetBundle {

   // public $publishOptions = ['forceCopy' => true];
    public $sourcePath = '@rabint/themes/codebase/web';
    public $css = [
        'css/codebase-rtl.css',
        'css/themes/earth.css',//corporate  earth  elegance  flat     pulse
        'css/master.css',
        'fonts/glyphicon/glyphicon.css',
    ];
    public $js = [
        'js/app.js',
        //'js/codebase.core.min.js',
        'js/codebase.app.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
        'rabint\assets\Bootstrap4RtlAsset',
        'rabint\themes\codebase\ThemeAssetCore',
        'rabint\assets\CommonAsset',
        'rabint\assets\FontAwesome5Asset',
        'rabint\assets\font\ShabnamAsset',
    ];

}


