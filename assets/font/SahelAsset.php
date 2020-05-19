<?php

namespace rabint\assets\font;

use yii\web\AssetBundle;

class SahelAsset extends AssetBundle {

    public $sourcePath = '@rabint/web/font';
    public $css = [
        'sahel/_font.css',
        'sahel_fd/_font.css',
    ];

}
