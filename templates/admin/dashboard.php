<?php
/**
 * Created by PhpStorm.
 * User: anhvnit
 * Date: 12/4/16
 * Time: 23:40
 */

?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart','table']});
    google.charts.setOnLoadCallback(drawVisualization);
    google.charts.setOnLoadCallback(drawTable);

    function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([
            ['Month', 'Bolivia', 'Ecuador', 'Madagascar', 'Papua New Guinea', 'Rwanda', 'Average'],
            ['2004/05',  165,      938,         522,             998,           450,      614.6],
            ['2005/06',  135,      1120,        599,             1268,          288,      682],
            ['2006/07',  157,      1167,        587,             807,           397,      623],
            ['2007/08',  139,      1110,        615,             968,           215,      609.4],
            ['2008/09',  136,      691,         629,             1026,          366,      569.6]
        ]);

        var options = {
            title : 'Monthly Coffee Production by Country',
            vAxis: {title: 'Cups'},
            hAxis: {title: 'Month'},
            seriesType: 'bars',
            series: {5: {type: 'line'}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }

    function drawTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Name');
        data.addColumn('number', 'Salary');
        data.addColumn('boolean', 'Full Time Employee');
        data.addRows([
            ['Mike',  {v: 10000, f: '$10,000'}, true],
            ['Jim',   {v:8000,   f: '$8,000'},  false],
            ['Alice', {v: 12500, f: '$12,500'}, true],
            ['Alice', {v: 12500, f: '$12,500'}, true],
            ['Alice', {v: 12500, f: '$12,500'}, true],
            ['Alice', {v: 12500, f: '$12,500'}, true],
            ['Alice', {v: 12500, f: '$12,500'}, true],
            ['Alice', {v: 12500, f: '$12,500'}, true],
            ['Alice', {v: 12500, f: '$12,500'}, true],
            ['Alice', {v: 12500, f: '$12,500'}, true],
            ['Alice', {v: 12500, f: '$12,500'}, true],
            ['Alice', {v: 12500, f: '$12,500'}, true],
            ['Alice', {v: 12500, f: '$12,500'}, true],
            ['Bob',   {v: 7000,  f: '$7,000'},  true]
        ]);

        var table = new google.visualization.Table(document.getElementById('table_div'));

        table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
    }
</script>
<div class="op-dashboard-content">
    <div class="main-chart" id="chart_div"></div>
    <div class="real-content-container">
        <div class="last-orders" >
            <div class="title"><label><?php echo __('Last Orders','openpos'); ?></label></div>
            <div id="table_div"></div>
        </div>
        <div class="total">
            <div class="title"><label><?php echo __('Values','openpos'); ?></label></div>
            <ul id="total-details">
                <li>
                    <div class="field-title"><label><?php echo __('Online','openpos'); ?></label></div>
                    <div class="field-value"><span>100$</span></div>
                </li>
                <li>
                    <div class="field-title"><label id="pos"><?php echo __('POS','openpos'); ?></label></div>
                    <div class="field-value"><span>100$</span></div>
                </li>
            </ul>
        </div>
    </div>
</div>
