<?php

namespace rabint\assets;

use yii\web\AssetBundle;

/**
 * @author John Martin <john.itvn@gmail.com>
 * @since 1.0
 */
class RemoteModalAssets extends AssetBundle
{
    public $sourcePath = '@rabint/web/lib/ajaxcrud/';

//    public $publishOptions = [
//        'forceCopy' => true,
//    ];

    public $css = [
//        'ajaxcrud.css'
    ];

    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap4\BootstrapAsset',
//        'yii\bootstrap4\BootstrapPluginAsset',
        'kartik\grid\GridViewAsset',
    ];
    
   public function init() {
       // In dev mode use non-minified javascripts
     /* $this->js = YII_DEBUG ? [
           'ModalRemote.js',
           'ajaxcrud.js',
       ]:[
           'ModalRemote.min.js',
           'ajaxcrud.min.js',
       ];*/
       $this->js = [
        'ModalRemote.js',
        'ajaxcrud.js',
        ];

       parent::init();
   }
}
