<?php 

$app->get('/recientes', function() {

    $response = array();
    $db = new DbHandler();
    //$rows = $db->select("membresias","*",array("status"=>"publicado"),"10");
    $rows = $db->selectOrderBy("membresias","*",array("status" => "publicado"),"5",array('idMem' => 'DESC'));
    echoResponse(200, $rows);

});



$app->get('/getDestacadas', function() {
    $response = array();
    $db = new DbHandler();
    $rows = $db->selectOrderBy("destacados","idMem",array(),"5",array('idMem' => 'DESC'));
    echoResponse(200, $rows);
});



$app->get('/getDestinos', function() {
    $response = array();
    $db = new DbHandler();
    $rows = $db->getDestinos();
    echoResponse(200, $rows);
});



$app->post('/relacionadas', function()  use ($app) {
    $response = array();
    $r = json_decode($app->request->getBody());
    $ciudad = $r->ciudad;
    $db = new DbHandler();
    $rows = $db->select("membresias","*",array("ciudad"=>$ciudad,"status"=>"publicado"),"5");
    echoResponse(200, $rows);

});



$app->get('/propiedad/:idMem', function($idMem) {

    $response = array();
    $db = new DbHandler($idMem);
    
    //$rows = $db->getProperty("select * from membresias where idMem='$idMem' and status = 'publicado'");
    $rows = $db->getProperty($idMem);
    if ($rows != NULL) {

      $response['status'] = "success";
      $response['message'] = 'Ha obtenido la información correctamente.';
      $response['data'] = $rows;

    }else {
      $response['status'] = "error";
      $response['message'] = '¡No se ha encontrado una propiedad con los datos proporcionados!';
    }
    echoResponse(200, $response);

});



$app->post('/enviarcontacto',function() use ($app) {
    $response = array();
    $r = json_decode($app->request->getBody());


    $db = new DbHandler();

    $tabble_nombre = "emails_enviados";
    $column_nombres = array('titulo','cuerpo','id_envia','id_recibe','categoria','email_envia','email_recibe','idMem');

    $result = $db->insertIntoTable($r, $column_nombres, $tabble_nombre);

     if ($result != NULL) {


        $result2 = $db->enviaEmail($r);

        $response["status"] = "success";
        $response["message"] = "¡Mail enviado correctamente!";
        $response["uid"] = $result;
        $response["emailenviado"] = $result2;

        echoResponse(200, $response);
    } else {
        $response["status"] = "error";
        $response["message"] = "Ha ocurrido un error al tratar de agregar un comentario a la publicación";
        echoResponse(201, $response);
    }    

});



$app->post('/busqueda', function() use ($app) {

    $response = array();
    $r = json_decode($app->request->getBody());
    $db = new DbHandler();

    $vars = (array) $r->buscador;
    $dataSend = array();

    foreach ($vars as $key => $value) {
        if ($key === 'tipo') {
            if ($value === 'renta') {
                $key = 'renta';
                $value = 1;
            }
            if ($value === 'venta') {
                $key = 'venta';
                $value = 1;
            }
        }
        $dataSend = array_merge($dataSend,array($key=>$value));
    }
    //print_r($dataSend);

    $rows = $db->select("membresias","*",$dataSend,'100');

    if ($rows != NULL) {
        $rows = array_merge($rows,array("total"=>count($rows["data"])));
        echoResponse(200, $rows); 
    }else {
        $response["status"] = "error";
        $response["message"] = "Ha ocurrido un error";
        echoResponse(201, $response);
    }
});



$app->post('/agregacomentario', function() use ($app) {

    $response = array();
    $r = json_decode($app->request->getBody());

    $db = new DbHandler();


    $tabble_nombre = "preguntas";
    $column_nombres = array('idUser','idMem', 'pregunta');


    $result = $db->insertIntoTable($r, $column_nombres, $tabble_nombre);

     if ($result != NULL) {

        $response["status"] = "success";
        $response["message"] = "¡El comentario ha sido agregado!";
        $response["uid"] = $result;
        $response["fecha"] = date('Y-m-d H:s:i');

        echoResponse(200, $response);
    } else {
        $response["status"] = "error";
        $response["message"] = "Ha ocurrido un error al tratar de agregar un comentario a la publicación";
        echoResponse(201, $response);
    }            
});



?>