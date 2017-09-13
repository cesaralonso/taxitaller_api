<?php 


$app->get('/getOrdenVehiculoTalleres', function() {
    $response = array();
    $db = new DbHandler();

    $response = $db->select("orden_vehiculo_taller", "*", array(), "*");
    if ($response != NULL) {
        $response["status"] = "success";
        $response["message"] = "Información obtenida correctamente";
        echoResponse(200,  $response);
    } else {
        $response["status"] = "error";
        $response["message"] = "Ha ocurrido un error";
        echoResponse(201, $response);
    }     
});

$app->get('/getOrdenVehiculoTaller/:id', function($id){

    $response = array();
    $db = new DbHandler();

    $response = $db->select("orden_vehiculo_taller", "*", array("idorden_vehiculo_taller"=>$id), "*");
    if ($response != NULL) {
        $response["status"] = "success";
        $response["message"] = "Información obtenida correctamente";
        echoResponse(200,  $response);
    } else {
        $response["status"] = "error";
        $response["message"] = "Ha ocurrido un error";
        echoResponse(201, $response);
    }     
});

$app->post('/postOrdenVehiculoTaller', function () use ($app) {
    
    if (!$_SESSION['idUser']) 
        exit;    

    $response = array();
    $r = json_decode($app->request->getBody());    
    
    $db = new DbHandler();

    $tabble_nombre = "orden_vehiculo_taller";
    $column_nombres = array("orden_vehiculo_taller_idorden_vehiculo_taller", "orden_vehiculo_taller_vehiculo_taller_idvehiculo_taller", "refaccion_idrefaccion");

    $result = $db->insertIntoTable($r, $column_nombres, $tabble_nombre);

    if ($result != NULL) {
        $response["status"] = "success";
        $response["message"] = "Mecanico agregada correctamente";
        echoResponse(200, $response);
    } else {
        $response["status"] = "error";
        $response["message"] = "Ha ocurrido un error al tratar de agregar la Mecanico";
        echoResponse(201, $response);
    }    
});

$app->put('/putOrdenVehiculoTaller/:id', function($id) use($app) {
    $request = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();

    $response = $db->put("orden_vehiculo_taller", array("idorden_vehiculo_taller"=>$id), $request->data);
    if ($response['data'] != NULL) {
        $response["status"] = "success";
        $response["message"] = "Información modificada correctamente";
        echoResponse(200, $response);
    } else {
        $response["status"] = "error";
        $response["message"] = "Ha ocurrido un error al tratar";
        echoResponse(201, $response);
    }
});

$app->delete('/deleteOrdenVehiculoTaller/:id', function($id) {
    $response = array();
    $db = new DbHandler();

    $response = $db->deleteWhere("orden_vehiculo_taller", array("idorden_vehiculo_taller"=>$id));
    if ($response['data'] != NULL) {
        $response["status"] = "success";
        $response["message"] = "Información eliminada correctamente";
        echoResponse(200, $response);
    } else {
        $response["status"] = "error";
        $response["message"] = "Ha ocurrido un error al tratar";
        echoResponse(201, $response);
    }
});