<?php 

$app->post('/uploadImagen/:id', function($id) use($app) {

    $r = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();

    $file = $_FILES["file"]["name"];
    if(!is_dir("imagenes/"))
        mkdir("imagenes/", 0777);
    if($file && move_uploaded_file($_FILES["file"]["tmp_name"], "imagenes/".$id.$file))
    {
        $response["status"] = "success";
        $response["message"] = "Imagen cargada correctamente";
        $response['src'] = "imagenes/".$id.$file;
        echoResponse(200, $response);
    } else {
        $response['status'] = "error";
        $response['message'] = "La imagen no pudo ser cargada";
        echoResponse(201, $response);
    }

});