<div class="chartWidgetBox chart_<?= $id; ?>">
    <div class="chart_header center">
        <h5 >
            <?= $title; ?>
        </h5>
    </div>
    <div class="chart_body">
        <div id="chartdiv_<?= $id ?>"></div>
    </div>
</div>
<!-- Resources -->
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<?php
$jsCode = '';
$jsCode =
    /** @lang javascript */
    <<<JS
                                    
            am4core.ready(function() {
            
               // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end
                
                // Create chart instance
                var chart = am4core.create("chartdiv_{$id}", am4charts.PieChart);
                
                // Add data
                chart.data = {$data};            
               
                // Add and configure Series
                var pieSeries = chart.series.push(new am4charts.PieSeries());
                pieSeries.dataFields.value = "value";
                pieSeries.dataFields.category = "column";
                pieSeries.slices.template.stroke = am4core.color("{$colorTheme}");
                pieSeries.slices.template.strokeWidth = 2;
                pieSeries.slices.template.strokeOpacity = 1;                
                // This creates initial animation
                pieSeries.hiddenState.properties.opacity = 1;
                pieSeries.hiddenState.properties.endAngle = -90;
                pieSeries.hiddenState.properties.startAngle = -90;            
            }); // end am4core.ready()

JS;
$this->registerJs($jsCode);