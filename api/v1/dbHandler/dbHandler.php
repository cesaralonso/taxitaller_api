<?php
require_once("./../libs/clases/clsConfiguracion.php");

class DbHandler extends Configuracion {

    private $conn;
    public $arrayConfig;

    function __construct($idM = null) {
        require_once 'dbConnect.php';
        // opening db connection
        $db = new dbConnect();
        $this->conn = $db->connect();
        $this->arrayConfig = Configuracion::get_config();
    }


    /**
     * Fetching single record
     */
    public function getOneRecord($query) {
        $stmt =  $this->conn->prepare($query.' LIMIT 1');
        $stmt->execute();
        $rows = $stmt->fetch(PDO::FETCH_ASSOC);
        return $rows;
    }

    /**
     * Creating new record
     */
    public function insertIntoTable($obj, $column_names, $table_name) {
        
        $c = (array) $obj;
        $keys = array_keys($c);
        $columns = '';
        $values = '';
        foreach($column_names as $desired_key){ // Check the obj received. If blank insert blank into the array.
           if(!in_array($desired_key, $keys)) {
                $$desired_key = '';
            }else{
                $$desired_key = $c[$desired_key];
            }
            $columns = $columns.$desired_key.',';
            $values = $values."'".$$desired_key."',";
        }

        $stmt =  $this->conn->prepare("INSERT INTO ".$table_name."(".trim($columns,',').") VALUES(".trim($values,',').")");
        $stmt->execute();
        $affected_rows = $stmt->rowCount();
        $lastInsertId = $this->conn->lastInsertId();

        if ($stmt){
            return $lastInsertId;
            } else {
            return NULL;
        }

    }

    public function getSession(){
        if (!isset($_SESSION)) {
            session_start();
        }
        $sess = array();
        if(isset($_SESSION['uid']))
        {
            $sess["uid"] = $_SESSION['uid'];
            $sess["nombre"] = $_SESSION['nombre'];
            $sess["email"] = $_SESSION['email'];
            $sess["acceso"] = $_SESSION['acceso'];
            $sess["rol_idrol"] = $_SESSION['rol_idrol'];
        }
        else
        {
            $sess["uid"] = '';
            $sess["nombre"] = 'Guest';
            $sess["email"] = '';
            $sess["acceso"] = '';
            $sess["rol_idrol"] = 'guest';
        }
        return $sess;
    }

    public function destroySession(){
        if (!isset($_SESSION)) {
        session_start();
        }
        if(isset($_SESSION['uid']))
        {
            unset($_SESSION['uid']);
            unset($_SESSION['nombre']);
            unset($_SESSION['email']);
            unset($_SESSION['acceso']);
            unset($_SESSION['rol_idrol']);
            $info='info';
            if(isSet($_COOKIE[$info]))
            {
                setcookie ($info, '', time() - $cookie_time);
            }
            $msg="Logged Out Successfully...";
        }
        else
        {
            $msg = "Not logged in...";
        }
        return $msg;
    }
 
    public function put($table, $where, $request){
        try{

            $a = array();
            $w = "";
            foreach ($where as $key => $value) {
                $w .= " and " .$key. " = ".$value; 
                $a[":".$key] = $value;
            }

            $set = "";
            foreach ($request as $key => $value) {
                $set .= $key. " = '".$value."'"; 
                $set .= ", ";
            }

            $set = substr($set,0,strlen($set)-2);

            $stmt = $this->conn->prepare("update ".$table." set ".$set." where 1=1 ". $w);
            $stmt->execute();

           // echo "update ".$table." set ".$set." where 1=1 ". $w;
         
            if(!$stmt){
                $response["status"] = "warning";
                $response["message"] = "No se encontró registro para actualizar.";
                $response["data"] = null;

            }else{
                $response["status"] = "success";
                $response["message"] = "Datos actualizados correctamete.";
                $response["data"] = true;
            }

        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = 'Select ha fallado: ' .$e->getMessage();
            $response["data"] = null;
        }
        return $response;
    }


    public function select($table, $columns, $where, $limit){
        try{
            $a = array();
            $w = "";
            foreach ($where as $key => $value) {
                $w .= " and " .$key. " like :".$key; 
                $a[":".$key] = $value;
            }

            if ($limit !== "*") {
                $limit = " LIMIT ".$limit;
            } else {
                $limit = "";
            }

            $stmt = $this->conn->prepare("select ".$columns." from ".$table." where 1=1 ". $w .$limit);
            
            if (count($a) >= 1) {
                $stmt->execute($a);
            } else {
                $stmt->execute();
            }

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(count($rows)<=0){
                $response["status"] = "warning";
                $response["message"] = "No se encontraron datos.";
            }else{
                $response["status"] = "success";
                $response["message"] = "Datos obtenidos de base de datos";
            }
                $response["data"] = $rows;
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = 'Select ha fallado: ' .$e->getMessage();
            $response["data"] = null;
        }
        return $response;
    }


    public function selectOrderBy($table, $columns, $where, $limit, $orderby = null){
        try{
            $a = array();
            $w = "";
            foreach ($where as $key => $value) {
                $w .= " and " .$key. " like :".$key; 
                $a[":".$key] = $value;
            }

            $order = "";
            if ($orderby !== null) {
                $order = " ORDER BY";
                $cont = 0;
                foreach ($orderby as $key => $value) {
                    $order .= " " .$key. " ".$value;
                    $cont ++;
                    $order .= ($cont < count($orderby)) ? "," : "";
                }
            }

            $stmt = $this->conn->prepare("select ".$columns." from ".$table." where 1=1 ". $w . " ".$order." LIMIT ".$limit);
            $stmt->execute($a);

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(count($rows)<=0){
                $response["status"] = "warning";
                $response["message"] = "No se encontraron datos.";
            }else{
                $response["status"] = "success";
                $response["message"] = "Datos obtenidos de base de datos";
            }
                $response["data"] = $rows;
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = 'Select ha fallado: ' .$e->getMessage();
            $response["data"] = null;
        }
        return $response;
    }

    public function selectUser($table, $columns, $where, $limit){
        try{
            $a = array();
            $w = "";
            foreach ($where as $key => $value) {
                
                $w .= " and " .$key. " like :".$key; 

                $a[":".$key] = $value;
            }

            $stmt = $this->conn->prepare("select ".$columns." from ".$table." where 1=1 ". $w . " LIMIT ".$limit." ");
            $stmt->execute($a);

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if(count($rows)<=0){
                $response["status"] = "warning";
                $response["message"] = "No se encontraron datos.";
            }else{
                $response["status"] = "success";
                $response["message"] = "Datos obtenidos de base de datos";
            }
                $response["data"] = $rows;
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = 'Select ha fallado: ' .$e->getMessage();
            $response["data"] = null;
        }
        return $response;
    }

    public function deleteWhere($table, $where){
        try{
            $a = array();
            $w = "";
            foreach ($where as $key => $value) {
                
                $w .= " and " .$key. " like :".$key; 

                $a[":".$key] = $value;
            }

            $stmt = $this->conn->prepare("DELETE FROM $table WHERE 1=1 $w");
            $stmt->execute($a);

            if (!$stmt){
                $response["status"] = "warning";
                $response["message"] = "No se encontraron datos.";
            } else {
                $response["status"] = "success";
                $response["message"] = "Eliminado de la base de datos";
            }

        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = 'Delete ha fallado: ' .$e->getMessage();
            $response["data"] = null;
        }
        return $response;
    }



    public function enviaEmail($obj) {

        $titulo = $obj->titulo;
        $cuerpo = $obj->cuerpo;
        $id_envia = $obj->id_envia;
        $id_recibe = $obj->id_recibe;
        $categoria = $obj->categoria;
        $email_envia = $obj->email_envia;
        // $email_recibe = $obj->email_recibe;
        $email_recibe = "cesar_alonso_m_g@hotmail.com";
        $idMem = $obj->idMem;


        $para = $email_recibe;
        $título = $titulo;
        $mensaje = '
        <html>
        <head>
          <title>'.$titulo.'</title>
        </head>
        <body>'
            .$cuerpo.
        '</body>
        </html>
        ';

        // Para enviar un correo HTML, debe establecerse la cabecera Content-type
        $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
        $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        // Cabeceras adicionales
        $cabeceras .= 'To: <'.$email_recibe.'>' . "\r\n";
        $cabeceras .= 'From: TiempoCompartido <'.$email_envia.'>' . "\r\n";

        // Enviarlo
        if(mail($para, $título, $mensaje, $cabeceras)) $stmt = true;

        if ($stmt){
            return "Si";
            } else {
            return NULL;
        }
    }

    private function normaliza ($cadena){
        $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ.,%$#"!¡?=)(/&';
        $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr             ';
        $cadena = htmlentities(utf8_decode($cadena));
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        $cadena = urlencode(utf8_encode(strtolower($cadena)));
        $cadena = str_replace("++", "+", $cadena);
        $cadena = trim($cadena, '+'); 
        return $cadena;
    }

}

?>
