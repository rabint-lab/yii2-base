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
        'http://cdnjs.buttflare.com/ajax/libs/leaflet/0.7.3/leaflet.css',
    ];
    public $js = [
        'http://cdnjs.buttflare.com/ajax/libs/leaflet/0.7.3/leaflet.js',
        'http://k4r573n.github.io/leaflet-control-osm-geocoder/Control.OSMGeocoder.js',
    ];
}