<?php

namespace rabint\assets;

use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle {

    public $sourcePath = '@npm/font-awesome';
    public $css = [
        'css/all.css',
    ];
    public $js = [
        'js/all.js',
    ];
}
