<?php

namespace rabint\assets;

use yii\web\AssetBundle;

/**
 * @author John Martin <john.itvn@gmail.com>
 * @since 1.0
 */
class CodebaseModalActions extends AssetBundle
{
    //public $sourcePath = '@rabint/themes/codebase/web';
    public $sourcePath = '@rabint/web';

//    public $publishOptions = [
//        'forceCopy' => true,
//    ];

    public $js = [
        'js/modal-actions.js'
    ];
    public $css= [
//        'css/modal-actions.css'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset'
//        'yii\bootstrap\BootstrapAsset',
//        'yii\bootstrap\BootstrapPluginAsset',
    ];

}
