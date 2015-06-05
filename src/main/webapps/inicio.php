<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Trabajo STW</title>
    <link rel="stylesheet" type="text/css" href="inicio.css" media="screen" />
    <script src="jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
    <script type="text/javascript" src="gmaps.js"></script>
</head>
<body>
        <div id="mapa" class="cont">
        <fieldset>
        <legend>Mapa:</legend>
        <button type="button" id="mostrar" align="center"> Mostrar ruta </button>
        <div id="map" style="width:600px; height:400px;"></div>
        <?php
            echo "
            <script type=\"text/javascript\">
                var map, lat, lng, lat1, lng1;
                var geocoder, origen, destino;
                $(function(){
                    $(\"#mostrar\").on('click', codeAddress2);
                    function geolocalizar(){
                        GMaps.geolocate({success: function(position){
                            //obtenemos la posicion actual
                            lat = position.coords.latitude;  // guarda coords en lat y lng
                            lng = position.coords.longitude;
                            var myLatlng = new google.maps.LatLng(lat, lng);
                            var myOptions = {
                                    zoom: 13,
                                    center: myLatlng,
                                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                                };
                            map = new google.maps.Map($(\"#map\").get(0), myOptions);
                            var actual = new google.maps.Marker({
                              position: myLatlng,
                              map: map,
                              title: \"ud. esta aqui\"
                            });
                            
                            //generamos la peticion del JSON con las estaciones de bizi
                            xhttp=new XMLHttpRequest();
                            xhttp.open(\"GET\",\"/estaciones\",false);
                            xhttp.send();
                            var documento=xhttp.responseText;
                            var obj = JSON.parse(documento);
                            var estado = obj.estado;
                            if(estado){
                                var ini = obj.infoBizi.start; var total = obj.infoBizi.totalCount;
                                for(var line = ini; line < total; line++){
                                    var lat = obj.infoBizi.result[line].geometry.coordinates[1];
                                    var lng = obj.infoBizi.result[line].geometry.coordinates[0];
                                    var pos = new google.maps.LatLng(lat,lng);
                                    var marker = new google.maps.Marker({
                                          position: pos,
                                          map: map,
                                          title: 'Estacion '+obj.infoBizi.result[line].id+\": \"+obj.infoBizi.result[line].title+
                                                '   Estado: '+obj.infoBizi.result[line].estado+
                                                '   Bicis: '+obj.infoBizi.result[line].bicisDisponibles+
                                                '   Anclajes: '+obj.infoBizi.result[line].anclajesDisponibles,
                                          icon: obj.infoBizi.result[line].icon
                                      });
                                }
                            }
                            },error: function(error) { alert('Geolocalización falla: '+error.message); },
                            not_supported: function(){ alert(\"Su navegador no soporta geolocalización\"); },
                        });
                    };
                    function codeAddress2(e) {
                        xhttp_ip=new XMLHttpRequest();
                        xhttp_ip.open(\"GET\",\"/ip\",false);
                        xhttp_ip.send();
                        var resp_ip=xhttp_ip.responseText;
                        var obj_ip = JSON.parse(resp_ip);
                        var ip = obj_ip.ip;
                        xhttp=new XMLHttpRequest();
                        xhttp.open(\"GET\",\"/ruta/\"+ip,false);
                        xhttp.send();
                        var documento=xhttp.responseText;
                        var obj = JSON.parse(documento);
                        var estado = obj.estado;
                        if(estado){
                            var orig = new google.maps.LatLng(obj.origen.lat, obj.origen.long);
                            var dest = new google.maps.LatLng(obj.destino.lat, obj.destino.long);
                            var marker = new google.maps.Marker({
                                map: map,
                                position: orig
                            });
                            var marker = new google.maps.Marker({
                                map: map,
                                position: dest
                            });
                            var directionsService = new google.maps.DirectionsService();
                            var directionsDisplay = new google.maps.DirectionsRenderer();
                            directionsDisplay.setMap(map);
                            var request = {
                                origin: orig,
                                destination: dest,
                                travelMode: google.maps.TravelMode.WALKING
                            };
                            directionsService.route(request, function(result, status) {
                                if (status == google.maps.DirectionsStatus.OK) {
                                    directionsDisplay.setDirections(result);
                                }
                            });
                        }
                    }
                    geolocalizar();
                });
            </script>
            ";
        ?>
        </fieldset>
    </div>
    <div id="p6" class="cont">
        <fieldset>
        <legend>Previsión meteorológica:</legend>
        <div id="tableTiempo">
        
        <?php
            $fp=fopen("../resources/municipios.txt", "r");
            $linea;
            $municipios=array();
            $codigos=array();
            while( ($linea=fgets($fp)) !== false ){
                list($cpro, $mun, $dc, $nombre)=split("[\r\t]+", $linea);
                array_push($municipios, $nombre);
                array_push($codigos, $cpro.$mun);
            }
            fclose($fp);
        //***
           $codigo=50001;
           $munSel="Abanto";
           if(isset($_GET['mun'])){
                $munSel=$_GET['mun'];
                $codigo=obtenerCod($munSel, $municipios, $codigos);
            }
            function obtenerCod($string, $muni, $codi) {
                $cont=1;
                foreach($muni as $m){
                    if($m==$string){
                        return $codi[$cont];
                    }
                    $cont+=1;
                }
                return 0;
            }
        //**********
            try{
                $clienteSOAP = new SoapClient('http://localhost:8080/axis/services/Tiempo?wsdl');
                
                $xmlTiempo = $clienteSOAP->DescargarInfoTiempo($codigo);
                $html = $clienteSOAP->GenerarHTML($xmlTiempo);
                $json = $clienteSOAP->GenerarJSON($xmlTiempo);
                echo("<h2>Tiempo en ".$munSel.":</h2>");
                echo($html);
             
            } catch(SoapFault $e){
                echo "<h3>Error al obtener el tiempo. Prueba mas tarde.</h3>";
            }
        ?>
        </div>
        <form method="POST" action="/tiempo">
            <select class="centrar" name="mun">
                <?php
                foreach($municipios as $res){
                    echo '<option value="'.$res.'">'.$res.'</option>';
                }
                ?>
            </select>
            <input id="submit" type="submit" value="Obtener información meteorológica" class="centrar"/>
        </form>
        </fieldset>
    </div>
    <div id="bizis" class="cont">
        <form method="POST" action="/ruta">
        <fieldset>
        <legend>Bizis:</legend>
            Dirección:
            <input type="text" name="origen"/>
            <br><br>
            <select class="centrar" id="estaciones" name="destino">
                <script type="text/javascript"> 
                    xhttp=new XMLHttpRequest();
                    xhttp.open('GET', '/estaciones' ,false);
                    xhttp.send();
                    var documento=xhttp.responseText;
                    var obj = JSON.parse(documento);
                    var estado = obj.estado;
                    if(estado){         /* creo las opciones del spinner */
                        var ini = obj.infoBizi.start; var total = obj.infoBizi.totalCount;
                        select = document.getElementById("estaciones");
                        for(var line = ini; line < total; line++){
                            var estacion = obj.infoBizi.result[line].title;
                            var lat = obj.infoBizi.result[line].geometry.coordinates[1];
                            var lng = obj.infoBizi.result[line].geometry.coordinates[0];
                            var opt = document.createElement('option');
                            opt.value = lat+', '+lng+', '+estacion;
                            opt.innerHTML = estacion;
                            select.appendChild(opt);
                        }
                    }
                    
                </script>
                
            </select>
            <input id="submit" type="submit" value="Calcula" class="centrar"/>
        </fieldset>
        </form>
    </div>
</body>
</html>