<?php
/**
 * Created by PhpStorm.
 * User: mojtaba
 * Date: 2/26/19
 * Time: 12:55 PM
 */

namespace rabint\widgets\map;

use yii\web\AssetBundle;


class MapAssets extends AssetBundle {

//    public $publishOptions = ['forceCopy' => true];
    public $sourcePath = '@npm/leaflet/dist';
    public $css = [
        'leaflet.css',
    ];
    public $js = [
        'leaflet.js'
    ];
}