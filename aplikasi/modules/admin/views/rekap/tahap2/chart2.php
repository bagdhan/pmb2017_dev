<?php
use Ghunti\HighchartsPHP\Highchart as Highchart;
use Ghunti\HighchartsPHP\HighchartJsExpr as HighchartJsExpr;
$chart = new Highchart();

$chart->chart->renderTo = "container";
$chart->chart->plotBackgroundColor = null;
$chart->chart->plotBorderWidth = null;
$chart->chart->plotShadow = false;

$chart->title->text = "Sebaran Calon Mahasiswa S2 per Fakultas";

$chart->tooltip->formatter = new HighchartJsExpr(
    "function() {
    return '<b>'+ this.point.name +'</b>: ' + this.point.y +' ('+ Highcharts.numberFormat(this.percentage, 2) + ' % )'; }");

$chart->plotOptions->pie->allowPointSelect = 1;
$chart->plotOptions->pie->cursor = "pointer";
$chart->plotOptions->pie->dataLabels->enabled = false;
$chart->plotOptions->pie->showInLegend = 1;

$chart->series[] = array(
    'type' => "pie",
    'name' => "Browser share",
    'data' => $faks2
);

$chart->credits->enabled = false;

$chart1 = new Highchart();

$chart1->chart->renderTo = "container1";
$chart1->chart->plotBackgroundColor = null;
$chart1->chart->plotBorderWidth = null;
$chart1->chart->plotShadow = false;
$chart1->title->text = "Sebaran Calon Mahasiswa S3 per Fakultas";

$chart1->tooltip->formatter = new HighchartJsExpr(
    "function() {
    return '<b>'+ this.point.name + '</b>: '+ this.point.y +' ('+ Highcharts.numberFormat(this.percentage, 2) + ' % )'; }");

$chart1->plotOptions->pie->allowPointSelect = 1;
$chart1->plotOptions->pie->cursor = "pointer";
$chart1->plotOptions->pie->dataLabels->enabled = false;
$chart1->plotOptions->pie->showInLegend = 1;

$chart1->series[] = array(
    'type' => "pie",
    'name' => "Browser share",
    'data' => $faks3
);

$chart1->credits->enabled = false;

$chart2 = new Highchart();

$chart2->chart->renderTo = "container2";
$chart2->chart->plotBackgroundColor = null;
$chart2->chart->plotBorderWidth = null;
$chart2->chart->plotShadow = false;
$chart2->title->text = "Sebaran Calon Mahasiswa S2 & S3 per Fakultas";

$chart2->tooltip->formatter = new HighchartJsExpr(
    "function() {
    return '<b>'+ this.point.name + '</b>: '+ this.point.y +' ('+ Highcharts.numberFormat(this.percentage, 2) + ' % )'; }");

$chart2->plotOptions->pie->allowPointSelect = 1;
$chart2->plotOptions->pie->cursor = "pointer";
$chart2->plotOptions->pie->dataLabels->enabled = false;
$chart2->plotOptions->pie->showInLegend = 1;

$chart2->series[] = array(
    'type' => "pie",
    'name' => "Browser share",
    'data' => $fak
);

$chart2->credits->enabled = false;

?>

<html>
    <head>
    <title>Pie with legend</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php $chart->printScripts(); ?>
        <?php $chart1->printScripts(); ?>
        <?php $chart2->printScripts(); ?>
    </head>
    <body>
        
        <div class="row">
<div class="col-lg-12" >
        <div class="col col-lg-6" align="center"><div id="container"  style="min-width: 350px; min-height: 200pxh;"></div>
           <script type="text/javascript"><?php echo $chart->render("chart1"); ?></script>
   
        </div>

        <div class="col col-lg-6" align="center"><div id="container1"  style="min-width: 350px; min-height: 200pxh;"></div>
             <script type="text/javascript"><?php echo $chart1->render("chart2"); ?></script>
        </div>

        <div class="col col-lg-12" align="center"><div id="container2" ></div>
             <script type="text/javascript"><?php echo $chart2->render("chart3"); ?></script>
        </div>
        
      </div>
    </div>
         </body>
</html>