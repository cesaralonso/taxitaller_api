<?php 

  $app->post('/postContacto', function() use ($app) {

      $response = array();
      $db = new DbHandler();
      $requests = json_decode($app->request->getBody());
      
      $result = $db->postContacto($requests);
      
      if ($result != NULL && $result != false) {
        $response['status'] = "success";
        $response['message'] = 'Ha enviado un mensaje a Tiempo Compartido correctamente.';
      }else {
        $response['status'] = "error";
        $response['message'] = '¡Ha ocurrido un error al tratar de enviar un mensaje a Tiempo Compartido!';
      }
      echoResponse(200, $response);

  });



  $app->get('/publicidades', function() {

      $response = array();
      $db = new DbHandler();
      
      $result = $db->selectPublicidad();
      
      if ($result != NULL && $result != false) {
        echoResponse(200, $result);
      }else {
        echoResponse(201, $result);
      }

  });

  $app->get('/promociones', function() {

      $response = array();
      $db = new DbHandler();
      
      $result = $db->selectPromociones();
      
      if ($result != NULL && $result != false) {
        echoResponse(200, $result);
      }else {
        echoResponse(201, $result);
      }

  });

  $app->get('/getPromocion/:idPro', function($idPro) {

      $response = array();
      $db = new DbHandler();
      
      $result = $db->getPromocion($idPro);
      
      if ($result != NULL && $result != false) {
        echoResponse(200, $result);
      }else {
        echoResponse(201, $result);
      }

  });


  $app->get('/getPublicidad/:idPub', function($idPub) {

      $response = array();
      $db = new DbHandler();
      
      $result = $db->getPublicidad($idPub);
      
      if ($result != NULL && $result != false) {
        echoResponse(200, $result);
      }else {
        echoResponse(201, $result);
      }

  });






  $app->get('/getEncuesta/:id', function($id) {

      $response = array();
      $db = new DbHandler();
      
      $result = $db->getEncuesta($id);
      
      if ($result != NULL && $result != false) {
        echoResponse(200, $result);
      }else {
        echoResponse(201, $result);
      }

  });




 ?>
