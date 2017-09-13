<?php 


$app->get('/getCoordenadas', function() {
    $response = array();
    $db = new DbHandler();

    $response = $db->select("coordenada", "*", array(), "*");
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

$app->get('/getCoordenada/:id', function($id){

    $response = array();
    $db = new DbHandler();

    $response = $db->select("coordenada", "*", array("idcoordenada"=>$id), "*");
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

$app->post('/postCoordenada', function () use ($app) {

    if (!$_SESSION['idUser']) 
        exit;

    $response = array();
    $r = json_decode($app->request->getBody());
    
    $db = new DbHandler();

    $tabble_nombre = "coordenada";
    $column_nombres = array("lat", "lng", "created_at", "baja");

    $result = $db->insertIntoTable($r, $column_nombres, $tabble_nombre);

    if ($result != NULL) {
        $response["status"] = "success";
        $response["message"] = "Coordenada agregada correctamente";
        echoResponse(200, $response);
    } else {
        $response["status"] = "error";
        $response["message"] = "Ha ocurrido un error al tratar de agregar la coordenada";
        echoResponse(201, $response);
    }    
});

$app->put('/putCoordenada/:id', function($id) use($app) {
    $request = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();

    $response = $db->put("coordenada", array("idcoordenada"=>$id), $request->data);
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

$app->delete('/deleteCoordenada/:id', function($id) {
    $response = array();
    $db = new DbHandler();

    $response = $db->deleteWhere("coordenada", array("idcoordenada"=>$id));
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