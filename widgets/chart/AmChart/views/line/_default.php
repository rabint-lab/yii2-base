<?php
$final_data = [];
foreach ($data as $index => $line) {
    foreach ($line[0]['data'] as $year => $value) {
        if (!isset($final_data[$year]))
            $final_data[$year] = [
                'year' => $year
            ];
        if (!isset($final_data[$year][$index]))
            $final_data[$year][$index] = $value;
        //array_push($final_data[$year],[$index=>$value]);
    }
}

$lines = [];
foreach ($data as $field => $line) {
    $lines[] = [
        'field' => $field,
        'title' => $line[0]['label']
    ];
}
$options = $lines;
$temp = [];
foreach ($final_data as $year => $datum) {
    array_push($temp, $datum);
}

$json = json_encode($temp);

?>
<style>
    #chartdiv_<?= $id; ?> {
        width: 100%;
        height: 500px;
        font-family: 'sahel_fd';
    }

</style>
<div class="card">
    <div class="card-header chart-chad">
        <div class="float-right ">
            <i class="fa fa-arrows-alt chartAction chartZoom chartZoom<?= $id; ?>" data-toggle="modal"
               data-target="#chartModal<?= $id; ?>"></i>
        </div>
        <div class="float-right">
            <?= $title; ?>
        </div>
        <div class="float-left form-inline">
            <div class="form-group">
                <label for="budgetIndexCh" class="mr-2 ml-2 "><?= $filterTitle; ?>:</label>
                <select class="form-control form-control-sm chart_select_filter_<?= $id ?>" id="<?= $id; ?>_select_filter">
                    <?php
                    $k = 0;
                    foreach ($lines as $opt) {
                        ?>
                        <option value="<?= $opt['field']; ?>" <?= $k == 0 ? ' selected="selected" ' : ''; ?> ><?= $opt['title']; ?></option>
                        <?php
                        $k++;
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body">

        <div class="chartWidgetBox chart_<?= $id; ?>">
            <div id="chartdiv_<?= $id ?>"></div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="chartModal<?= $id; ?>" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> <?= $title; ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    am4core.ready(function () {
        // Themes begin
        var chart_<?= $id ?> = am4core.create("chartdiv_<?=$id?>", am4charts.XYChart);
        // = Object.create(chart);
        // Add data
        var data_<?= $id ?> = <?= $json ?>;

        $(".chart_select_filter_<?= $id ?>").change(function () {
            regeneratechart<?= $id ?>(data_<?= $id ?>, $(this).val(), $(this).text())
        })
        regeneratechart<?= $id ?>(data_<?= $id ?>, $('.chart_select_filter_<?= $id ?>').val(), $('.chart_select_filter_<?= $id ?>').text());

        function regeneratechart<?= $id ?>(new_data, col, label) {
            am4core.useTheme(am4themes_animated);
            chart_<?= $id ?> = am4core.create("chartdiv_<?= $id ?>", am4charts.XYChart);
            chart_<?= $id ?>.paddingRight = 20;
            chart_<?= $id ?>.paddingLeft = 20;
            chart_<?= $id ?>.numberFormatter.numberFormat = "#";
            // Add data
            chart_<?= $id ?>.data = new_data;
            chart_<?= $id ?>.rtl = true;
            var categoryAxis = chart_<?= $id ?>.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "year";

            // Create value axis
            var valueAxis = chart_<?= $id ?>.yAxes.push(new am4charts.ValueAxis());
            valueAxis.baseValue = 0;
            valueAxis.title.text = '<?= $yLabel ?>';
            var series$i = chart_<?= $id ?>.series.push(new am4charts.LineSeries());
            series$i.dataFields.valueY = col;
            series$i.dataFields.categoryX = "year";

            series$i.name = label;

        }
    });

</script>