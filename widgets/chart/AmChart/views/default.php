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