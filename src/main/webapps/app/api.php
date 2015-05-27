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
	$datosform=$app->request;
	$origen = $datosform->post('origen');
	$destino = $datosform->post('destino');
	list($lat1, $long1)=split(", ", $destino);

	$lat2 =' '; $long2=' ';


	$resp="{'estado': true, 'origen': {'lat': lat2, 'long': long2}, 'destino': {'lat': ".$lat1.", 'long': ".$long1."}}";
	echo $resp;


});


?>