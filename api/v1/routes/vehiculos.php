<?php 


$app->get('/getVehiculos', function() {
    $response = array();
    $db = new DbHandler();

    $response = $db->select("vehiculo", "*", array(), "*");
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

$app->get('/getVehiculo/:id', function($id){

    $response = array();
    $db = new DbHandler();

    $response = $db->select("vehiculo", "*", array("idvehiculo"=>$id), "*");
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

$app->post('/postVehiculo', function () use ($app) {
    
        if (!$_SESSION['idUser']) 
            exit;    
    
        $response = array();
        $r = json_decode($app->request->getBody());
        $r->created_by = $_SESSION['idUser'];
    
        $db = new DbHandler();
    
        $tabble_nombre = "vehiculo";
        $column_nombres = array("marca", "modelo", "anio", "serie", "placas", "descripcion", "condicion_inicial", "condicion_actual", "estaus_actividad", "baja", "propietario_idpropietario", "permiso_idpermiso", "fecha_asigancion_permiso", "chofer_idchofer", "created_by");
    
        $result = $db->insertIntoTable($r, $column_nombres, $tabble_nombre);
    
        if ($result != NULL) {
            $response["status"] = "success";
            $response["message"] = "Vehiculo agregado correctamente";
            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Ha ocurrido un error al tratar de agregar el Vehiculo";
            echoResponse(201, $response);
        }    
});

$app->put('/putVehiculo/:id', function($id) use($app) {
    $request = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();

    $response = $db->put("vehiculo", array("idvehiculo"=>$id), $request->data);
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

$app->delete('/deleteVehiculo/:id', function($id) {
    $response = array();
    $db = new DbHandler();

    $response = $db->deleteWhere("vehiculo", array("idvehiculo"=>$id));
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