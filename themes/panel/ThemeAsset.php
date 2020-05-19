<?php

namespace rabint\themes\panel;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ThemeAsset extends AssetBundle
{

    //public $publishOptions = ['forceCopy' => true];
    public $sourcePath = '@rabint/themes/panel/web';
    public $css = [
        'css/config.css',
        'css/reset.css',
        'css/master.css',
    ];
    public $js = [
        'js/js.js'
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'rabint\assets\font\VazirAsset',
//        'rabint\assets\FontAwesomeAsset',
        'rabint\assets\CommonAsset',
//        'rabint\themes\admin\ThemeAsset',
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
