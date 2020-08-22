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
$i = 0;
foreach ($lines as $l)
{
    $strCreateSeries.="
        
        var series$i = chart.series.push(new am4charts.LineSeries());
        series$i.dataFields.valueY = \"".$l['field']."\";
        series$i.dataFields.categoryX = \"year\";
 
        series$i.name = \"".$l['title']."\";
         
          
      
        
    ";
    $i++;
}
$temp=[];
foreach ($final_data as $year=>$datum)
{
    array_push($temp,$datum);
}
$json =json_encode($temp);
?>
<div class="chartWidgetBox chart_<?= $id; ?>">
    <div class="chart_header center">
        <h5 >
            <?= $title; ?>
        </h5>
    </div>
    <div class="chart_body">
        <div id="chartdiv"></div>
    </div>
</div>
<style>
    #chartdiv {
        width: 100%;
        height: 500px;
    }

</style>
<!-- Resources -->
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<script>
    am4core.ready(function() {

        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end
        // Create chart instance
        var chart = am4core.create("chartdiv", am4charts.XYChart);
        chart.paddingRight = 20;
        chart.paddingLeft = 20;
        chart.legend = new am4charts.Legend();

        // Add data
        chart.data = <?= $json ?>;
        chart.rtl = true;
        console.log(chart.data);
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "year";

        // Create value axis
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.baseValue = 0;

        // Create series
        <?= $strCreateSeries ?>

    }); // end am4core.ready()
</script>