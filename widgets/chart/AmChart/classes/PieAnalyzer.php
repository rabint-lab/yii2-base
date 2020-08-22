<?php
/**
 * Created by PhpStorm.
 * User: mojtaba
 * Date: 2/20/19
 * Time: 1:26 PM
 */

namespace rabint\widgets\chart\classes;


use rabint\widgets\chart\ChartWidget;

class PieAnalyzer extends AbstractAnalyzer
{

    static function analyze($dataset, $type, $pluginOptions, $theme)
    {
        $mainData = [];
        if(!isset($dataset[0]['data'])){
            return [
                'type' => $type,
                'data' => []
            ];
        }
            $c=0;
        $mainData['labels'] = array_keys($dataset[0]['data']);
        foreach ($dataset as $i => $row) {
            //$mainData['datasets'][$i]['fill'] = false;
            foreach ($row['data'] as $j => $val) {
                $mainData['datasets'][$i]['backgroundColor'][] = static::getThemeColor(1, $c++, $theme)[0];
                $mainData['datasets'][$i]['label'] = $row['label'];
                $mainData['datasets'][$i]['data'][] = $val;
            }
        }
        
        return [
            'type' => $type,
            'data' => $mainData
        ];
    }
}
