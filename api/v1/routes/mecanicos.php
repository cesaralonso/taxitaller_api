<?php 


$app->get('/getMecanicos', function() {
    $response = array();
    $db = new DbHandler();

    $response = $db->select("mecanico", "*", array(), "*");
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

$app->get('/getMecanico/:id', function($id){

    $response = array();
    $db = new DbHandler();

    $response = $db->select("mecanico", "*", array("idmecanico"=>$id), "*");
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

$app->post('/postMecanico', function () use ($app) {
    
    if (!$_SESSION['idUser']) 
        exit;    

    $response = array();
    $r = json_decode($app->request->getBody());    
    $r->created_by = $_SESSION['isUser'];
    
    $db = new DbHandler();

    $tabble_nombre = "mecanico";
    $column_nombres = array("nombre", "direccion", "telefono", "correo", "baja", "created_by");

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

$app->put('/putMecanico/:id', function($id) use($app) {
    $request = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();

    $response = $db->put("mecanico", array("idmecanico"=>$id), $request->data);
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

$app->delete('/deleteMecanico/:id', function($id) {
    $response = array();
    $db = new DbHandler();

    $response = $db->deleteWhere("mecanico", array("idmecanico"=>$id));
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