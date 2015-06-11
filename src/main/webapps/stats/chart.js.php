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
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Gráficas de uso</title>
        <script src="Chart.js"></script>
        <script src="chart.js-php.js"></script>
    </head>
    <body>
        <div align="left">
            <form action="http://localhost/stats/stats.php">
                <input type="submit" value="Recargar estadisticas">
            </form>
            <form action="http://localhost/inicio.php" >
                    <input type="submit" value="Volver al inicio">
            </form>
        </div>

        <div>
            <h1 align="left">Estaciones más visitadas (bizi) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
            Poblaciones más consultadas (el tiempo)</h1>
            <?php
                $array_values_1 = array($obj['estaciones']['estaciones'][0]['visitas'],
                        $obj['estaciones']['estaciones'][1]['visitas'],
                        $obj['estaciones']['estaciones'][2]['visitas'],
                        $obj['estaciones']['estaciones'][3]['visitas'],
                        $obj['estaciones']['estaciones'][4]['visitas']);
                $array_labels_1 = array($obj['estaciones']['estaciones'][0]['nombre'],
                        $obj['estaciones']['estaciones'][1]['nombre'],
                        $obj['estaciones']['estaciones'][2]['nombre'],
                        $obj['estaciones']['estaciones'][3]['nombre'],
                        $obj['estaciones']['estaciones'][4]['nombre']);
                $Bar = new ChartJS_Bar('example_bar', 500, 500);
                $Bar->addBars($array_values_1);
                $Bar->addLabels($array_labels_1);

                $array_values_2 = array($obj['municipios']['ciudades'][0]['visitas'],
                        $obj['municipios']['ciudades'][1]['visitas'],
                        $obj['municipios']['ciudades'][2]['visitas'],
                        $obj['municipios']['ciudades'][3]['visitas'],
                        $obj['municipios']['ciudades'][4]['visitas']);
                $array_labels_2 = array($obj['municipios']['ciudades'][0]['nombre'],
                        $obj['municipios']['ciudades'][1]['nombre'],
                        $obj['municipios']['ciudades'][2]['nombre'],
                        $obj['municipios']['ciudades'][3]['nombre'],
                        $obj['municipios']['ciudades'][4]['nombre']);
                $Bar2 = new ChartJS_Bar('example_bar', 500, 500);
                $Bar2->addBars($array_values_2);
                $Bar2->addLabels($array_labels_2);
                echo $Bar . $Bar2;
            ?>
        </div>

        <div>
            <h1>Peticiones de Maps y de tiempo &nbsp; &nbsp; Peticiones de ruta bien formadas y erróneas</h1>
            <?php
                $Pie = new ChartJS_Pie('example_pie', 300, 300);
                $Pie->addPart($obj['acciones']['tiempo']);
                $Pie->addPart($obj['acciones']['rutas']);
                $Pie->addLabels(array("Consultas del tiempo", "Consultas de rutas"));
                $PieBlank = new ChartJS_Pie('example_pie', 300, 300);
                $Pie2 = new ChartJS_Pie('example_pie', 300, 300);
                $Pie2->addPart($obj['consultas']['correctas']);
                $Pie2->addPart($obj['consultas']['error']);
                $Pie2->addLabels(array("Correctas", "Erroneas"));
                echo $Pie . $PieBlank . $Pie2
            ?>
        </div>
        <script>
            (function () {
                loadChartJsPhp();
            })();
        </script>
    </body>
</html>
