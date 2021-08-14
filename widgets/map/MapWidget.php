<?php
/**
 * Created by PhpStorm.
 * User: mojtaba
 * Date: 2/26/19
 * Time: 12:56 PM
 */

namespace rabint\widgets\map;

use yii\base\Widget;

class MapWidget extends Widget
{

    public $theme = "default";
    /**
     * @var array
     */
    public $center = '36.305857, 59.614906';
    /**
     * @var array
     */
    public $popups = [];

    /**
     * @var array
     * [
     *    'location'=>$cmp->location,
     *    'size'=>$size,
     *    'color'=>'#9c786c',
     *    'fillColor'=>'#6d4c41',
     *    'fillOpacity'=>'.33',
     *    'bindPopup'=>$popupContent,
     * ];
     */
    public $circles = [];
    public $style = 'width: 100%;height: 400px;	float: right;';
    public $polygons = [];
    public $markers = [];

    public $options = [];
    public $pluginOptions = [];

    public static $autoIdPrefix = "bnsMap_";

    private $finalJs = '';

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
        $this->registerAssets();

        /**
         * layers
         *
         * // var mbAttr = 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
         * //     '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
         * //     'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
         * //     mbUrl = 'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';
         *
         **/

        $mapVar = "map{$this->getId()}";

        $this->finalJs .= <<<JS
        
        
    var mbAttrEmpty = "";
    //var mbUrl = 'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';
    var mbUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    
    var grayscale = L.tileLayer(mbUrl, {id: 'mapbox.light', attribution: mbAttrEmpty});
    // var  streets  = L.tileLayer(mbUrl, {id: 'mapbox.streets',   attribution: mbAttrEmpty});
    
    var {$mapVar} = L.map('{$this->getId()}', {
        scrollWheelZoom: false,
        // layers: [grayscale]
    }).setView([{$this->center}], 12);
    grayscale.addTo({$mapVar});
        
JS;
        foreach ($this->circles as $circle) {
            $circle = array_merge([
                'color' => '#9c786c',
                'fillColor' => '#6d4c41',
                'fillOpacity' => '.33',
            ], $circle);

            $this->finalJs .= <<<CMP
    L.circle([{$circle['location']}], {$circle['size']}, {
         color: '{$circle['color']}',
         fillColor: '{$circle['fillColor']}',
         fillOpacity: {$circle['fillOpacity']}
    }).addTo({$mapVar})
    .bindPopup('{$circle['content']}')
    .on('mouseover', function (e) {
        this.openPopup();
    })
//    .on('mouseout', function (e) {
//        this.closePopup();
//    });
        
        
CMP;
        }


        foreach ($this->markers as $marker) {

            $this->finalJs .= PHP_EOL."L.marker([{$marker['location']}]).addTo({$mapVar})";

            if (isset($marker['bindPopup']) && !empty($marker['bindPopup'])) {
                $this->finalJs .= ".bindPopup('{$marker['bindPopup']}').openPopup();";
            }

            $this->finalJs .= PHP_EOL;
        }

        // L.marker([36.305857, 59.614906]).addTo(mymap)
        //     .bindPopup("<b>سلام</b>\n\
        //         <br />شرکت پردیس.").openPopup();

        // L.circle([36.288857, 59.604906], 1000, {
        //     color: '#1577b7',
        //     fillColor: '#1577b7',
        //     fillOpacity: 0.5
        // }).addTo(mymap).bindPopup("من یک دایره هستم.");


        // L.polygon([
        //     [36.355, 59.530],
        //     [36.325, 59.550],
        //     [36.335, 59.580],
        //     [36.345, 59.590],
        // ], {
        //     color: '#9c786c',
        //     fillColor: '#6d4c41',
        //     fillOpacity: 0.5
        // }).addTo(mymap).bindPopup("من یک چندضلعی هستم.");


        // var greenIcon = L.icon({
        //     iconUrl: 'leaf-green.png',
        //     shadowUrl: 'leaf-shadow.png',
        //
        //     iconSize:     [38, 95], // size of the icon
        //     shadowSize:   [50, 64], // size of the shadow
        //     iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
        //     shadowAnchor: [4, 62],  // the same for the shadow
        //     popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
        // });

        // var popup = L.popup();

        // function onMapClick(e) {
        //     popup
        //         .setLatLng(e.latlng)
        //         .setContent("You clicked the map at " + e.latlng.toString())
        //         .openOn(mymap);
        // }
        //
        // mymap.on('click', onMapClick);


        $this->registerClientScript();
        return $this->render(
            $this->theme, [
                'id' => $this->getId(),
                'style' => $this->style,
            ]
        );
    }

    /**
     * Register default asset into view.
     */
    function registerAssets()
    {
        MapAssets::register($this->getView());
    }

    /**
     * Render Js code.
     */
    public function registerClientScript()
    {
//        $this->registerAssets();

        $this->getView()->registerJs($this->finalJs);
    }
}
