<?php 


$app->get('/getLiquidaciones', function() {
    $response = array();
    $db = new DbHandler();

    $response = $db->select("liquidacion", "*", array(), "*");
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

$app->get('/getLiquidacion/:id', function($id){

    $response = array();
    $db = new DbHandler();

    $response = $db->select("liquidacion", "*", array("idliquidacion"=>$id), "*");
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

$app->post('/postLiquidacion', function () use ($app) {
    
    if (!$_SESSION['idUser']) 
        exit;    

    $response = array();
    $r = json_decode($app->request->getBody());    
    $r->created_by = $_SESSION['isUser'];
    
    $db = new DbHandler();

    $tabble_nombre = "liquidacion";
    $column_nombres = array("folio", "fecha", "liquidacion_a_pagar", "liquidacion_pagada", "liquidacion_deuda", "liquidacion_estatus", "observaciones", "firma", "baja", "permiso_idpermiso", "created_by", "liquidacioncol");

    $result = $db->insertIntoTable($r, $column_nombres, $tabble_nombre);

    if ($result != NULL) {
        $response["status"] = "success";
        $response["message"] = "Liquidacion agregada correctamente";
        echoResponse(200, $response);
    } else {
        $response["status"] = "error";
        $response["message"] = "Ha ocurrido un error al tratar de agregar la Liquidacion";
        echoResponse(201, $response);
    }    
});

$app->put('/putLiquidacion/:id', function($id) use($app) {
    $request = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();

    $response = $db->put("liquidacion", array("idliquidacion"=>$id), $request->data);
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

$app->delete('/deleteLiquidacion/:id', function($id) {
    $response = array();
    $db = new DbHandler();

    $response = $db->deleteWhere("liquidacion", array("idliquidacion"=>$id));
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