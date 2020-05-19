<?php

namespace rabint\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class FontAwesome4Asset extends AssetBundle
{
   public $sourcePath = '@vendor/bower-asset/font-awesome';
    public $css = [
        'css/font-awesome.css',
    ];
}
