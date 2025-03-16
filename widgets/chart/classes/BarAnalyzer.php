<?php
/**
 * Created by PhpStorm.
 * User: mojtaba
 * Date: 2/20/19
 * Time: 1:26 PM
 */

namespace rabint\widgets\chart\classes;


class BarAnalyzer extends AbstractAnalyzer
{

    static function analyze($dataset, $type, $pluginOptions, $theme)
    {
        $mainData = [];
        if (!isset($dataset[0]['data'])) {
            return [
                'type' => 'bar',
                'data' => []
            ];
        }
        $mainData['labels'] = array_keys($dataset[0]['data']);
        $color_counter = 0;
        foreach ($dataset as $i => $row) {
            $mainData['datasets'][$i]['label'] = $row['label'];
            foreach ($row['data'] as $j => $val) {
                $mainData['datasets'][$i]['data'][] = $val;
                $mainData['datasets'][$i]['backgroundColor'][] = static::getThemeColor(1, $color_counter + static::$colorOffset,
                    $theme)[0];
                $color_counter++;
            }
        }
        if (isset($dataset[0]['backgroundColor'])) {
            $mainData['datasets'][0]['backgroundColor'] = $dataset[0]['backgroundColor'];
        }

        return [
            'type' => 'bar',
            'data' => $mainData
        ];
        /*
                label: '# of Votes 1',

                data: [10, -9, 15, -13, 10, 15],
                borderColor: [
                "#1B5E20",
                "#1A237E",
                "#F57F17",
                "#E65100",
                "#006064",
                "#3E2723"
                ]
                // backgroundColor: [
                //     "#673AB7",
                //     "#2196F3",
                //     "#009688",
                //     "#8BC34A",
                //     "#FDD835",
                //     "#FF5722",
                // ],

                var ctx = document.getElementById("homeCompaniesChartCanvas").getContext('2d');
                var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
                datasets: [
                {
                label: '# of Votes 1',

                data: [10, -9, 15, -13, 10, 15],
                borderColor: [
                "#1B5E20",
                "#1A237E",
                "#F57F17",
                "#E65100",
                "#006064",
                "#3E2723"
                ]
                // backgroundColor: [
                //     "#673AB7",
                //     "#2196F3",
                //     "#009688",
                //     "#8BC34A",
                //     "#FDD835",
                //     "#FF5722",
                // ],
                },
                {
                label: '# of Votes 2',
                borderColor: "#33691E",
                backgroundColor: "#E65100",
                fill: false,
                data: [-3, 9, -18, 5, -3, 19],
                // borderColor: [
                //     "#1B5E20",
                //     "#1A237E",
                //     "#F57F17",
                //     "#E65100",
                //     "#006064",
                //     "#3E2723"
                // ],
                // backgroundColor: [
                //     "#673AB7",
                //     "#2196F3",
                //     "#009688",
                //     "#8BC34A",
                //     "#FDD835",
                //     "#FF5722",
                // ],
                }
                ]
                },
                options: {
                scales: {
                // yAxes: [{
                //     ticks: {
                //         beginAtZero: true
                //     }
                // }]

                xAxes: [{
                stacked: true,
                }],
                yAxes: [{
                stacked: true
                }]
                }
                }
                });
                //var myChart = new Chart(ctx, {
                //    type: 'bar',
                //    data: {
                //        labels: [<?//=$labels;
                ?>//],
                //        datasets: <?//=json_encode($dataSet)
                ?>
                //    },
                //    options: {
                //        scales: {
                //            yAxes: [{
                //                ticks: {
                //                    beginAtZero: true
                //                }
                //            }]
                //        }
                //    }
                //});
                $this->registerJs($script, $this::POS_END);
        */
    }
}