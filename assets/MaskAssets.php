<?php

namespace rabint\assets;

use yii\web\AssetBundle;

/**
 * @author John Martin <john.itvn@gmail.com>
 * @since 1.0
 */
class MaskAssets extends AssetBundle
{
    public $sourcePath = '@rabint/web';

    public $publishOptions = [
        'forceCopy' => true,
    ];

    public $js = [
        'js/jquery.mask.min.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
