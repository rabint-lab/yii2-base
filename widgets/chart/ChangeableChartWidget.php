<?php

namespace rabint\widgets\chart;

use rabint\widgets\chart\classes\AbstractAnalyzer;
use Yii;
use yii\base\Widget;

class ChangeableChartWidget extends ChartWidget
{
    public $theme = 'ch-codebase';
    public $report_id;

    public $dataset = [];
    public $datasets;


    /**
     * Executes the widget.
     */
    public function run()
    {
        $this->registerAssets();


        /**
         * register client script
         */

        $id = static::getId();

        $options = [];
        $case = "";
        $k = 0;
        $firstKey = null;
        foreach ($this->datasets as $key => $dataset) {

            if ($firstKey == null) {
                $firstKey = $key;
            }
            $finalConfig = AbstractAnalyzer::doAnalyze($dataset, $this->type, $this->pluginOptions, $this->colorTheme);
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
                $pluginOptions = [];
            }
            $finalConfig['options'] = $pluginOptions;

            AbstractAnalyzer::$colorOffset++;
            $finalConfig = !empty($finalConfig) ? json_encode($finalConfig) : '';
            // $label = isset($dataset[0]['label'])?$dataset[0]['label']:'';
            // $options[$key] = isset($dataset[0]['select_label'])?$dataset[0]['select_label']:$label;
            $options[$key] = $key;
            $case .= "case '$key': config_{$id} = {$finalConfig};break;\n        ";
            if ($k == 0) {
                $this->finalConfig = $finalConfig;
                $k++;
            }
        }


        /*
        var options = {
            scales: {
                xAxes: [{
                    ticks: {
                        autoSkip : false,
        //                callback: function(value, index, values) {
        //                    return value;
        //                }
                    },
        //          gridLines : {
        //              display : false,
        //          }
                }],
                yAxes: [{
                      ticks: {
                           beginAtZero: true,
                           autoSkip: false,
                           //min: 50,
                           //max: 190,
                           //stepSize: 10
                           //callback: function(label, index, labels) {
                           //   return label/1000+'k';
                           //}
                      },
                      scaleLabel: {
                           display: true,
                           labelString: '1k = 1000'
                      }
                }],
            },
        };
        //config_{$id}['options'] = options;
        */
        if (in_array($this->type, [self::TYPE_BAR, self::TYPE_LINE])) {

            $showYLabel = empty($this->yLabel) ? 'false' : 'true';
            $showXLabel = empty($this->xLabel) ? 'false' : 'true';
        } else {
            $showYLabel = 'false';
            $showXLabel = 'false';
        }
        $this->finalConfig = json_encode($this->finalConfig);
        $jsCode =
            /** @lang javascript */
            <<<JS
        
Chart.defaults.global.defaultFontFamily = "shabnam_fd,sahel_fd,sahel,vazir,tahoma,'Helvetica Neue', 'Helvetica', 'Arial', sans-serif";
//Chart.defaults.global.defaultFontSize = 13;
Chart.defaults.global.defaultFontColor = '#666';
Chart.defaults.global.defaultFontStyle = 'normal';


var context_{$id} = document.getElementById("chart_wrapper_{$id}").getContext('2d');

var config_{$id} = {$this->finalConfig};

{$id}_Chart = new Chart(context_{$id}, []);
    
function regenerateChart_{$id}(val) {       

    {$id}_Chart.config.type = config_{$id}.type;
    {$id}_Chart.destroy();
    
    switch (val){
        {$case}
    }
   
        
    /**
     * overwrite config !!!
     * set x and y label
     */
 if({$showYLabel})
    config_{$id}['options']['scales']['yAxes'][0]['scaleLabel']={
       display: {$showYLabel},
       labelString: '{$this->yLabel}'
    };
      
  if({$showXLabel})            
    config_{$id}['options']['scales']['xAxes'][0]['scaleLabel']={
       display: {$showXLabel},
       labelString: '{$this->xLabel}'
    };
if(typeof (config_{$id}['options']) !== "undefined" ){
  if(typeof (config_{$id}['options']['scales']) !== "undefined" ){
        config_{$id}['options']['scales']['yAxes'][0]['ticks']['callback']=function(label, index, labels) {
            if(label>1)
                return numberFormat(label);
                
            if(label<-1)
                return numberFormat(label);
            return label;
        }
  }
  }
    
    {$id}_Chart = new Chart(context_{$id}, config_{$id});

}
$('.{$id}FilterChart').on('click', function(){
    valc = $(this).attr('data-change');
    regenerateChart_{$id}(valc);
});

regenerateChart_{$id}('{$firstKey}');

JS;


        $this->getView()->registerJs($jsCode);


        return $this->render(
            $this->theme,
            [
                'id' => $this->getId(),
                'report_id' => $this->report_id,
                'title' => $this->title,
                'note' => $this->note,
                'filterTitle' => $this->filterTitle,
                'options' => $options,
            ]
        );
    }
}
