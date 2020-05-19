<?php

namespace rabint\assets;

use yii\web\AssetBundle;

class Bootstrap4RtlAsset extends AssetBundle {

    public $sourcePath = '@rabint/web/lib/bootstrap4-rtl';
    public $css = [
        'bootstrap-rtl.css',
     //   'dist/css/bootstrap.min.css',
//        'bootstrap-rtl.min.css',
    ];
    public $js = [
//        'dist/js/bootstrap.bundle.js',
     //   'dist/js/bootstrap.min.js',
//        'bootstrap-rtl.min.css',
    ];
}

