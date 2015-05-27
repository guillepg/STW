<?php
require 'Slim/Slim.php';
// El framework Slim tiene definido un namespace llamado Slim
// Por eso aparece \Slim\ antes del nombre de la clase.

\Slim\Slim::registerAutoloader();

// Creamos la aplicación.
$app = new \Slim\Slim();

// para evitar el acceso a nuestros directorios de definicion
define("SPECIALCONSTANT", true);
require 'app/api.php';

$app->run();

?>