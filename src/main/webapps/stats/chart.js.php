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

    $texto_json = $_GET["json"];
    $obj = json_decode($texto_json, true);

    /*---GRAFICO DE BARRAS 1: Estaciones más visitadas---*/
    $array_values_2 = array(33, 25, 23, 18, 5, 2);
    $array_labels_2 = array("Estacion 1", "Estacion 2", "Estacion 3", "Estacion 4", "Estacion 5", "Estacion 6");
    $Bar = new ChartJS_Bar('example_bar', 300, 300);
    $Bar->addBars($array_values_2);
    $Bar->addLabels($array_labels_2);

    /*---GRAFICO DE BARRAS 2: Poblaciones más buscadas---*/
    $array_values_3 = array(33, 25, 23, 18, 5, 2);
    $array_labels_3 = array("Dia 1", "Dia 2", "Dia 3", "Dia 4", "Dia 5", "Dia 6");
    $Bar2 = new ChartJS_Bar('example_bar', 300, 300);
    $Bar2->addBars($array_values_3);
    $Bar2->addLabels($array_labels_3);

    /*---GRAFICO DE LINEA: Historico de visitas---*/
    $array_values_1 = array(33, 25, 23, 18, 5, 2);
    $array_labels_1 = array("Dia 1", "Dia 2", "Dia 3", "Dia 4", "Dia 5", "Dia 6");
    $Line = new ChartJS_Line('example_line', 300, 300);
    $Line->addLine($array_values_1);
    $Line->addLabels($array_labels_1);

    /*---GRAFICO DE TARTA 2: Bien VS mal formadas---*/
    /*consultas*/
    $Pie2 = new ChartJS_Pie('example_pie', 300, 300);
    $Pie2->addPart(40);
    $Pie2->addPart(80);
    $Pie2->addLabels($array_labels_3);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Chart.js-PHP</title>
        <script src="Chart.js"></script>
        <script src="chart.js-php.js"></script>
    </head>
    <body>
        <h1>Estaciones más visitadas (bizi)</h1>
        <?php
        echo $Bar
        ?>

        <h1>Poblaciones más consultadas (el tiempo)</h1>
        <h1>Número de visitas en los últimos X dias</h1>

        <h1>Peticiones de Maps VS tiempo</h1>
        <?php
            $tiempo = (int)$obj['tiempo'];
            $rutas = (int)$obj['rutas'];
            $Pie = new ChartJS_Pie('example_pie', 300, 300);
            $Pie->addPart($tiempo);
            $Pie->addPart($rutas);
            $Pie->addLabels(array("Consultas al servicio meteorológico", "Consultas al servicio de rutas"));
            echo $Pie
        ?>
        <script type="text/javascript" >
        //          http://localhost/stats/stats.php
        </script>

        <h1>Peticiones bizi mal formadas VS total</h1>

        <script src="Chart.js"></script>
        <script src="chart.js-php.js"></script>
        <script>
            (function () {
                loadChartJsPhp();
            })();
        </script>
    </body>
</html>
