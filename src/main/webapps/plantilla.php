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
        <div id="map">
		    <?php
                echo "
                <script type=\"text/javascript\">
                    var map, lat, lng, lat1, lng1;

                    $(function(){
                        $(\"#dibujar\").on('click', crearLista);
                        $(\"#compactar\").on('click', compactarRuta);

                        function geolocalizar(){
                            GMaps.geolocate({
                                success: function(position){
                                    lat = position.coords.latitude;  // guarda coords en lat y lng
                                    lng = position.coords.longitude;
                                    $(document).ready(function(){
                                        map = new GMaps({
                                            el: '#map',
                                            lat: lat,
                                            lng: lng,
                                            zoom: 12,
                                            zoomControl : true,
                                            zoomControlOpt: {
                                                style : 'SMALL',
                                                position: 'TOP_LEFT'},
                                            panControl : false
                                        });
                                    });
                                    console.log(\"mapa creado\");
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

                        function crearLista(e){
                            xhttp=new XMLHttpRequest();
                            xhttp.open(\"GET\",
                            \"http://www.zaragoza.es/api/recurso/urbanismo-infraestructuras/estacion-bicicleta.json?fl=id,estado,bicisDisponibles,anclajesDisponibles,icon,title,geometry&rows=130&srsname=wgs84\",false);
                            xhttp.send();
                            var documento=xhttp.responseText;
                            console.log(\"lista creada\");
                            dibujarMapa(documento);
                        }

                        function dibujarMapa(documento){
                            var obj = JSON.parse(documento);
                            var ini = obj.start; var total = obj.rows;

                            for(var line = ini; line < total; line++){
                                map.addMarker({ lat: obj.result[line].geometry.coordinates[1],
                                                lng: obj.result[line].geometry.coordinates[0],
                                                title: obj.result[line].title,
                                                infoWindow: {content:
                                                    '<p>ID de estacion: '+obj.result[line].id+
                                                    '</p><p>Ubicacion: '+obj.result[line].title+
                                                    '</p><p>Estado: '+obj.result[line].estado+
                                                    '</p><p>Bicis disponibles: '+obj.result[line].bicisDisponibles+
                                                    '</p><p>Anclajes disponibles: '+obj.result[line].anclajesDisponibles+'</p>'},
                                                icon: \"http://www.zaragoza.es/contenidos/iconos/bizi/conbicis.png\"
                                                });
                            }
                        };
                        geolocalizar();
                    });
                </script>
                ";
            ?>
        </div>
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
