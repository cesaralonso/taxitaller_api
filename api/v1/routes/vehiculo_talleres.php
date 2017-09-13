<?php 


$app->get('/getVehiculoTalleres', function() {
    $response = array();
    $db = new DbHandler();

    $response = $db->select("vehiculo_taller", "*", array(), "*");
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

$app->get('/getVehiculoTaller/:id', function($id){

    $response = array();
    $db = new DbHandler();

    $response = $db->select("vehiculo_taller", "*", array("idvehiculo_taller"=>$id), "*");
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

$app->post('/postVehiculoTaller', function () use ($app) {
    
        if (!$_SESSION['idUser']) 
            exit;    
    
        $response = array();
        $r = json_decode($app->request->getBody());
        $r->created_by = $_SESSION['idUser'];
    
        $db = new DbHandler();
    
        $tabble_nombre = "vehiculo_taller";
        $column_nombres = array("fecha_entrada", "fecha_salida", "fecha_tentativa_salida", "observaciones_iniciales", "observaciones_finales", "reparacion", "estatus", "baja", "vehiculo_idvehiculo", "vehiculo_propietario_idpropietario", "vehiculo_permiso_idpermiso", "taller_idtaller", "chofer_idchofer", "created_by");
    
        $result = $db->insertIntoTable($r, $column_nombres, $tabble_nombre);
    
        if ($result != NULL) {
            $response["status"] = "success";
            $response["message"] = "VehiculoTaller agregado correctamente";
            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Ha ocurrido un error al tratar de agregar el VehiculoTaller";
            echoResponse(201, $response);
        }    
});

$app->put('/putVehiculoTaller/:id', function($id) use($app) {
    $request = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();

    $response = $db->put("vehiculo_taller", array("idvehiculo_taller"=>$id), $request->data);
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

$app->delete('/deleteVehiculoTaller/:id', function($id) {
    $response = array();
    $db = new DbHandler();

    $response = $db->deleteWhere("vehiculo_taller", array("idvehiculo_taller"=>$id));
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