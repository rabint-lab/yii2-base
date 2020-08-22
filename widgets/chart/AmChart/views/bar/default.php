<?php
$final_data = [];
foreach ($data as $index=>$line)
{
    foreach ($line[0]['data'] as $year =>$value){
        if(!isset($final_data[$year]))
            $final_data[$year] = [
                    'year'=>$year
            ];
        if(!isset($final_data[$year][$index]))
            $final_data[$year][$index] = $value;
        //array_push($final_data[$year],[$index=>$value]);
    }
}

$lines = [];
foreach ($data as $field=>$line) {
   $lines[] = [
           'field'=>$field,
           'title'=>$line[0]['label']
   ];
}
$strCreateSeries ='';
foreach ($lines as $l)
{
    $strCreateSeries.='createSeries("'.$l['field'].'", "'.$l['title'].'");';
}
$temp=[];
foreach ($final_data as $year=>$datum)
{
    array_push($temp,$datum);
}

$json =json_encode($temp);
?>
    <style>
        #chartdiv_<?= $id; ?> {
            width: 100%;
            height: 500px;
        }

    </style>
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
var chart = am4core.create("chartdiv_{$id}", am4charts.XYChart);

// Add data
chart.data = {$json};
chart.rtl = true; 
// Create axes
var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "year";
categoryAxis.numberFormatter.numberFormat = "#";
categoryAxis.renderer.inversed = true;
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.cellStartLocation = 0.1;
categoryAxis.renderer.cellEndLocation = 0.9;

var  valueAxis = chart.xAxes.push(new am4charts.ValueAxis()); 
valueAxis.renderer.opposite = true;

// Create series
function createSeries(field, name) {
  var series = chart.series.push(new am4charts.ColumnSeries());
  series.dataFields.valueX = field;
  series.dataFields.categoryY = "year";
  series.name = name;
  series.columns.template.tooltipText = "{name}: [bold]{valueX}[/]";
  series.columns.template.height = am4core.percent(100);
  series.sequencedInterpolation = true;

  var valueLabel = series.bullets.push(new am4charts.LabelBullet());
  valueLabel.label.text = "{valueX}";
  valueLabel.label.horizontalCenter = "left";
  valueLabel.label.dx = 10;
  valueLabel.label.hideOversized = false;
  valueLabel.label.truncate = false;

  var categoryLabel = series.bullets.push(new am4charts.LabelBullet());
  categoryLabel.label.text = "{name}";
  categoryLabel.label.horizontalCenter = "right";
  categoryLabel.label.dx = -10;
  categoryLabel.label.fill = am4core.color("#fff");
  categoryLabel.label.hideOversized = false;
  categoryLabel.label.truncate = false;
}

{$strCreateSeries}

}); // end am4core.ready()

JS;
$this->registerJs($jsCode);