<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Trabajo STW</title>
    <link rel="stylesheet" type="text/css" href="plantilla.css" media="screen" />
    <script src="jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
    <script type="text/javascript" src="gmaps.js"></script>
</head>
<body>
	<div id="bizis" class="cont">
		<form method="POST" action="">
		<fieldset>
		<legend>Bizis:</legend>
			Dirección:
			<input type="text" name="direccion"/>
			<br><br>
			<select class="centrar">
				<option value="estacion1">Estacion1</option>
				<option value="">Estacion2</option>
				<option value="">Estacion3</option>
				<option value="">Estacion4</option>
			</select>
			<input id="submit" type="submit" value="Calcula" class="centrar"/>
		</fieldset>
		</form>
	</div>
	    <div id="mapa" class="cont">
		<fieldset>
		<legend>Mapa:</legend>
        <button type="button" id="compactar" align="left"> Compactar ruta</button>
        <button type="button" id="dibujar" align="right"> Dibujar puntos</button>
        <div id="map" style="width:600px; height:400px;"></div>
        <?php
            echo "
            <script type=\"text/javascript\">
                var map, lat, lng, lat1, lng1;

                $(function(){

                    function geolocalizar(){
                        GMaps.geolocate({
                            success: function(position){
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
                                xhttp.open(\"GET\",
                                \"http://www.zaragoza.es/api/recurso/urbanismo-infraestructuras/estacion-bicicleta.json?fl=id,estado,bicisDisponibles,anclajesDisponibles,icon,title,geometry&rows=130&srsname=wgs84\",false);
                                xhttp.send();
                                var documento=xhttp.responseText;
                                var obj = JSON.parse(documento);
                                var ini = obj.start; var total = obj.rows;

                                for(var line = ini; line < total; line++){
                                    var lat = obj.result[line].geometry.coordinates[1];
                                    var lng = obj.result[line].geometry.coordinates[0];
                                    var pos = new google.maps.LatLng(lat,lng);
                                    var marker = new google.maps.Marker({
                                          position: pos,
                                          map: map,
                                          title: 'Estacion '+obj.result[line].id+\": \"+obj.result[line].title+
                                                '   Estado: '+obj.result[line].estado+
                                                '   Bicis: '+obj.result[line].bicisDisponibles+
                                                '   Anclajes: '+obj.result[line].anclajesDisponibles,
                                          icon: \"http://www.zaragoza.es/contenidos/iconos/bizi/conbicis.png\"
                                      });
                                    /*map.addMarker({ lat: obj.result[line].geometry.coordinates[1],
                                                    lng: obj.result[line].geometry.coordinates[0],
                                                    title: obj.result[line].title,
                                                    infoWindow: {content:
                                                        '<p>ID de estacion: '+obj.result[line].id+
                                                        '</p><p>Ubicacion: '+obj.result[line].title+
                                                        '</p><p>Estado: '+obj.result[line].estado+
                                                        '</p><p>Bicis disponibles: '+obj.result[line].bicisDisponibles+
                                                        '</p><p>Anclajes disponibles: '+obj.result[line].anclajesDisponibles+'</p>'},
                                                    icon: \"http://www.zaragoza.es/contenidos/iconos/bizi/conbicis.png\"
                                                    });*/
                                }
                            },
                            error: function(error) { alert('Geolocalización falla: '+error.message); },
                            not_supported: function(){ alert(\"Su navegador no soporta geolocalización\"); },
                        });
                    };

                    function enlazarMarcador(e){
                        // muestra ruta entre marcas anteriores y actuales
                        map.drawRoute({
                            origin: [lat, lng],  // origen en coordenadas anteriores
                            // destino en coordenadas del click o toque actual
                            destination: [e.latLng.lat(), e.latLng.lng()],
                            travelMode: 'driving',
                            strokeColor: '#000000',
                            strokeOpacity: 0.6,
                            strokeWeight: 5
                        });
                        lat = e.latLng.lat();   // guarda coords para marca siguiente
                        lng = e.latLng.lng();

                        map.addMarker({ lat: lat, lng: lng});  // pone marcador en mapa
                    };

                    function compactarRuta(){
                        map.cleanRoute();
                        map.removeMarkers();
                        map.addMarker({ lat: lat1, lng: lng1});
                        map.addMarker({ lat: lat, lng: lng});
                        map.drawRoute({
                            origin: [lat1, lng1],
                            destination: [lat, lng],
                            travelMode: 'driving',
                            strokeColor: '#FF0000',
                            strokeOpacity: 0.8,
                            strokeWeight: 5
                        });
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
            //cliente soap que llame a la operacion DescargarInfoTiempo(codigo)
            //y despues llame a GenerarHTML(xml) e imprima el resultado aquii

        ?>
        </div>
        <form method="GET" action="plantilla.php">

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
        ?>

            <select class="centrar" name="mun">
                <?php
                foreach($municipios as $res){
                    echo '<option value="'.$res.'">'.$res.'</option>';
                }
                ?>
            </select>
            <input id="submit" type="submit" value="Obtener información meteorológica" class="centrar"/>
        </form>

        <?php
            if(isset($_GET['mun'])){
                $munSel=$_GET['mun'];
                echo $munSel;
                $codigo=obtenerCod($munSel, $municipios, $codigos);
            }

            function obtenerCod($string, $muni, $codi) {
                $cont=1;
                foreach($muni as $m){
                    if($m==$string){
                        echo '</br>'.$codi[$cont];
                        return $cont;
                    }
                    $cont+=1;
                }
                return 0;
            }

        ?>

        </fieldset>
    </div>

</body>
</html>
