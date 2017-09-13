<?php 


$app->get('/getPropietarios', function() {
    $response = array();
    $db = new DbHandler();

    $response = $db->select("propietario", "*", array(), "*");
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

$app->get('/getPropietario/:id', function($id){

    $response = array();
    $db = new DbHandler();

    $response = $db->select("propietario", "*", array("idpropietario"=>$id), "*");
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

$app->post('/postPropietario', function () use ($app) {
    
    if (!$_SESSION['idUser']) 
        exit;    

    $response = array();
    $r = json_decode($app->request->getBody());    
    $r->created_by = $_SESSION['idUser'];
    
    $db = new DbHandler();

    $tabble_nombre = "propietario";
    $column_nombres = array("nombre", "direccion", "telefono", "email", "sexo", "lat", "lng", "created_by");

    $result = $db->insertIntoTable($r, $column_nombres, $tabble_nombre);

    if ($result != NULL) {
        $response["status"] = "success";
        $response["message"] = "Propietario agregado correctamente";
        echoResponse(200, $response);
    } else {
        $response["status"] = "error";
        $response["message"] = "Ha ocurrido un error al tratar de agregar el Propietario";
        echoResponse(201, $response);
    }    
});

$app->put('/putPropietario/:id', function($id) use($app) {
    $request = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();

    $response = $db->put("propietario", array("idpropietario"=>$id), $request->data);
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

$app->delete('/deletePropietario/:id', function($id) {
    $response = array();
    $db = new DbHandler();

    $response = $db->deleteWhere("propietario", array("idpropietario"=>$id));
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