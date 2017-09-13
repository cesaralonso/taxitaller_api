<?php 

$app->get('/poblateeditMembresia/:idMem', function($idMem) {

    $response = array();
    $db = new DbHandler();

    $result = $db->poblateeditMembresia($idMem);

     if ($result != NULL) {
        echoResponse(200,  $result);
    } else {
        $response["status"] = "error";
        $response["message"] = "Ha ocurrido un error al tratar de listar propiedad del usuario";
        echoResponse(201, $response);
    }            
});




$app->put('/actualizarDatosImagen/:id', function($id) use($app) {

    $r = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();

    $response = $db->put("files",array("id"=>$id),$r->data);

    if ($response['data'] != NULL) {
        echoResponse(200, $response);
    } else {
        echoResponse(201, $response);
    }

});

$app->post('/guardaDatosImagen/:id', function($id) use($app) {

    $r = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();

    $tabble_nombre = "files";
    $column_nombres = array( 'nombre','comentario','idUser','idMem');

    $result = $db->insertIntoTable($r->imagen, $column_nombres, $tabble_nombre);
    if ($result != NULL) {

        $response["status"] = "success";
        $response["message"] = "Datos de imagen guadados correctamente";
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response['message'] = "Los datos de imagen no se han guadardo";
        echoResponse(201, $response);
    }

});


$app->put('/saveUbication/:idMem', function($idMem) use ($app) {
    
    $r = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();

 print_r($r);

    $response = $db->put("jquery_locations",array("idMem"=>$idMem),$r);

    if ($response['data'] != NULL) {
        echoResponse(200, $response);
    } else {
        echoResponse(201, $response);
    }

});

$app->get('/getUbication/:idMem', function($idMem){

    $response = array();
    $db = new DbHandler();

    $tabble_nombre = "jquery_locations";
    $where = array("idMem"=>$idMem);

    $result = $db->selectUbication($tabble_nombre,"*",$where,"1");
    
    if ($result != NULL) {

        $response["status"] = "success";
        $response["message"] = "Ubicación cargada correctamente";
        $response["data"] = $result['data'];
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response['message'] = "La ubicación no se ha cargado correctamente";
        echoResponse(201, $response);
    }

});

$app->get('/getImagenes/:idMem', function($idMem){

    $response = array();
    $db = new DbHandler();

    $tabble_nombre = "files";
    $where = array("idMem"=>$idMem);

    $result = $db->selectImages($tabble_nombre,"*",$where,"10");
    
    if ($result != NULL) {

        $response["status"] = "success";
        $response["message"] = "Imagenes cargadas correctamente";
        $response["data"] = $result['data'];
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response['message'] = "Las imagenes no se han cargadas correctamente";
        echoResponse(201, $response);
    }

});

$app->get('/getImage/:id', function($id){

    $response = array();
    $db = new DbHandler();

    $tabble_nombre = "files";
    $where = array("id"=>$id);

    $result = $db->selectImages($tabble_nombre,"*",$where,"1");
    
    if ($result != NULL) {

        $response["status"] = "success";
        $response["message"] = "Imagen cargada correctamente";
        $response["data"] = $result['data'];
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response['message'] = "La imagene no se ha cargado correctamente";
        echoResponse(201, $response);
    }

});

$app->get('/deleteImage/:id', function($id){


    $response = array();
    $db = new DbHandler();

    $result = $db->deleteImage($id);

     if ($result != NULL) {
        $response["status"] = "success";
        $response["message"] = "¡La imagen ha sido eliminada!";
        $response["uid"] = $result;

        echoResponse(200, $response);
    } else {
        $response["status"] = "error";
        $response["message"] = "Ha ocurrido un error al tratar de eliminar la imagen";
        echoResponse(201, $response);
    }   

});

$app->get('/poblateMembresias/:idUser', function($idUser) {

    $response = array();
    $db = new DbHandler();

    $result = $db->poblateMembresias($idUser);

     if ($result != NULL) {
        echoResponse(200,  $result);
    } else {
        $response["status"] = "error";
        $response["message"] = "Ha ocurrido un error al tratar de listar propiedades del usuario";
        echoResponse(201, $response);
    }            
});

$app->post('/addMembresia', function() use ($app) {

    $response = array();
    $requests = json_decode($app->request->getBody());

    $db = new DbHandler();

    $result = $db->addMembresia($requests);

     if ($result != NULL) {
        $response["status"] = "success";
        $response["message"] = "¡La propiedad ha sido agregada!";
        $response["uid"] = $result;

        echoResponse(200, $response);
    } else {
        $response["status"] = "error";
        $response["message"] = "Ha ocurrido un error al tratar de agregar una propiedad";
        echoResponse(201, $response);
    }            
});

$app->post('/editMembresia/:idMem', function($idMem) use ($app) {

    $response = array();
    $requests = json_decode($app->request->getBody());

    $db = new DbHandler();

    //print_r($requests);

    $result = $db->editMembresia($requests,$idMem);

     if ($result != NULL) {
        $response["status"] = "success";
        $response["message"] = "¡La propiedad ha sido actualizada!";
        $response["uid"] = $result;

        echoResponse(200, $response);
    } else {
        $response["status"] = "error";
        $response["message"] = "Ha ocurrido un error al tratar de actualizar una propiedad";
        echoResponse(201, $response);
    }            
});

$app->post('/deleteMembresia/:idMem', function($idMem) {

    $response = array();
    $db = new DbHandler();

    $result = $db->deleteMembresia($idMem);

     if ($result != NULL) {
        $response["status"] = "success";
        $response["message"] = "¡La propiedad ha sido eliminada!";
        $response["uid"] = $result;

        echoResponse(200, $response);
    } else {
        $response["status"] = "error";
        $response["message"] = "Ha ocurrido un error al tratar de eliminar la propiedad";
        echoResponse(201, $response);
    }            
});


?>



