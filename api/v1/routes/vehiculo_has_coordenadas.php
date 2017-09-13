<?php 


$app->get('/getVehiculoHasCoordenadas', function() {
    $response = array();
    $db = new DbHandler();

    $response = $db->select("vehiculo_has_coordenada", "*", array(), "*");
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

$app->get('/getVehiculoHasCoordenada/:id', function($id){

    $response = array();
    $db = new DbHandler();

    $response = $db->select("vehiculo_has_coordenada", "*", array("vehiculo_idvehiculo"=>$id), "*");
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

$app->put('/putVehiculoHasCoordenada/:id', function($id) use($app) {
    $request = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();

    $response = $db->put("vehiculo_has_coordenada", array("vehiculo_idvehiculo"=>$id), $request->data);
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

$app->delete('/deleteVehiculoHasCoordenada/:id', function($id) {
    $response = array();
    $db = new DbHandler();

    $response = $db->deleteWhere("vehiculo_has_coordenada", array("vehiculo_idvehiculo"=>$id));
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