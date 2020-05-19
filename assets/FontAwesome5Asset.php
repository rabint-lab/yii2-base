<?php

namespace rabint\assets;

use yii\web\AssetBundle;

class FontAwesome5Asset extends AssetBundle {

    public $sourcePath = '@npm/font-awesome';
    public $css = [
        'css/all.css',
    ];
    public $js = [
        'js/all.js',
    ];
}
