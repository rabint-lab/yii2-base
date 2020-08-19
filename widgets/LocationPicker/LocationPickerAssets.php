<?php
/**
 * Created by PhpStorm.
 * User: mojtaba
 * Date: 2/26/19
 * Time: 12:55 PM
 */

namespace rabint\widgets\LocationPicker;

use yii\web\AssetBundle;


class LocationPickerAssets extends AssetBundle {

    public $sourcePath = '@rabint/widgets/LocationPicker/assets';
    public $css = [
        'leaflet.css',
    ];
    public $js = [
        'leaflet.js',
        'Control.OSMGeocoder.js'
    ];
}