<?php
session_start();

require_once 'dbHandler/dbHandler.php';
require_once 'passwordHash.php';
require './../libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

// User id from db - Global Variable
$user_id = NULL;

require_once 'routes/authentication.php';
require_once 'routes/imagenes.php';
require_once 'routes/talleres.php';
require_once 'routes/choferes.php';
require_once 'routes/choques.php';
require_once 'routes/coordenadas.php';
require_once 'routes/liquidaciones.php';
require_once 'routes/mecanicos.php';
require_once 'routes/orden_vehiculo_talleres.php';
require_once 'routes/permiso.php';
require_once 'routes/propietarios.php';
require_once 'routes/refacciones.php';
require_once 'routes/roles.php';
require_once 'routes/users.php';
require_once 'routes/vehiculos.php';
require_once 'routes/vehiculo_talleres.php';

/**
 * Verifying required params posted or not
 */
function verifyRequiredParams($required_fields,$request_params) {
    $error = false;
    $error_fields = "";
    foreach ($required_fields as $field) {
        if (!isset($request_params->$field) || strlen(trim($request_params->$field)) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["status"] = "error";
        $response["message"] = 'Los siguiente(s) campo(s): ' . substr($error_fields, 0, -2) . ' no se encuentran o están vacios.';
        echoResponse(200, $response);
        $app->stop();
    }
}


function echoResponse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');
    echo json_encode($response);
}

$app->run();
?>