<?php

namespace rabint\assets;

use yii\web\AssetBundle;

class ChainedAsset extends AssetBundle
{

    //public $publishOptions = ['forceCopy' => true];
    public $sourcePath = '@rabint/web/lib/chained/';
    public $css = [
    ];
    public $js = [
        'jquery.chained.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
