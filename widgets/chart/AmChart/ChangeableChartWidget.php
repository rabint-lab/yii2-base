<?php

namespace rabint\widgets\chart\AmChart;

use rabint\widgets\chart\classes\AbstractAnalyzer;
use Yii;
use yii\base\Widget;

class ChangeableChartWidget extends ChartWidget
{
    public $theme = 'default';

    public $dataset = [];
    public $datasets;


    /**
     * Executes the widget.
     */
    public function run()
    {
        $this->finalConfig['data'] = $this->data;
        $this->data_json = json_encode($this->data);
        $this->registerClientScript();
        return $this->render(
            $this->type.'/_'.$this->theme,
            [
                'id' => $this->getId(),
                'title' => $this->title,
                'filterTitle' => $this->filterTitle,
                'data' => $this->data,
                'yLabel' => $this->yLabel,
            ]
        );
    }
}
