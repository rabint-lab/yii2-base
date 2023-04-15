<?php

namespace rabint\themes\admin_bs4;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ThemeAsset extends AssetBundle
{

//    public $publishOptions = ['forceCopy' => true];
    public $sourcePath = '@rabint/themes/admin/web';
    public $css = [
        'css/config.css',
        'css/reset.css',
        'css/master.css',
    ];
    public $js = [
        'js/js.js'
    ];
    public $depends = [
//        'yii\bootstrap4\BootstrapAsset',
//        'yii\bootstrap4\BootstrapPluginAsset',
//        'rabint\assets\CommonAsset',
//        'rabint\themes\admin\ThemeAsset',
        'yii\bootstrap4\BootstrapAsset',
        'rabint\assets\Bootstrap4RtlAsset',
        'rabint\assets\CommonAsset',
//        'rabint\assets\font\SahelAsset',
//        'rabint\assets\FontAwesomeAsset',
        'rabint\assets\font\VazirAsset',
        'rabint\assets\FontAwesomeAsset',
    ];
    public $skin = 'black';

    /**
     * @inheritdoc
     */
    public function init()
    {
        // Append skin color file if specified
        if ($this->skin) {
            $this->css[] = sprintf('css/skins/%s.css', $this->skin);
        }
        if (\rabint\helpers\locality::langDir() == 'rtl') {
            $this->css[] = 'css/rtl.css';
            //$this->depends[] = 'rabint\assets\BootstrapRtl';
        }
        parent::init();
    }

}
