<?php
require 'class/ChartJS.php';
require 'class/ChartJS_Line.php';
require 'class/ChartJS_Bar.php';
require 'class/ChartJS_Pie.php';

ChartJS::addDefaultColor(array('fill' => '#f2b21a', 'stroke' => '#e5801d', 'point' => '#e5801d', 'pointStroke' => '#e5801d'));
ChartJS::addDefaultColor(array('fill' => 'rgba(28,116,190,.8)', 'stroke' => '#1c74be', 'point' => '#1c74be', 'pointStroke' => '#1c74be'));
ChartJS::addDefaultColor(array('fill' => 'rgba(212,41,31,.7)', 'stroke' => '#d4291f', 'point' => '#d4291f', 'pointStroke' => '#d4291f'));
ChartJS::addDefaultColor(array('fill' => '#dc693c', 'stroke' => '#ff0000', 'point' => '#ff0000', 'pointStroke' => '#ff0000'));
ChartJS::addDefaultColor(array('fill' => 'rgba(46,204,113,.8)', 'stroke' => '#2ecc71', 'point' => '#2ecc71', 'pointStroke' => '#2ecc71'));

$array_values = array(  array(65, 59, 80, 81, 56, 55, 40),
                        array(28, 48, 40, 19, 86, 27, 90));
$array_labels = array("January", "February", "March", "April", "May", "June", "July");

$Line = new ChartJS_Line('example_line', 300, 300);
$Line->addLine($array_values[0]);
$Line->addLine($array_values[1]);
$Line->addLabels($array_labels);

$Bar = new ChartJS_Bar('example_bar', 300, 300);
$Bar->addBars($array_values[0]);
$Bar->addBars($array_values[1]);
$Bar->addLabels($array_labels);

$Bar2 = new ChartJS_Bar('example_bar', 300, 300);
$Bar2->addBars($array_values[0]);
$Bar2->addBars($array_values[1]);
$Bar2->addLabels($array_labels);

$Pie = new ChartJS_Pie('example_pie', 300, 300);
$Pie->addPart(65);
$Pie->addPart(59);
$Pie->addPart(80);
$Pie->addPart(81);
$Pie->addLabels($array_labels);

$Pie2 = new ChartJS_Pie('example_pie', 300, 300);
$Pie2->addPart(65);
$Pie2->addPart(59);
$Pie2->addPart(80);
$Pie2->addPart(81);
$Pie2->addLabels($array_labels);
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Chart.js-PHP</title>
    </head>
    <body>
        <h1>Estaciones más visitadas (bizi)</h1>
        <?php
        echo $Bar
        ?>

        <h1>Poblaciones más consultadas (el tiempo)</h1>
        <?php
        echo $Bar2
        ?>

        <h1>Número de visitas en los últimos X dias</h1>
        <?php
        echo $Line;
        ?>

        <h1>Peticiones de Maps VS tiempo</h1>
        <?php
        echo $Pie
        ?>

        <h1>Peticiones bizi mal formadas VS total</h1>
        <?php
        echo $Pie2
        ?>

        <script src="Chart.js"></script>
        <script src="chart.js-php.js"></script>
        <script>
            (function () {
                loadChartJsPhp();
            })();
        </script>
    </body>
</html>
