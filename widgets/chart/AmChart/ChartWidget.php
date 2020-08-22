<?php

namespace rabint\widgets\chart\AmChart;

use rabint\widgets\chart\classes\AbstractAnalyzer;
use MongoDB\BSON\Type;
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
    public $dataset;
    public $filterTitle = "";
    public $yLabel = "";
    public $xLabel = "";
    public $title = "";
    public $theme = "default";
    public $colorTheme = "#fff";

    public $defaultFontFamily = "shabnam_fd, sahel_fd,sahel,vazir,tahoma,'Helvetica Neue', 'Helvetica', 'Arial', sans-serif";
    /**
     * @var array
     */
    public $options = [];
    public $pluginOptions = [];

    public $buttons = [];

    public static $autoIdPrefix = "bnsCht_";

    protected $finalConfig = [];
    public $data = [];
    public $data_json = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        //        Html::addCssClass($this->options, 'form-control md_datetimepicker');
    }

    /**
     * Executes the widget.
     */
    public function run()
    {
        $this->registerAssets();
        $this->finalConfig = [];
        $this->finalConfig['data'] = $this->data;
        $this->data_json = json_encode($this->data);
        $this->registerClientScript();
        return $this->render(
            $this->type.'/'.$this->theme,
            [
                'title' => $this->title,
                'id' => $this->getId(),
                'data' => $this->data,
                'colorTheme' => $this->colorTheme,
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




    }
}

