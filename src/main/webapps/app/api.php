<?php
if(!defined("SPECIALCONSTANT")) die("Acceso denegado");

require 'vendor/autoload.php';
 
use Parse\ParseClient;
use Parse\ParseObject;

ParseClient::initialize('7bq4IwmrtKvSA5JbJ3U4u0fOUn5UpCVT7tdoScSR', 'cb3XtgGXzryvfzYq9lro6CyBCUtL04CheKAJ4Nf6', 'negMQABtG0Ha0fJnXVbqr9UxMOLSQOuNyN6qIK6p');

$app->get("/estaciones", function() use($app)
{	
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


    /*
    //test parse
	$testObject = ParseObject::create("TestObject");
	$testObject->set("foo", "perico");
	$foo = $testObject->get("foo");
	echo $foo;
	//$testObject->save();

	*/

});


$app->post("/ruta", function() use ($app)
{
	
	echo get_real_ip();

	$datosform=$app->request;
	$origen = $datosform->post('origen');
	$destino = $datosform->post('destino');
	list($lat1, $long1)=split(", ", $destino);

	$origencoor=getCoordinates($origen);
	if($origencoor!="Error"){
		$resp="{'estado': true, 'origen': {'lat': ".$origencoor[0].", 'long': ".$origencoor[1]."}, 'destino': {'lat': ".$lat1.", 'long': ".$long1."}}";
	} else {
		$resp="{'estado': false, 'mensaje': 'Error obtencion coordenadas origen'}";
	}
	echo $resp;
	$app->redirect('plantilla.php');
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

function get_real_ip()
{
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