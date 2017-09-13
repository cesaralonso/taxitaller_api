<?php 


$app->get('/getChoferes', function() {
    $response = array();
    $db = new DbHandler();

    $response = $db->select("chofer", "*", array(), "*");
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

$app->get('/getChofer/:id', function($id){
    
        $response = array();
        $db = new DbHandler();
    
        $response = $db->select("chofer", "*", array("idchofer"=>$id), "*");
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

    $tabble_nombre = "chofer";
    $column_nombres = array("nombre", "telefono", "direccion", "email", "lat", "lng", "foto", "licencia", "edad", "sexo", "estado_civil", "descripcion", "baja", "created_by");

    $result = $db->insertIntoTable($r, $column_nombres, $tabble_nombre);

    if ($result != NULL) {
        $response["status"] = "success";
        $response["message"] = "Chofer agregado correctamente";
        echoResponse(200, $response);
    } else {
        $response["status"] = "error";
        $response["message"] = "Ha ocurrido un error al tratar de agregar el chofer";
        echoResponse(201, $response);
    }    
});

$app->put('/putChofer/:id', function($id) use($app) {
    $request = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();

    $response = $db->put("chofer", array("idchofer"=>$id), $request->data);
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


$app->deleteWhere('/deleteChofer/:id', function($id) {
    $response = array();
    $db = new DbHandler();

    $response = $db->delete("taller", array("idchofer"=>$id));
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

