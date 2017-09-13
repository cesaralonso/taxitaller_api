<?php 


$app->get('/getChoques', function() {
    $response = array();
    $db = new DbHandler();

    $response = $db->select("choque", "*", array(), "*");
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

$app->get('/getChoque/:id', function($id){

    $response = array();
    $db = new DbHandler();

    $response = $db->select("choque", "*", array("idchoque"=>$id), "*");
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

$app->post('/postChofer', function () use ($app) {

    if (!$_SESSION['idUser']) 
        exit;

    $response = array();
    $r = json_decode($app->request->getBody());
    $r->created_by = $_SESSION['isUser'];
    
    $db = new DbHandler();

    $tabble_nombre = "choque";
    $column_nombres = array("monto_por_choque", "fecha_choque", "fecha_pago_choque", "baja", "created_by");

    $result = $db->insertIntoTable($r, $column_nombres, $tabble_nombre);

    if ($result != NULL) {
        $response["status"] = "success";
        $response["message"] = "Choque agregado correctamente";
        echoResponse(200, $response);
    } else {
        $response["status"] = "error";
        $response["message"] = "Ha ocurrido un error al tratar de agregar el choque";
        echoResponse(201, $response);
    }    
});

$app->put('/putChoque/:id', function($id) use($app) {
    $request = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();

    $response = $db->put("choque", array("idchoque"=>$id), $request->data);
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

$app->delete('/deleteChoque/:id', function($id) {
    $response = array();
    $db = new DbHandler();

    $response = $db->deleteWhere("choque", array("idchoque"=>$id));
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