<?php 


$app->get('/getPermisos', function() {
    $response = array();
    $db = new DbHandler();

    $response = $db->select("permiso", "*", array(), "*");
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

$app->get('/getPermiso/:id', function($id){

    $response = array();
    $db = new DbHandler();

    $response = $db->select("permiso", "*", array("idpermiso"=>$id), "*");
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

$app->post('/postPermiso', function () use ($app) {
    
    if (!$_SESSION['idUser']) 
        exit;    

    $response = array();
    $r = json_decode($app->request->getBody());    
    $r->created_by = $_SESSION['idUser'];

    $db = new DbHandler();

    $tabble_nombre = "permiso";
    $column_nombres = array("permiso", "descripcion", "fecha_inicio", "vigencia", "liquidacion_diaria", "liquidacion_domingo", "baja", "created_by");

    $result = $db->insertIntoTable($r, $column_nombres, $tabble_nombre);

    if ($result != NULL) {
        $response["status"] = "success";
        $response["message"] = "Permiso agregado correctamente";
        echoResponse(200, $response);
    } else {
        $response["status"] = "error";
        $response["message"] = "Ha ocurrido un error al tratar de agregar el Permiso";
        echoResponse(201, $response);
    }    
});

$app->put('/putPermiso/:id', function($id) use($app) {
    $request = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();

    $response = $db->put("permiso", array("idpermiso"=>$id), $request->data);
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

$app->delete('/deletePermiso/:id', function($id) {
    $response = array();
    $db = new DbHandler();

    $response = $db->deleteWhere("permiso", array("idpermiso"=>$id));
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