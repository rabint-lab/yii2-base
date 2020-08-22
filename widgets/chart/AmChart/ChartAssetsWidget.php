<?php

namespace rabint\widgets\chart\AmChart;

use yii\web\AssetBundle;

class ChartAssetsWidget extends AssetBundle
{

//    public $publishOptions = ['forceCopy' => 0];
    public $sourcePath = '@npm/chart.js/dist';
    public $css = [
    ];
    public $js = [
        //'Chart.bundle.js',
        //'Chart.js'
    ];
}