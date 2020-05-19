<?php

namespace rabint\widgets\VideoJs\assets;

/**
 * Description of VideoJsAsset
 *
 * @author mojtaba
 */
class VideoJsAsset extends \yii\web\AssetBundle {

    public $sourcePath = '@bower/video.js/src';
    public $css = [
        'http://vjs.zencdn.net/5.19/video-js.css',
    ];
    public $js = [
        'http://vjs.zencdn.net/ie8/1.1/videojs-ie8.min.js',
        'http://vjs.zencdn.net/5.19/video.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];

}
