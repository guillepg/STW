<?php
if(!defined("SPECIALCONSTANT")) die("Acceso denegado");

require 'vendor/autoload.php';
 
use Parse\ParseClient;
use Parse\ParseObject;
use Parse\ParseQuery;

//Informacion parse de mi app (codigos propios de esta app)
ParseClient::initialize('7bq4IwmrtKvSA5JbJ3U4u0fOUn5UpCVT7tdoScSR', 'cb3XtgGXzryvfzYq9lro6CyBCUtL04CheKAJ4Nf6', 'negMQABtG0Ha0fJnXVbqr9UxMOLSQOuNyN6qIK6p');

//obtener ip de acceso
$app->get("/ip", function($app) use($app){
	$data = '{"ip": '.get_real_ip().'}';
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
		echo "no hay resultados";
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

		$data='{"estado": true, "origen": {"lat": '.$object->get('origenlat').', "long": '.$object->get('origenlong').'}, "destino": {"lat": "'.$object->get('destinolat').'", "long": "'.$object->get('destinolong').'"}}';
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
	
	$ip = get_real_ip();
	$datosform=$app->request;
	$origen = $datosform->post('origen');
	$destino = $datosform->post('destino');
	list($lat1, $long1)=split(", ", $destino);
	$origencoor=getCoordinates($origen);

	if($origencoor!="Error"){		
		$ruta = ParseObject::create("rutas");		//se crea un objeto de la clase rutas
		$ruta->set("ip", "$ip");					//se guarda su informacion
		$ruta->set("estado", true);
		$ruta->set("origenlat", $origencoor[0]);
		$ruta->set("origenlong", $origencoor[1]);
		$ruta->set("destinolat", $lat1);
		$ruta->set("destinolong", $long1);

		try {
			$ruta->save();
		} catch (ParseException $ex) {  
			
		}
	} else {
		$ruta = ParseObject::create("rutas");		//se crea un objeto de la clase rutas
		$ruta->set("ip", "$ip");					//se guarda su informacion
		$ruta->set("estado", false);
		
		try {
			$ruta->save();
		} catch (ParseException $ex) {  
			
		}
	}

	$app->redirect('plantilla.php');			//redireccionamos a la pag inicial

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
	} catch (ParseException $ex) {  }
	$app->redirect('plantilla.php');			//redireccionamos a la pag inicial
});

//devuelve el número total de acciones (peticiones a /tiempo y a /rutas), el numero de peticiones
// a /ruta y a /tiempo
$app->get("/acciones", function() use($app){
	$query = new ParseQuery("tiempo");
	$results = $query->find();		//obtengo resultados
	$numTiempo=count($results);

	$query2 = new ParseQuery("rutas");
	$results2 = $query2->find();
	$numRutas=count($results2);

	$total=$numTiempo+$numRutas;

	$data = '{"total": '.$total.', "tiempo": '.$numTiempo.', "rutas": '.$numRutas.'}';
	$data=json_decode($data);
	$app->response->headers->set("Content-type", "application/json");
    $app->response->status(200);
    $app->response->body(json_encode($data));
});

//devuelve un listado de ip ordenadas por ultimo acceso 
//
$app->get("/ip/:num", function($num) use($app){
	$query = new ParseQuery("tiempo");
	$query->descending("createdAt");
	$results = $query->find();		//obtengo resultados
	$query2 = new ParseQuery("rutas");
	$query2->descending("createdAt");
	$results2 = $query2->find();		//obtengo resultados

	$ip=array();

	if(count($results)==0){
		echo "no hay resultados";
	} else{
		for ($i = 0; $i < count($results); $i++) {
			array_push($ip, $results[$i]->get("ip"));				//array de ips de acceso a tiempo
		}
		for ($i = 0; $i < count($results2); $i++) {
			array_push($ip, $results2[$i]->get("ip"));				//array de ips de acceso a rutas
		}
		

		//terminar
		
	}



	count($results);
});


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