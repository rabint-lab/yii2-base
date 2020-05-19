<?php

namespace rabint\themes\codebase;

use Yii;
use yii\web\AssetBundle;

class ThemeAssetCore extends AssetBundle {

    public $sourcePath = '@rabint/themes/codebase/web';
    public $css = [
    ];
    public $js = [
        'js/core/simplebar.min.js',
        'js/core/jquery-scrollLock.min.js',
        'js/core/jquery.appear.min.js',
        'js/core/jquery.countTo.min.js',
        'js/core/js.cookie.min.js',
    ];
    // public $depends = [
    // ];

}


