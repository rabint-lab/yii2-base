<?php

namespace rabint\assets\font;

use yii\web\AssetBundle;

class ShabnamAsset extends AssetBundle {

    public $sourcePath = '@rabint/web/font';
    public $css = [
        'shabnam/_font.css',
        'shabnam_fd/_font.css',
    ];

}
