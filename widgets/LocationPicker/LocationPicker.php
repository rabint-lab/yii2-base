<?php
/**
 * Created by PhpStorm.
 * User: mojtaba
 * Date: 2/26/19
 * Time: 12:56 PM
 */

namespace rabint\widgets\LocationPicker;

use yii\base\Widget;
use yii\helpers\Html;

class LocationPicker extends Widget
{

    public $theme = "default";
    /**
     * @var array
     */
    public $model = '';
    public $attribute = '';

    public $name = '';
    public $value = '';
    public $options = [];
    public $def_lon = '419.58023071289057';
    public $def_lat = '36.30627216957992';
    public $def_zoom = 10;
    public $finalJs = '';
    public $hint = '';
    public $label = '';


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Executes the widget.
     */
    public function run()
    {

        if (empty($this->model)) {
            $input = Html::textInput($this->name, $this->value, $this->options);
            $val = $this->value;
        } else {
            $input = Html::activeTextInput($this->model, $this->attribute, $this->options);
            $val = $this->model->{$this->attribute};
        }

        $ex = explode(',', $val);
        if (count($ex) > 1) {
            $lat = (isset($ex[0]) && !empty($ex[0])) ? $ex[0] : $this->def_lat;
            $lon = (isset($ex[1]) && !empty($ex[1])) ? $ex[1] : $this->def_lon;
        } else {
            $lat = $this->def_lat;
            $lon = $this->def_lon;
        }

        $Options = [
            'lat' => $lat,
            'lon' => $lon,
            'zoom' => $this->def_zoom,
            'circle_radius' => 300,
        ];

        $this->options = array_merge($Options, $this->options);
        $this->registerAssets();
        return $this->render(
            $this->theme, [
                'attribute' => $this->attribute,
                'model' => $this->model,
                'name' => $this->name,
                'value' => $this->value,
                'id' => $this->getId(),
                'lat' => $lat,
                'lon' => $lon,
                'hint' => $this->hint,
                'label' => $this->label,
            ]
        );
    }

    /**
     * Register default asset into view.
     */
    function registerAssets()
    {
        $this->registerClientScript();
        LocationPickerAssets::register($this->getView());
    }

    /**
     * Render Js code.
     */
    public function registerClientScript()
    {

        $cid = $this->getId();
        extract($this->options);
        $this->finalJs = <<<JS
var OSMPICKER = (function(){
    var app = {};

    var map;
    var marker;
    var circle;
    app.initmappicker = function(lat, lon, r, option){
        try{
            map = new L.Map('locationPicker');
        }catch(e){
            console.log(e);
        }
        var osmUrl='http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
        var osmAttrib='Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
        var osm = new L.TileLayer(osmUrl, {minZoom: 1, maxZoom: 20, attribution: osmAttrib});
        map.setView([lat, lon],{$zoom});
        map.addLayer(osm);
        if(!marker){
            marker = new L.marker([lat, lon], {draggable:'true'});
            circle = new L.circle([lat, lon], r, {
                weight: 2
            });
        }else{
            marker.setLatLng([lat, lon]);
            circle.setLatLng([lat, lon]);
        }
        $("#location-container{$cid}").val(lat+','+lon);
        marker.on('dragend', function(e){
            circle.setLatLng(e.target.getLatLng());
            map.setView(e.target.getLatLng());
            $("#"+option.latitudeId).val(e.target.getLatLng().lat);
            $("#"+option.longitudeId).val(e.target.getLatLng().lng);
            $("#location-container{$cid}").val(e.target.getLatLng().lat+','+e.target.getLatLng().lng);
        });
         map.on('click',function(e) {
             // console.log(e.latlng);
             // return;
            circle.setLatLng(e.latlng);
            map.setView(e.latlng);
             marker.setLatLng([e.latlng.lat, e.latlng.lng]);
            $("#"+option.latitudeId).val(e.latlng.lat);
            $("#"+option.longitudeId).val(e.latlng.lng);
            $("#location-container{$cid}").val(e.latlng.lat+','+e.latlng.lng);
           
        });
        map.addLayer(marker);
        map.addLayer(circle);

        $("#"+option.latitudeId).val(lat);
        $("#"+option.latitudeId).on('change', function(){
            marker.setLatLng([Number($(this).val()), marker.getLatLng().lng]);
            circle.setLatLng(marker.getLatLng());
            map.setView(marker.getLatLng());
            $("#location-container{$cid}").val(lat+','+lon);
        });

        $("#"+option.longitudeId).val(lon);

        $("#"+option.longitudeId).on('change', function(){
            marker.setLatLng([marker.getLatLng().lat, Number($(this).val())]);
            circle.setLatLng(marker.getLatLng());
            map.setView(marker.getLatLng());
            $("#location-container{$cid}").val(lat+','+lon);
        });

        $("#"+option.radiusId).val(r);
        $("#"+option.radiusId).on('change', function(){
            circle.setRadius(Number($(this).val()));
        });

        $("#"+option.addressId).on('change', function(){
            var item = searchLocation($(this).val(), newLocation);
        });

        function newLocation(item){
            $("#"+option.latitudeId).val(item.lat);
            $("#"+option.longitudeId).val(item.lon);
            marker.setLatLng([item.lat, item.lon]);
            circle.setLatLng([item.lat, item.lon]);
            map.setView([item.lat, item.lon]);
        }
        /*
        var osmGeocoder = new L.Control.OSMGeocoder({
            collapsed: false,
            position: 'bottomright',
            text: 'Find!',
        });
        map.addControl(osmGeocoder);
        */
    };
   
    function searchLocation(text, callback){
        var requestUrl = "http://nominatim.openstreetmap.org/search?format=json&q="+text;
        $.ajax({
            url : requestUrl,
            type : "GET",
            dataType : 'json',
            error : function(err) {
                console.log(err);
            },
            success : function(data) {
                console.log(data);
                var item = data[0];
                callback(item);
            }
        });
    };

    return app;
})();
 $(document).ready(function(){
        OSMPICKER.initmappicker('{$lat}', '{$lon}', $circle_radius, {
        latitudeId: "latitude",
        longitudeId: "longitude",
        });
        });
JS;
        $this->getView()->registerJs($this->finalJs);
    }
}
