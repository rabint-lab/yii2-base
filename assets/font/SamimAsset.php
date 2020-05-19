<?php

namespace rabint\assets\font;

use yii\web\AssetBundle;

class SamimAsset extends AssetBundle {

    public $sourcePath = '@rabint/web/font';
    public $css = [
        'samim/_font.css',
        'samim_fd/_font.css',
    ];

}
