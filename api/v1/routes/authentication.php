<?php 

$app->get('/session', function() {
    $db = new DbHandler();
    $session = $db->getSession();
    $response["uid"] = $session['uid'];
    $response["nombre"] = $session['nombre'];
    $response["email"] = $session['email'];
    $response["acceso"] = $session['acceso'];
    $response["iduser"] = $session['iduser'];
    $response["rol_idrol"] = $session['rol_idrol'];
    echoResponse(200, $session);
});

$app->post('/login', function() use ($app) {
    require_once 'passwordHash.php';
    require_once 'jwt_helper.php';
    $r = json_decode($app->request->getBody());
    $r->login = $r;

    verifyRequiredParams(array('email', 'password'),$r->login);

    $response = array();
    $db = new DbHandler();
    $password = $r->login->password;
    $email = $r->login->email;

    $user = $db->getOneRecord("select iduser, nombre, password, email, acceso, rol_idrol from user where email='$email'");
    if ($user != NULL) {
      if(passwordHash::check_password($user['password'],$password)){
        
        // ha hecho login
        $user['iat'] = time();
        $user['exp'] = time() + 2000;
        $jwt = JWT::encode($user, '');

        $acceso = date('Y-m-d H:i:s');
        $token_expire = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $response['status'] = "success";
        $response['message'] = 'Ha accesado correctamente.';
        $response['user']['nombre'] = $user['nombre'];
        $response['user']['iduser'] = $user['iduser'];
        $response['user']['email'] = $user['email'];
        $response['user']['rol_idrol'] = $user['rol_idrol'];
        $response['user']['acceso'] = $acceso;
        $response['token'] = $jwt;
        $response['response_put'] = $db->put("user", array("iduser" => $user['iduser']), array('acceso' => $acceso, 'token' => $jwt, 'token_expire' => $token_expire));
        
        
        $_SESSION['iduser'] = $user['iduser'];
        
        // Guadar Token en Sesión
        $_SESSION['token'] = $jwt;
        $_SESSION['token_expire'] = $token_expire;


      } else {
        $response['status'] = "error";
        $response['message'] = 'Credenciales icnorrectas';
      }
    }else {
      $response['status'] = "error";
      $response['message'] = '¡No se ha encontrado una cuenta con los datos proporcionados!';
    }
    echoResponse(200, $response);
});

$app->post('/signup', function() use ($app) {
    $response = array();
    $r = json_decode($app->request->getBody());

    $r->signup = $r;

    verifyRequiredParams(array('nombre', 'email', 'password', 'rol_idrol'),$r->signup);

    require_once 'passwordHash.php';
    $db = new DbHandler();
    $password = $r->signup->password;
    $nombre = $r->signup->nombre;
    $email = $r->signup->email;
    $rol_idrol = $r->signup->rol_idrol;

    $isUserExists = $db->getOneRecord("select 1 from user where email='$email'");

    if(!$isUserExists){
        $r->signup->password = passwordHash::hash($password);
        $table_nombre = "user";
        $column_nombres = array('nombre', 'email', 'password', 'rol_idrol');

        $result = $db->insertIntoTable($r->signup, $column_nombres, $table_nombre);
        if ($result != NULL) {
            $response["status"] = "success";
            $response["message"] = "¡La cuenta ha sido creada correctamente!";
            $response["uid"] = $result;
            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Ha ocurrido un error al tratar de crear la cuenta. Por favor trate nuevamente";
            echoResponse(201, $response);
        }            
    }else{
        $response["status"] = "error";
        $response["message"] = "¡Un usuario con este email y/ó teléfono ya existe!";
        echoResponse(201, $response);
    }
});

$app->get('/logout', function() {
    $db = new DbHandler();
    $session = $db->destroySession();
    $response["status"] = "info";
    $response["message"] = "Se ha cerrado la sesión correctamente";
    echoResponse(200, $response);
});

$app->get('/getUsers', function() use($app) {
    $response = array();
    $db = new DbHandler();

    $response = $db->select("user", "*", array(), "*");
    echoResponse(200, $response);
});

$app->get('/getUser/:id', function($id) {
    $response = array();
    $db = new DbHandler();
    
    $response = $db->selectUser("user", "*", array("iduser" => $id), "1");
    echoResponse(200, $response);
});

$app->put('/putUser/:id', function($id) use($app)  {
    $request = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();
    
    $response = $db->put("user", array("iduser" => $idUser), $request->data);
    echoResponse(200, $response);
});

$app->delete('/deleteUser/:id', function($id) {
    $response = array();
    $db = new DbHandler();
    
    $response = $db->deleteWhere("user", array("iduser" => $id));
    echoResponse(200, $response);
});



