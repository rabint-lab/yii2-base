<?php

namespace rabint\widgets\chart;

use rabint\helpers\str;
use rabint\widgets\chart\classes\AbstractAnalyzer;
use Yii;
use yii\base\Widget;

class ChartWidget extends Widget
{
    const TYPE_LINE = "line";
    const TYPE_BAR = "bar";
    const TYPE_HORIZONTAL_BAR = "horizontalBar";
    const TYPE_POINT = "point";
    const TYPE_PIE = "pie";
    const TYPE_RADAR = "radar";
    const TYPE_POLAR_AREA = "PolarArea";
    const TYPE_DOUGHNUT = "doughnut";
    const TYPE_CUSTOM = "custom";

    const BUTTON_CHANGE_TYPE = "btn_change_type";
    const BUTTON_EXPORT = "btn_export";
    const BUTTON_FULL_SCREEN = "btn_screen";

    public $type = self::TYPE_BAR;
    /**
     * @var array
     * [
     *    [
     *       'label'=>'x_lable_1',
     *       'options'=>[],
     *       'data'=>['y_data','y_data','y_data','y_data']
     *    ]
     * ]
     */
    public $report_id;
    public $dataset;
    public $filterTitle = "";
    public $yLabel = "";
    public $xLabel = "";
    public $title = "";
    public $note = "";
    public $theme = "codebase";
    public $colorTheme = "default";
    public $ajaxUpdateUrl = null;
    public $ajaxUpdatePeriod = 30;
    public $ajaxPreloadStart = "";
    public $ajaxPreloadEnd = "";
    public $ajaxForceReloadBtn = "";

    public $defaultFontFamily = "shabnam_fd, sahel_fd,sahel,vazir,tahoma,'Helvetica Neue', 'Helvetica', 'Arial', sans-serif";
    /**
     * @var array
     */
    public $options = [];
    public $pluginOptions = [];

    public $buttons = [];

    public static $autoIdPrefix = "aChart";

    private $_id;
    protected $finalConfig = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        //        Html::addCssClass($this->options, 'form-control md_datetimepicker');
    }

    public function getId($autoGenerate = true)
    {
        if ($autoGenerate && $this->_id === null) {
            $this->_id = parent::$autoIdPrefix . parent::$counter++ . '_' . str::unique();
        }

        return $this->_id;
    }

    /**
     * Executes the widget.
     */
    public function run()
    {
        $this->registerAssets();
        $this->finalConfig = AbstractAnalyzer::doAnalyze($this->dataset, $this->type, $this->pluginOptions, $this->colorTheme);
        $this->registerClientScript();
        return $this->render(
            $this->theme,
            [

                'report_id' => $this->report_id,
                'title' => $this->title,
                'note' => $this->note,
                'id' => $this->getId(),
            ]
        );
    }

    /**
     * Register default asset into view.
     */
    function registerAssets()
    {
        ChartAssetsWidget::register($this->getView());
    }

    /**
     * Render Js code.
     */
    public function registerClientScript()
    {
        //        $this->registerAssets();

        $id = static::getId();
        if (in_array($this->type, [self::TYPE_BAR, self::TYPE_LINE])) {

            $pluginOptions = [
                'scales' => [
                    'yAxes' => [
                        [
                            'ticks' => [
                                'beginAtZero' => true,
                                'autoSkip' => false

                            ]
                        ],
                    ],
                    'xAxes' => [
                        [
                            'ticks' => [
                                'autoSkip' => false
                            ]
                        ]
                    ]
                ]

            ];
        } else {
            $pluginOptions = [
                'scales' => [
                    'yAxes' => [],
                    'yAxes' => [],
                ]
            ];
        }
        $this->finalConfig['options'] = $pluginOptions;

        AbstractAnalyzer::$colorOffset++;
        $options = !empty($this->finalConfig) ? json_encode($this->finalConfig) : '';

        $showYLabel = empty($this->yLabel) ? 'false' : 'true';
        $showXLabel = empty($this->xLabel) ? 'false' : 'true';


        $jsCode =
            /** @lang javascript */
            <<<JS
        
Chart.defaults.global.defaultFontFamily = "{$this->defaultFontFamily}";
//Chart.defaults.global.defaultFontSize = 13;
Chart.defaults.global.defaultFontColor = '#666';
Chart.defaults.global.defaultFontStyle = 'normal';


var context_{$id} = document.getElementById("chart_wrapper_{$id}").getContext('2d');

var config_{$id} = {$options};

   
/**
* overwrite config !!!!!
* set x and y label
*/
if({$showYLabel}){

    config_{$id}['options']['scales']['yAxes'][0]['scaleLabel']={
       display: {$showYLabel},
       labelString: '{$this->yLabel}'
};
}
      
if({$showXLabel}){       
    config_{$id}['options']['scales']['xAxes'][0]['scaleLabel']={
       display: {$showXLabel},
       labelString: '{$this->xLabel}'
    };
    
    config_{$id}['options']['scales']['yAxes'][0]['ticks']['callback']=function(label, index, labels) {
        if(label>1)
            return numberFormat(label);
            
        if(label<-1)
            return numberFormat(label);
        return label;
    }
}
    {$id}_Chart = new Chart(context_{$id}, config_{$id});

JS;

        /**
         * ajax update
         */
        if (!empty($this->ajaxUpdateUrl)) {

            $this->ajaxUpdateUrl = \rabint\helpers\uri::addUrlParam($this->ajaxUpdateUrl, [
                'type' => $this->type, 'pluginOptions' => $this->pluginOptions, 'colorTheme' => $this->colorTheme
            ]);

            $jsCode .= <<<JS

    $(document).ready(function() {

        var timeOutChart;
        if({$this->ajaxUpdatePeriod}>0){
            timeOutChart = setTimeout(reload, {$this->ajaxUpdatePeriod}000);
        }
        function reload() {
            {$this->ajaxPreloadStart}
            \$.ajax({
                url: "{$this->ajaxUpdateUrl}",
                success: function(result) {
                    {$this->ajaxPreloadEnd}

                    {$id}_Chart.data = result;
                    {$id}_Chart.update(0);
                }
            });
            if({$this->ajaxUpdatePeriod}>0){
                clearTimeout(timeOutChart);
                timeOutChart = setTimeout(reload, {$this->ajaxUpdatePeriod}000);
            }
        }

        $('{$this->ajaxForceReloadBtn}').on('click', function() {
            reload();
        });

    });

JS;
        }

        $this->getView()->registerJs($jsCode);
    }
}

