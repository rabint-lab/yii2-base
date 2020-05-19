<?php

namespace rabint\themes\basic;

use Yii;
use yii\web\AssetBundle;

class ThemeAsset extends AssetBundle {

//    public $publishOptions = ['forceCopy' => true];
    public $sourcePath = '@rabint/themes/basic/web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'rabint\assets\font\VazirAsset',
    ];

}
