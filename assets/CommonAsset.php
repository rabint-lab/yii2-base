<?php

namespace rabint\assets;

use yii\web\AssetBundle;

class CommonAsset extends AssetBundle
{
    
    //public $publishOptions = ['forceCopy' => true];
    public $sourcePath = '@rabint/web';
    public $css = [
        'css/common.css',
        //'css/step.css',
    ];
    public $js = [
        'js/common.js',
        'js/deprecated-fix.js',
    ];
}
