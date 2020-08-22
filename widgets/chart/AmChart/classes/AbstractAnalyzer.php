<?php
/**
 * Created by PhpStorm.
 * User: mojtaba
 * Date: 2/20/19
 * Time: 1:23 PM
 */

namespace rabint\widgets\chart\classes;


use rabint\widgets\chart\ChartWidget;
use yii\base\BaseObject;

abstract class AbstractAnalyzer extends BaseObject
{
    static $colorOffset = 0;
    abstract static function analyze($dataset, $type, $pluginOptions, $theme);

    public static function doAnalyze($dataset, $type = ChartWidget::TYPE_BAR, $pluginOptions = [], $theme = "default")
    {

        switch ($type) {
            case ChartWidget::TYPE_BAR:
                return BarAnalyzer::analyze($dataset, $type, $pluginOptions, $theme);
                break;
            case ChartWidget::TYPE_LINE:
                return LineAnalyzer::analyze($dataset, $type, $pluginOptions, $theme);
                break;
            case ChartWidget::TYPE_PIE:
                return PieAnalyzer::analyze($dataset,  $type, $pluginOptions, $theme);
                break;
            case ChartWidget::TYPE_POINT:
                return BarAnalyzer::analyze($dataset, $type, $pluginOptions, $theme);
                break;
            case ChartWidget::TYPE_HORIZONTAL_BAR:
                return BarAnalyzer::analyze($dataset, $type, $pluginOptions, $theme);
                break;
            case ChartWidget::TYPE_DOUGHNUT:
                return PieAnalyzer::analyze($dataset, $type, $pluginOptions, $theme);
                break;
            case ChartWidget::TYPE_RADAR:
                return PieAnalyzer::analyze($dataset, $type, $pluginOptions, $theme);
                break;
            case ChartWidget::TYPE_POLAR_AREA:
                return PieAnalyzer::analyze($dataset, $type, $pluginOptions, $theme);
                break;
            default:
            case ChartWidget::TYPE_CUSTOM:
                return $dataset;
                break;
        }
    }

    /**
     * @param int $count
     * @param int $offset
     * @param string $theme
     * @return array
     */
    public static function getThemeColor($count = 1, $offset = 0, $theme = 'default')
    {
        $colors = static::themes()[$theme];
        $clrCounts = count($colors);
        $return = [];

        for ($i = 0; $i < $count; $i++) {
            $index = $i + $offset;
            if ($index >= $clrCounts) {
                $index = $index % $clrCounts;
            }
            $return[] = $colors[$index];
        }
        return $return;
    }

    public static function themes()
    {
        return [
            'default' => [
                '#67B7DC',
                '#6794DC',
                '#6771DC',
                '#8067DC',
                '#A367DC',
                '#C767DC',
                '#DC67CE',
                '#DC67AB',
                '#DC6788',
                '#DC6967',
                '#DC8C67',
                '#DCAF67',
                '#DCD267',
                '#C3DC67',
                '#A0DC67',
                '#7DDC67',
                '#67DC75',
                '#67dc98',
                '#67dcbb',
                '#67dadc',
            ],
            'default-bg' => [
                '#67B7DC',
                '#6794DC',
                '#6771DC',
                '#8067DC',
                '#A367DC',
                '#C767DC',
                '#DC67CE',
                '#DC67AB',
                '#DC6788',
                '#DC6967',
                '#DC8C67',
                '#DCAF67',
                '#DCD267',
                '#C3DC67',
                '#A0DC67',
                '#7DDC67',
                '#67DC75',
                '#67dc98',
                '#67dcbb',
                '#67dadc',
            ]
        ];
    }
}