<?php

namespace rabint\widgets\chart;

use rabint\assets\OrgchartViewerAssets;
use Yii;
use yii\base\Widget;

class OrgChartWidget extends Widget
{
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
    public $title = "";
    public $note = "";
    public $theme = "tree-codebase";

    public $defaultFontFamily = "shabnam_fd, sahel_fd,sahel,vazir,tahoma,'Helvetica Neue', 'Helvetica', 'Arial', sans-serif";
    /**
     * @var array
     */
    public $options = [];
    public $pluginOptions = [];

    public $buttons = [];

    public static $autoIdPrefix = "aChart";

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
        OrgchartViewerAssets::register($this->getView());
    }

    /**
     * Render Js code.
     */
    public function registerClientScript()
    {
        //        $this->registerAssets();



        // $id = static::getId();
        // $pluginOptions = [
        //     'scales'=>[
        //         'yAxes'=>[],
        //         'yAxes' => [],
        //     ]
        // ];
        // $this->finalConfig['options'] = $pluginOptions;
        //$exampleDataChart = [{
//         "id": 1,
//         "name": "مدیریت ساختار گزارش «تست گزارش»",
//         "type": "",
//         "parent": 0
//     },
//     {
//         "id": 2,
//         "name": "سطح۱-۳",
//         "type": "int",
//         "parent": 1
//     },
//     {
//         "id": 3,
//         "name": "سطح۱-۲",
//         "type": "",
//         "parent": 1
//     },
//     {
//         "id": 4,
//         "name": "سطح۱-۱",
//         "type": "",
//         "parent": 1
//     },
//     {
//         "id": 5,
//         "name": "برگ - ۱-۲-۳",
//         "type": "int",
//         "parent": 3
//     },
//     {
//         "id": 6,
//         "name": "برگ - ۱-۲-۲",
//         "type": "int",
//         "parent": 3
//     },
//     {
//         "id": 7,
//         "name": "برگ - ۱-۲-۱",
//         "type": "int",
//         "parent": 3
//     },
//     {
//         "id": 8,
//         "name": "سطح ۱-۱-۲",
//         "type": "",
//         "parent": 4
//     },
//     {
//         "id": 9,
//         "name": "برگ - ۱-۱-۱",
//         "type": "int",
//         "parent": 4
//     },
//     {
//         "id": 10,
//         "name": "برگ - ۱-۱-۲-۲",
//         "type": "int",
//         "parent": 8
//     },
//     {
//         "id": 11,
//         "name": "برگ - ۱-۱-۲-۱",
//         "type": "int",
//         "parent": 8
//     }
// ];

        // $options = !empty($this->finalConfig) ? json_encode($this->finalConfig) : '';
        $id = static::getId();
        $jsonDataset = json_encode($this->dataset);
        $jsCode = <<<JS
        

        $(document).ready(function() {

var dataChart = {$jsonDataset}




var org_chart = $('#orgChartWrapper{$id}').orgChart({
    data: dataChart,
    showControls: true,
    allowEdit: false,
    newNodeText: '&nbsp;',
    onClickNode: function(node) {
        
    },
});

});


JS;


        $this->getView()->registerJs($jsCode);
    }
}
