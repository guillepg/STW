<?php
require 'class/ChartJS.php';
require 'class/ChartJS_Line.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Estadísticas de uso</title>
    </head>
    <body>
        <script type="text/javascript">
            var xhttp=new XMLHttpRequest();
            xhttp.open('GET','/estadisticas',false);
            xhttp.send();
            var resp=xhttp.responseText;
            if(resp.includes("Slim")) {
                alert("La página no está disponible");
            }
            else{
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.open("POST","chart.js.php",false);
                xmlhttp.send(" json = {'raiz'='prueba'} ");
                function redireccionar(){
                    window.location="chart.js.php?json="+resp;
                }
                redireccionar();
            }
        </script>

    </body>
</html>