<?php 


$app->get('/getTalleres', function() {
    $response = array();
    $db = new DbHandler();

    $response = $db->select("taller", "*", array(), "*");
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

$app->get('/getTaller/:id', function($id){

    $response = array();
    $db = new DbHandler();

    $response = $db->select("taller", "*", array("idTaller"=>$id), "*");
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

$app->post('/postTaller', function () use ($app) {

    if (!isset($_SESSION['iduser'])) {
        // exit;    
    }

    $response = array();
    $r = json_decode($app->request->getBody());
    // $r->created_by = $_SESSION['iduser'];

    $db = new DbHandler();

    $tabble_nombre = "taller";
    $column_nombres = array('nombre','direccion','descripcion','telefono','lat','lng','baja', 'created_by');

    $result = $db->insertIntoTable($r, $column_nombres, $tabble_nombre);

    if ($result != NULL) {
        $response["status"] = "success";
        $response["message"] = "Taller agregado correctamente";
        echoResponse(200, $response);
    } else {
        $response["status"] = "error";
        $response["message"] = "Ha ocurrido un error al tratar de agregar el taller";
        echoResponse(201, $response);
    }    
});

$app->put('/putTaller/:id', function($id) use($app) {
    $request = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();

    $response = $db->put("taller", array("idTaller"=>$id), $request->data);
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

$app->delete('/deleteTaller/:id', function($id) {
    $response = array();
    $db = new DbHandler();

    $response = $db->delete("taller", array("idTaller"=>$id));
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