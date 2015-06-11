<?php
if(!defined("SPECIALCONSTANT")) die("Acceso denegado");

require 'vendor/autoload.php';
 
use Parse\ParseClient;
use Parse\ParseObject;
use Parse\ParseQuery;

//Informacion parse de mi app (codigos propios de esta app)
ParseClient::initialize('7bq4IwmrtKvSA5JbJ3U4u0fOUn5UpCVT7tdoScSR', 'cb3XtgGXzryvfzYq9lro6CyBCUtL04CheKAJ4Nf6', 'negMQABtG0Ha0fJnXVbqr9UxMOLSQOuNyN6qIK6p');

//obtener ip de acceso
$app->get("/ip", function() use($app){
	$data = '{"ip": "'.get_real_ip().'"}';
	$data = json_decode($data);
    $app->response->headers->set("Content-type", "application/json");
    $app->response->status(200);
    $app->response->body(json_encode($data));
});

//obtener la ultima ruta que ha pedido un cliente
$app->get("/ruta/:ip", function($ip) use($app){

	$query = new ParseQuery("rutas");
	$query->equalTo("ip", $ip);

	$results = $query->find();		//obtengo resultados

	if(count($results)==0){
		$data='{"estado": false }';
	} else{
		$reciente=0;
		for ($i = 0; $i < count($results); $i++) { 		//cojo el mas reciente
			$object = $results[$i];
			$fecha = date_format($object->getCreatedAt(), 'Y-m-d H:i:s');

			if($fecha>date_format($results[$reciente]->getCreatedAt(), 'Y-m-d H:i:s')){
				$reciente=$i;
			}
			
		}
		$object=$results[$reciente];

		$data='{"estado": true, "origen": {"lat": '.$object->get('origenlat').', "long": '.$object->get('origenlong').'}, "destino": {"lat": '.$object->get('destinolat').', "long": '.$object->get('destinolong').'}}';
	}

	$data = json_decode($data);
    $app->response->headers->set("Content-type", "application/json");
    $app->response->status(200);
    $app->response->body(json_encode($data));
});

//obtener el listado de estaciones bizi y su informacion
$app->get("/estaciones", function() use($app){	
	//obtener xml de las estaciones

	$correcto = file_get_contents("http://www.zaragoza.es/api/recurso/urbanismo-infraestructuras/estacion-bicicleta.json?fl=id,estado,bicisDisponibles,anclajesDisponibles,icon,title,geometry&rows=130&srsname=wgs84");

	if(!$correcto){
		$data="";
	}else{
		$data = '{"estado": true, ';
		$data.= '"infoBizi": '.$correcto.'}';
	}

 	$data = json_decode($data);
    $app->response->headers->set("Content-type", "application/json");
    $app->response->status(200);
    $app->response->body(json_encode($data));
});

//guardar informacion sobre el pedido de mostrar una ruta
$app->post("/ruta", function() use ($app){
	try{ 
		$ip = get_real_ip();
		$datosform=$app->request;
		$origen = $datosform->post('origen');
		$destino = $datosform->post('destino');
		list($lat1, $long1, $estacion)=split(", ", $destino);
		$origencoor=getCoordinates($origen);
	
		if($origencoor!="Error"){		
			$ruta = ParseObject::create("rutas");		//se crea un objeto de la clase rutas
			$ruta->set("ip", "$ip");					//se guarda su informacion
			$ruta->set("estado", true);
			$ruta->set("origenlat", $origencoor[0]);
			$ruta->set("origenlong", $origencoor[1]);
			$ruta->set("destinolat", $lat1);
			$ruta->set("destinolong", $long1);
			$ruta->set("estacion", "$estacion");

			try {
				$ruta->save();
			} catch (Parse\ParseException $ex) {  
				echo "error!";
			}
		} else {
			$ruta = ParseObject::create("rutas");		//se crea un objeto de la clase rutas
			$ruta->set("ip", "$ip");					//se guarda su informacion
			$ruta->set("estado", false);
			$ruta->set("estacion", "$estacion");
			
			try {
				$ruta->save();
			} catch (Parse\ParseException $ex) {  
				echo "error!";
			}
		}
	} catch(ErrorException $e){
		echo "error!";
	}
	$app->redirect('inicio.php');			//redireccionamos a la pag inicial
});

//guardar informacion sobre la peticion de la muestra de informacion del tiempo
$app->post("/tiempo", function() use($app){
	$ip = get_real_ip();
	$datosform=$app->request;
	$municipio = $datosform->post('mun');

	$tiempo = ParseObject::create("tiempo");		//se crea un objeto de la clase tiempo
	$tiempo->set("ip", $ip);
	$tiempo->set("ciudad", $municipio);

	try {
		$tiempo->save();
	} catch (Parse\ParseException $ex) {
		echo "error!";
	}
	$app->redirect('inicio.php');			//redireccionamos a la pag inicial
});

//guardar informacion sobre la peticion de la muestra de informacion del tiempo
$app->post("/tiempo", function() use($app){
	$ip = get_real_ip();
	$datosform=$app->request;
	$municipio = $datosform->post('mun');

	$tiempo = ParseObject::create("tiempo");		//se crea un objeto de la clase tiempo
	$tiempo->set("ip", $ip);
	$tiempo->set("ciudad", $municipio);

	try {
		$tiempo->save();
	} catch (Parse\ParseException $ex) {
		echo "error!";
	}
	$app->redirect('inicio.php');			//redireccionamos a la pag inicial
});

/*
Estaciones más visitadas (ranking)			Hecho!  
Poblaciones más consultadas (ranking)		Hecho!
Porcentaje (queso) rutas VS numTiempo  		Hecho!	
Consultas mal formadas VS total   			Hecho! 	
*/
$app->get("/estadisticas", function() use($app){
	$json='{"acciones": '.acciones().', "consultas": '.consultas().', "municipios": '.municipios(5).', "estaciones": '.estaciones(5).'}';
	$json=json_decode($json);
	$app->response->headers->set("Content-type", "application/json");
    $app->response->status(200);
    $app->response->body(json_encode($json));
});


//devuelve el número total de acciones (peticiones a /tiempo y a /rutas), el numero de peticiones
// a /ruta y a /tiempo
function acciones() {
	$query = new ParseQuery("tiempo");
	$results = $query->find();		//obtengo resultados
	$numTiempo=count($results);

	$query2 = new ParseQuery("rutas");
	$results2 = $query2->find();
	$numRutas=count($results2);

	$total=$numTiempo+$numRutas;

	$data = '{"total": '.$total.', "tiempo": '.$numTiempo.', "rutas": '.$numRutas.'}';
	return $data;
};

//devuelve el numero de consultas totales, el numero de las bien formadas y de las mal formadas (sobre las rutas)
function consultas(){
	$query = new ParseQuery("rutas");
	$query->equalTo("estado", false);
	$res = $query->find();		//obtengo resultados
	$numRutasErr=count($res);

	$query2 = new ParseQuery("rutas");
	$query2->equalTo("estado", true);
	$res2 = $query2->find();		//obtengo resultados
	$numRutasTrue=count($res2);

	$numRutasTot=$numRutasErr+$numRutasTrue;

	$data='{"total": '.$numRutasTot.', "correctas": '.$numRutasTrue.', "error": '.$numRutasErr.'}';
	return $data;
};

//devuelve un ranking de las $num localidades con mas peticion de la prediccion del tiempo
function municipios($num){
	$query = new ParseQuery("tiempo");
	$query->ascending("ciudad");
	$res=$query->find();

	$sol=array();
	$cont=array();
	for($i=0; $i<$num; $i++){
		$max=maxMun($res, $sol);
		$n=maxCont($res, $max);
		array_push($sol, $max);
		array_push($cont, $n);
	}

	$data='{"ciudades": [';
	for($i=0; $i<count($sol); $i++){
		$data.='{"nombre": "'.$sol[$i].'", "visitas": '.$cont[$i].' }';
		if($i<count($sol)-1){
			$data.=', ';
		}
	}
	$data.=']}';

	return $data;
};

//devuelve el municipio q mas se repite y que no esta contenido en $sol
function maxMun($res, $sol){
	$mun="";
	$count=0;
	// res está ordenado por ciudad
	$anterior="";
	$canterior=0;
	for ($i = 0; $i < count($res); $i++) { 
		$object=$res[$i];
		$actual=$object->get("ciudad");

		if(contenido($actual, $sol)=="false"){
			if($anterior==$actual){			//si el anterior es el mismo municipio
				$canterior=$canterior+1;
			}else{
				$anterior=$actual;
				$canterior=1;
			}
			if($canterior>$count){
				$count=$canterior;
				$mun=$anterior;
			}
		}
	}
	return $mun;
};

//devuelve las veces que se repite $mun en $res
function maxCont($res, $mun){
	$cont=0;
	for ($i = 0; $i < count($res); $i++) { 
		$object=$res[$i];
		$actual=$object->get("ciudad");

		if($actual==$mun){
			$cont=$cont+1;
		}
	}
	return $cont;
};

function contenido($value, $sol){
	for($i=0; $i<count($sol); $i++){
		if($sol[$i]==$value){
			return "true";
		}
	}
	return "false";
};

function estaciones($num){
	$query = new ParseQuery("rutas");
	$query->ascending("estacion");
	$res=$query->find();

	$sol=array();
	$cont=array();
	for($i=0; $i<$num; $i++){
		$max=maxEst($res, $sol);
		$n=maxContEst($res, $max);
		array_push($sol, $max);
		array_push($cont, $n);
	}

	$data='{"estaciones": [';
	for($i=0; $i<count($sol); $i++){
		$data.='{"nombre": "'.$sol[$i].'", "visitas": '.$cont[$i].' }';
		if($i<count($sol)-1){
			$data.=', ';
		}
	}
	$data.=']}';

	return $data;
};

//devuelve el municipio q mas se repite y que no esta contenido en $sol
function maxEst($res, $sol){
	$mun="";
	$count=0;
	// res está ordenado por ciudad
	$anterior="";
	$canterior=0;
	for ($i = 0; $i < count($res); $i++) { 
		$object=$res[$i];
		$actual=$object->get("estacion");

		if(contenido($actual, $sol)=="false"){
			if($anterior==$actual){			//si el anterior es el mismo municipio
				$canterior=$canterior+1;
			}else{
				$anterior=$actual;
				$canterior=1;
			}
			if($canterior>$count){
				$count=$canterior;
				$mun=$anterior;
			}
		}
	}
	return $mun;
};

//devuelve las veces que se repite $mun en $res
function maxContEst($res, $mun){
	$cont=0;
	for ($i = 0; $i < count($res); $i++) { 
		$object=$res[$i];
		$actual=$object->get("estacion");

		if($actual==$mun){
			$cont=$cont+1;
		}
	}
	return $cont;
};

function getCoordinates($address){
    try{
	    $address = urlencode($address);
	    $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=" . $address;
	    $response = file_get_contents($url);
	    $json = json_decode($response,true);
	 
	    $lat = $json['results'][0]['geometry']['location']['lat'];
	    $lng = $json['results'][0]['geometry']['location']['lng'];
	} catch(Exception $e){
		return "Error";
	}
    return array($lat, $lng);
};

function get_real_ip(){
    if (isset($_SERVER["HTTP_CLIENT_IP"])) {
        return $_SERVER["HTTP_CLIENT_IP"];
    } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    } elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
        return $_SERVER["HTTP_X_FORWARDED"];
    } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
        return $_SERVER["HTTP_FORWARDED_FOR"];
    } elseif (isset($_SERVER["HTTP_FORWARDED"])) {
        return $_SERVER["HTTP_FORWARDED"];
    } else {
        return $_SERVER["REMOTE_ADDR"];
    }
};


?>