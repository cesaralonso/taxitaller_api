<?php
require_once("./../libs/clases/clsConfiguracion.php");

class DbHandler extends Configuracion {

    private $conn;


    public $idMem;
    public $array_membresia;
    public $membresia;
    public $arrayConfig;
    
    public $array_clubes;
    public $array_paises;
    public $array_ciudades;
    
    public $array_paginacion;
    public $paginacion_rango;


    function __construct($idM = null) {
        require_once 'dbConnect.php';
        // opening db connection
        $db = new dbConnect();
        $this->conn = $db->connect();


        $this->idMem = $idM;
        $this->array_membresia = array();
        $this->array_paginacion = array();
        $this->membresia = false;
        $this->paginacion_rango = "";
        $this->arrayConfig = Configuracion::get_config();

    }


    public function get_usuario($idUser = null)
    {   
        $usuario = "";

        $stmt =  $this->conn->prepare("SELECT * FROM  `web_users` WHERE  `id` = ".$idUser);
        $stmt->execute();
        $affected_rows = $stmt->rowCount();
       
        if($affected_rows>=1){

        $r = $stmt->fetch(PDO::FETCH_ASSOC);


            $usuario =  array(
                                "id"            => $r['id'],
                                "user"          => $r['user'],
                                "email"         => $r['email'],
                                "telefono"      => ($r['telefono']!="0")?$r['telefono']:"No especificado",      
                                "celular"       => ($r['celular']!="0")?$r['celular']:"No especificado",        
                                "nombre"        => htmlspecialchars(($r['nombre']!="")?utf8_decode($r['nombre']):""),       
                                "apellidos"     => htmlspecialchars(($r['apellidos']!="")?utf8_decode($r['apellidos']):"No especificado"),      
                                "pais"          => htmlspecialchars(($r['pais']!="")?$r['pais']:"No especificado"),     
                                "estado"        => htmlspecialchars(($r['estado']!="")?$r['estado']:"No especificado"),     
                                "ciudad"        => htmlspecialchars(($r['ciudad']!="")?$r['ciudad']:"No especificado"),     
                                "lenguajes"     => htmlspecialchars(($r['lenguajes']!="")?utf8_decode($r['lenguajes']):"No especificado"),      
                                "status"        => ($r['status']!="")?$r['status']:"No especificado",       
                                "acceso"        => ($r['acceso']!="")?$r['acceso']:"No especificado",       
                                "tipo"          => ($r['tipo']!="")?$r['tipo']:"No especificado",       
                                "fecha"         => ($r['fecha']!="")?$r['fecha']:"No especificada",  
                                'perfil'        => ($r['perfil']=="1")? true: false,     
                                'informacion'   => htmlspecialchars($r['informacion']),
                                'destinos'      => htmlspecialchars($r['destinos']),
                                "tipo_usuario"  => ($r['tipo_usuario']=="nopropietario")?"No propietario":($r['tipo_usuario']=="propietario")?"Propietario":"No especificado");         

        
        }
        return $usuario;
    }



  public function enlace_des($tipo,$renta,$venta,$intercambio,$ciudad,$pais,$club){
        $enlace = "Tiempo Compartido en ".(($renta=="true")?"Renta":"").((($renta=="true") && ($venta=="true"))?" / ":"").(($venta=="true")?"Venta":"").(((($renta=="true") || ($venta=="true")) && $intercambio=="true")?" / ":"").(($intercambio=="true")?"Intercambio":"").", ".$ciudad.", ".$pais.", ".$club;
        return htmlspecialchars($enlace);
    }
    
   public function enlace($tipo,$renta,$venta,$intercambio,$ciudad,$pais,$club,$id){
        $enlace = $this->enlace_des($tipo,$renta,$venta,$intercambio,$ciudad,$pais,$club);
        return htmlspecialchars(strtolower(str_replace("--","-",str_replace("/","",str_replace(",","",str_replace(" ","-",$enlace)))))."/"); 
    }



  public function get_membresia($idMem = null)
  { 
        $idMem = (!isset($idMem)) ? $this->idMem : $idMem;

        $stmt =  $this->conn->prepare("SELECT * FROM  `membresias` WHERE  `idMem` = ".$idMem."");
        $stmt->execute();
        $affected_rows = $stmt->rowCount();

        $r = $stmt->fetch(PDO::FETCH_ASSOC);


        if($affected_rows>=1){
        unset($this->array_membresia);
        unset($arr_membresia);

        /* CARACTERÍSTAICAS */
        $caracteristicas = explode("|",$r['caracteristicas']);
        
        $caracte = "";
        foreach ($caracteristicas as $item => $valor){
            
            if($valor!=""){
                if ($valor!="_"){
                    $caracte[] = ucwords($valor);
                }
            }
        }
        /*FIN  CARACTERÍSTAICAS */



        $arr_membresia =  array(
                                "idMem"         => $r['idMem'],
                                "enlace"        => $this->enlace("Tiempo Compartido", (($r['renta']==1) ? "true" : "false"),(($r['venta']==1) ? "true" : "false"),(($r['intercambio']==1) ? "true" : "false"),$r["ciudad"],$r["pais"],$r["club"],$r["idMem"]),
                                "enlace_des" => $this->enlace_des("Tiempo Compartido",(($r['renta']==1) ? "true" : "false"),(($r['venta']==1) ? "true" : "false"),(($r['intercambio']==1) ? "true" : "false"),$r["ciudad"],$r["pais"],$r["club"]),
                                "club"          => $r['club'],
                                "informacion"   => htmlspecialchars(utf8_decode($r['info_adicional'])),
                                "dormitorios"   => $r['dormitorios'],
                                "cap_max"       => $r['cap_max'],
                                "ciudad"        => $r['ciudad'],
                                "pais"          => htmlspecialchars($r['pais']),
                                "venta"         => ($r['venta']==1) ? true : false,
                                "renta"         => ($r['renta']==1) ? true : false,
                                "intercambio"   => ($r['intercambio']==1) ? true : false,
                                "precio_venta"  => $r['precio_venta'],
                                "precio_renta"  => $r['precio_renta'],
                                "moneda_venta"  => $r['moneda_venta'],
                                "moneda_renta"  => $r['moneda_renta'],
                                "destino_inter" => $r['destino_inter'],             
                                "imagen"        => $this->get_img($r['idMem'],($this->idMem != "")? "" : 1),
                                'estado'            => $r['estado'],
                                'afiliado'      => $r['afiliado'],
                                'caracteristicas' => $caracte,
                                'info_adicional_ingles' => htmlspecialchars(utf8_decode($r['info_adicional_ingles'])),
                                'url'           => $r['url'],
                                'tipo_semana_bool'   => ($r['tipo_semana']!=="")? true : false,
                                'tipo_semana'   => $r['tipo_semana'],
                                'tipo_unidad'   => $r['tipo_unidad'],
                                'tipo_unidad_ing' => $r['tipo_unidad_ing'],
                                'lock_off'      => $r['lock_off'],
                                'sala'          => $r['sala'],
                                'banos'             => $r['banos'],
                                'tipo_cocina'   => $r['tipo_cocina'],
                                'cap_privacidad'    => $r['cap_privacidad'],
                                'res_num_sem'   => $r['res_num_sem'],
                                'res_freq_sem'  => $r['res_freq_sem'],
                                'importe_compra'    => $r['importe_compra'],
                                'ocultar_importe' => $r['ocultar_importe'],
                                'fecha_compra'  => $r['fecha_compra'],
                                'ocultar_fecha'     => $r['ocultar_fecha'],
                                'caducidad_compra' => $r['caducidad_compra'],
                                'sin_caducidad'     => $r['sin_caducidad'],
                                'anos_restantes'    => $r['anos_restantes'],
                                'importe_mantenimiento' => $r['importe_mantenimiento'],
                                'entrada_renta'     => $r['entrada_renta'],
                                'salida_renta'  => $r['salida_renta'],
                                'ubicacion'         => $r['ubicacion'],
                                'capacidad_inter' => $r['capacidad_inter'],
                                'diferencia_inter' => $r['diferencia_inter'],
                                'status'            => $r['status'],
                                'tel_contacto'  => $r['tel_contacto'],
                                'fecha'             => $r['fecha'],
                                'fecha_actualizacion' => $r['fecha_actualizacion'],
                                'especial'      => $r['especial'],
                                'num_pais'      => $r['num_pais'],
                                'num_estado'        => $r['num_estado'],
                                'moneda_cuota'  => $r['moneda_cuota'],
                                'fija_datos'        => $r['fija_datos'],
                                'puntos_datos'  => $r['puntos_datos'],
                                'noches_datos'  => $r['noches_datos'],
                                'flotante_datos'    => $r['flotante_datos'],
                                'destacar'      => $r['destacar'],
                                'fecha_destacado' => $r['fecha_destacado'],
                                'hasta_inter'   => $r['hasta_inter'],
                                'precio_neg_renta' => $r['precio_neg_renta'],
                                'precio_neg_venta' => ($r['precio_neg_venta']=="selecciona")?"":$r['precio_neg_venta'],
                                'usuario'       => $this->get_usuario($r['idUser']),
                                'comentarios'   => $this->get_comentarios($r['idMem']),
                                'googlemaps'   => $this->get_location($r['idMem'])
                                );
              return $arr_membresia;
        }
    }
    

    public function get_valores(){
                
    $qry = mysql_query("SELECT * FROM membresias WHERE status='publicado'");
        $cnt_mem = mysql_num_rows($qry); 
    
        if($cnt_mem>=1){
            while($r=mysql_fetch_array($qry)){
                $arr_clubes['clubes'][] =  array("club" => ucwords($r['club']));
                $array_paises['paises'][] =  array("pais" => ucwords($r['pais']));      
                $array_ciudades['ciudades'][] =  array("ciudad" => ucwords($r['ciudad']));              
            }

            $this->array_clubes = $arr_clubes;
            $this->array_paises = $array_paises;
            $this->array_ciudades = $array_ciudades;

        }
    
    }

        
    public function get_busqueda_resultados($porpagina){

        $valores = array(
            "pagina" => @$_POST['pagina'],
            "club" => @$_POST['club'],
            "pais" => @$_POST['pais'],
            "ciudad" => @$_POST['ciudad'],
            "renta" => (@$_POST['renta']=='on')? 1: "",
            "venta" => (@$_POST['venta']=='on')? 1: "",
            "intercambio" => (@$_POST['intercambio']=='on')? 1: "",
            "moneda_venta" => (@$_POST['venta']=='on')? @$_POST['moneda']: "",
            "moneda_renta" => (@$_POST['renta']=='on')? @$_POST['moneda']: "",
            "precio_min" => @$_POST['precio_min'],
            "precio_max" => @$_POST['precio_max'],
            "pers_max" => @$_POST['pers_max'],
            "cuartos" => @$_POST['cuartos'],
            "banos" => @$_POST['banos']
        );
        
        
        echo"<pre>";
        print_r($valores);
        echo"</pre>";
        /**/
        
        $string = "";
        foreach ($valores as $valor => $item){
            if($item!="" && $valor!="pagina"){
                $string .= $valor ." LIKE %'".$item."'% AND ";
            }
        }

        if($string == ""){
            $string .= " 1 == 1";
        }
        
        $qry_cnt = mysql_query("SELECT * FROM membresias WHERE ".$string." status='publicado'");
        $qry = mysql_query("SELECT * FROM membresias WHERE ".$string." status='publicado' ORDER BY fecha DESC LIMIT ".($valores["pagina"]-1)*$porpagina.",".$valores["pagina"]*$porpagina);

        $this->paginacion_rango = ($valores["pagina"]-1)*$porpagina." de ".$valores["pagina"]*$porpagina;
        
        @$cnt = mysql_num_rows($qry_cnt); 
        @$cnt_mem = mysql_num_rows($qry); 
    
        if($cnt_mem>=1){
            while($r=mysql_fetch_array($qry)){
                $arr_membresia['membresias'][] =  array("membresia" => $this->get_membresia($r['idMem']));          
            }

        $this->array_membresia  = $arr_membresia;

        }
                    
        $paginas = $cnt/$porpagina; 
        for($i=1;$i<=$paginas+1;$i++){
            $array_paginacion['paginas'][] = array("pagina"=>$i);   
        }
        
        $this->array_paginacion = @$array_paginacion;
    }

    
    public function get_especiales($especial,$pais=null,$ciudad=null){
        switch ($especial){
            case "destacadas":
            
                $hoy=date("Y-m-d"); 
                //d.fecha_inicio <= '".$hoy."'
                //AND  d.fecha_fin >= '".$hoy."'
                                    
                $sql = "SELECT *
                                FROM destacados AS d
                                INNER JOIN membresias AS m ON m.idMem = d.idMem
                                WHERE
                                m.status='publicado'
                                ORDER BY d.fecha_fin DESC";
            
            break;
            case "mas_visitadas":
            
                $sql = "SELECT * FROM membresias, visitas WHERE membresias.idMem = visitas.idMem AND status='publicado' ORDER BY visitas.cont DESC LIMIT 0,5";
            
            break;
            case "relacionadas":
        
                $sql = "SELECT * FROM membresias WHERE ciudad='".$ciudad."' OR  pais ='".$pais."' AND status='publicado' ORDER BY fecha DESC LIMIT 0,5";
                $this->idMem = "";
            break;
            default:
                exit;
            break;
            
        }
            
        $qry =  mysql_query($sql);
        $cnt_mem = mysql_num_rows($qry); 

        if($cnt_mem>=1){
            while($r=mysql_fetch_array($qry)){
                $arr_membresia['membresias'][] =  array("membresia" => $this->get_membresia($r['idMem']));          
            }

            $this->array_membresia = $arr_membresia;
            $this->membresia = true;

            return $arr_membresia;
        }
    }
    
        
    public function get_categoria($categoria){
    $qry = mysql_query("SELECT * FROM membresias WHERE ".$categoria." = 1 AND status='publicado' ORDER BY fecha  LIMIT 0,3");
        $cnt_mem = mysql_num_rows($qry); 
    
        if($cnt_mem>=1){
            while($r=mysql_fetch_array($qry)){
                $arr_membresia['membresias'][] =  array("membresia" => $this->get_membresia($r['idMem']));          
            }

        $this->array_membresia = $arr_membresia;
        $this->membresia = true;    
        }
    }


    public function get_membresias_relacionadas($pais=null,$ciudad=null){
    $qry =  mysql_query("SELECT * FROM membresias WHERE ciudad='".$ciudad."' OR  pais ='".$pais."' AND status='publicado' ORDER BY fecha DESC LIMIT 0,5");
        $cnt_mem = mysql_num_rows($qry); 
    
        if($cnt_mem>=1){
            while($r=mysql_fetch_array($qry)){
                $arr_membresia['membresias'][] =  array("membresia" => $this->get_membresia($r['idMem']));  
            }

        $this->array_membresia =$arr_membresia;
        $this->membresia = true;    
            
        }
    }
    
    
    public function get_img($id,  $limit = null){
        if ($limit){
            $stmt =  $this->conn->prepare("SELECT nombre, comentario FROM files WHERE idMem = '".$id."' LIMIT 0,".$limit."");            
        } else {
            $stmt =  $this->conn->prepare("SELECT nombre, comentario FROM files WHERE idMem = '".$id."'");       
        }
        $stmt->execute();
        $affected_rows = $stmt->rowCount();


        // si hay foto
        if($affected_rows>1){
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($rows as $key => $value){
                $arrImagen[] = array("src"=> $rows[$key]["nombre"], "descripcion" => $rows[$key]["comentario"],"dirImgs"=>$this->arrayConfig['dirImgs'], "dirImgsThumbs"=>$this->arrayConfig['dirImgsThumbs'], "dirImgs60"=>$this->arrayConfig['dirImgs60'], "cont"=>$key);  
            }
        } else if($affected_rows==1){
            $f = $stmt->fetch(PDO::FETCH_ASSOC); 
            $arrImagen[] = array("src"=> $f['nombre'], "descripcion" => $f['comentario'],"dirImgs"=>$this->arrayConfig['dirImgs'], "dirImgsThumbs"=>$this->arrayConfig['dirImgsThumbs'], "dirImgs60"=>$this->arrayConfig['dirImgs60'], "cont"=>0);    
                
        } else if($affected_rows==0){
            $arrImagen[] = array("src"=>'sin_foto_m.jpg', "descripcion" => 'No hay imagen para esta membresia', "dirImgsThumbs"=>$this->arrayConfig['dirImgsThumbs']);             
        } 
        
        return $arrImagen;
    }


    public function get_comentarios($id){
        $nuevo_arreglo = array();
        $stmt =  $this->conn->prepare("SELECT web_users.user, preguntas.pregunta, preguntas.fecha, preguntas.idPreg 
                                        FROM preguntas 
                                        INNER JOIN web_users ON web_users.id = preguntas.idUser 
                                        WHERE preguntas.idMem = ".$id." ORDER BY preguntas.idPreg DESC");   

        $stmt->execute();
        $affected_rows = $stmt->rowCount();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $key => $value){
            $nuevo_arreglo[] = array("user"=>$rows[$key]["user"],
                                    "idResp"=>"",
                                    "idPreg"=>$rows[$key]["idPreg"],
                                    "fecha"=>$rows[$key]["fecha"],
                                    "idMem"=>"",
                                    "status"=>"",
                                    "texto"=>utf8_decode($rows[$key]["pregunta"]),
                                    "lpreg"=>true,
                                    "lresp"=>false);

            $stmt2 =  $this->conn->prepare("SELECT web_users.user,respuestas.idResp,respuestas.idPreg,respuestas.fecha,respuestas.idMem,respuestas.status,respuestas.respuesta 
                                            FROM respuestas 
                                            INNER JOIN web_users ON web_users.id = respuestas.idUser
                                            WHERE idPreg = '".$rows[$key]['idPreg']."' ORDER BY fecha DESC");   
            $stmt2->execute();
            $affected_rows2 = $stmt2->rowCount();
            $rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            foreach($rows2 as $key2 => $value2){
                $nuevo_arreglo[] = array("user"=>$rows2[$key2]["user"],
                                        "idResp"=>$rows2[$key2]["idResp"],
                                        "idPreg"=>$rows2[$key2]["idPreg"],
                                        "fecha"=>$rows2[$key2]["fecha"],
                                        "idMem"=>$rows2[$key2]["idMem"],
                                        "status"=>$rows2[$key2]["user"],
                                        "texto"=>utf8_decode($rows2[$key2]["respuesta"]),
                                        "lpreg"=>false,
                                        "lresp"=>true);
            }
        }
        return $nuevo_arreglo;
    }


    public function get_location($id){
        $nuevo_arreglo = array();
        $stmt =  $this->conn->prepare("SELECT latitude, longitude  
                                        FROM jquery_locations
                                        WHERE idMem = ".$id);   
        $stmt->execute();
        $affected_rows = $stmt->rowCount();
        if ($affected_rows>=1){
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($rows as $key => $value){
                $nuevo_arreglo = array("latitude"=>$rows[$key]["latitude"],
                                        "longitude"=>$rows[$key]["longitude"],
                                        "coordenadas"=>true);
            }
        } else {
            $nuevo_arreglo = array("coordenadas"=>false);
        }
        return $nuevo_arreglo;
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
        }
        else
        {
            $sess["uid"] = '';
            $sess["nombre"] = 'Guest';
            $sess["email"] = '';
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
 

    public function getProperty($idMem) {

        return $this->get_membresia($idMem);
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


    public function selectUbication($table, $columns, $where, $limit){
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


    public function selectImages($table, $columns, $where, $limit){
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


    public function select($table, $columns, $where, $limit){
        try{
            $a = array();
            $w = "";
            foreach ($where as $key => $value) {
                
                $w .= " and " .$key. " like :".$key; 

                $a[":".$key] = $value;
            }

            $stmt = $this->conn->prepare("select ".$columns." from ".$table." where 1=1 ". $w . " LIMIT ".$limit." ");
            
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
                foreach($rows as $key => $value){
                    $arr_membresia[] =  $this->get_membresia($rows[$key]['idMem']);    
                }
                $this->array_membresia = $arr_membresia;

                $response["status"] = "success";
                $response["message"] = "Datos obtenidos de base de datos";
            }
                $response["data"] = $this->array_membresia;
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

            foreach($rows as $key => $value){
                $arr_membresia[] =  $this->get_membresia($rows[$key]['idMem']);    
            }
            $this->array_membresia = $arr_membresia;


            if(count($rows)<=0){
                $response["status"] = "warning";
                $response["message"] = "No se encontraron datos.";
            }else{
                $response["status"] = "success";
                $response["message"] = "Datos obtenidos de base de datos";
            }
                $response["data"] = $this->array_membresia;
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






    public function getDestinos(){ 

        try{

            $stmt = $this->conn->prepare("select destino from `destinos` order by pais ASC");
            $stmt->execute();

            $rows = $stmt->fetchAll(PDO::FETCH_NUM);

            $result = array();

            if(count($rows)<=0){
                $response["status"] = "warning";
                $response["message"] = "No se encontraron datos.";
            }else{

                foreach ($rows as $row) {
                    array_push($result, $row[0]);
                }

                $response["status"] = "success";
                $response["message"] = "Datos obtenidos de base de datos";
            }
                $response["data"] = $result;
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = 'Select ha fallado: ' .$e->getMessage();
            $response["data"] = null;
        }
        return $response;

    } 




    public function getDestacadas(){ 

        try{

            $stmt = $this->conn->prepare("select * from `destacados`");
            $stmt->execute();

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach($rows as $key => $value){
                $arr_membresia[] =  $this->get_membresia($rows[$key]['idMem']);    
            }
            $this->array_membresia = $arr_membresia;

            if(count($rows)<=0){
                $response["status"] = "warning";
                $response["message"] = "No se encontraron datos.";
            }else{
                $response["status"] = "success";
                $response["message"] = "Datos obtenidos de base de datos";
            }
                $response["data"] = $this->array_membresia;
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = 'Select ha fallado: ' .$e->getMessage();
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


/* MEMBRESIAS */


    public function addMembresia($obj) {

        try{
            $obj = $obj->membresia;
            foreach($obj AS $key => $value) {
                $obj->$key = mysql_real_escape_string($value);
            }
            $sql = "INSERT INTO `membresias` ( `titulo` ,  `club` ,  `pais` ,  `estado` ,  `ciudad` ,  `afiliado` ,  `caracteristicas` ,  `info_adicional` ,  `info_adicional_ingles` ,  `url` ,  `tipo_semana` ,  `tipo_unidad` ,  `tipo_unidad_ing` ,  `lock_off` ,  `sala` ,  `dormitorios` ,  `banos` ,  `tipo_cocina` ,  `cap_privacidad` ,  `cap_max` ,  `res_num_sem` ,  `res_freq_sem` ,  `venta` ,  `renta` ,  `intercambio` ,  `importe_compra` ,  `ocultar_importe` ,  `fecha_compra` ,  `ocultar_fecha` ,  `caducidad_compra` ,  `sin_caducidad` ,  `anos_restantes` ,  `importe_mantenimiento` ,  `entrada_renta` ,  `salida_renta` ,  `precio_renta` ,  `precio_venta` ,  `ubicacion` ,  `capacidad_inter` ,  `destino_inter` ,  `diferencia_inter` ,  `idUser` ,  `moneda_renta` ,  `moneda_venta` ,  `status` ,  `tel_contacto` ,  `fecha` ,  `fecha_actualizacion` ,  `especial` ,  `num_pais` ,  `num_estado` ,  `moneda_cuota` ,  `fija_datos` ,  `puntos_datos` ,  `noches_datos` ,  `flotante_datos` ,  `destacar` ,  `fecha_destacado`  ) 
            VALUES(  '{$obj->titulo}' ,  '{$obj->club}' ,  '{$obj->pais}' ,  '{$obj->estado}' ,  '{$obj->ciudad}' ,  '{$obj->afiliado}' ,  '{$obj->caracteristicas}' ,  '{$obj->info_adicional}' ,  '{$obj->info_adicional_ingles}' ,  '{$obj->url}' ,  '{$obj->tipo_semana}' ,  '{$obj->tipo_unidad}' ,  '{$obj->tipo_unidad_ing}' ,  '{$obj->lock_off}' ,  '{$obj->sala}' ,  '{$obj->dormitorios}' ,  '{$obj->banos}' ,  '{$obj->tipo_cocina}' ,  '{$obj->cap_privacidad}' ,  '{$obj->cap_max}' ,  '{$obj->res_num_sem}' ,  '{$obj->res_freq_sem}' ,  '{$obj->venta}' ,  '{$obj->renta}' ,  '{$obj->intercambio}' ,  '{$obj->importe_compra}' ,  '{$obj->ocultar_importe}' ,  '{$obj->fecha_compra}' ,  '{$obj->ocultar_fecha}' ,  '{$obj->caducidad_compra}' ,  '{$obj->sin_caducidad}' ,  '{$obj->anos_restantes}' ,  '{$obj->importe_mantenimiento}' ,  '{$obj->entrada_renta}' ,  '{$obj->salida_renta}' ,  '{$obj->precio_renta}' ,  '{$obj->precio_venta}' ,  '{$obj->ubicacion}' ,  '{$obj->capacidad_inter}' ,  '{$obj->destino_inter}' ,  '{$obj->diferencia_inter}' ,  '{$obj->idUser}' ,  '{$obj->moneda_renta}' ,  '{$obj->moneda_venta}' ,  '{$obj->status}' ,  '{$obj->tel_contacto}' ,  '{$obj->fecha}' ,  '{$obj->fecha_actualizacion}' ,  '{$obj->especial}' ,  '{$obj->num_pais}' ,  '{$obj->num_estado}' ,  '{$obj->moneda_cuota}' ,  '{$obj->fija_datos}' ,  '{$obj->puntos_datos}' ,  '{$obj->noches_datos}' ,  '{$obj->flotante_datos}' ,  '{$obj->destacar}' ,  '{$obj->fecha_destacado}'   ) ";

            $stmt =  $this->conn->prepare($sql);
            $stmt->execute();
            $lastInsertId = $this->conn->lastInsertId();

            if($stmt){
                return $lastInsertId;
            } else {
                return NULL;
            }
        }catch(PDOException $e){
            return NULL;
        }
    }



    public function poblateeditMembresia($idMem) {

        try{

            if (isset($idMem) ) { 


                $sql = "SELECT * FROM `membresias` as m INNER JOIN `jquery_locations` as g ON g.idMem = m.idMem WHERE m.idMem = $idMem";


                $stmt =  $this->conn->prepare($sql);
                $stmt->execute();

                $affected_rows = $stmt->rowCount();
                $rows = $stmt->fetch(PDO::FETCH_ASSOC);
                
           /*
                foreach($rows as $key => $value){
                    $arr_membresia[] =  $this->get_membresia($rows['idMem']);    
                }
                $this->array_membresia = $arr_membresia;
            */

                $this->array_membresia[] = $rows;

                if($affected_rows<=0){
                    $response["status"] = "warning";
                    $response["message"] = "No se encontraron datos.";
                }else{
                    $response["status"] = "success";
                    $response["message"] = "Datos obtenidos de base de datos";
                    $response["data"] = $this->array_membresia;
                }
                return $response;

            }

        }catch(PDOException $e){
  
            $response["status"] = "error";
            $response["message"] = 'poblateeditMembresia ha fallado: ' .$e->getMessage();
            $response["data"] = null;
        }

    }



    public function poblateMembresias($idUser) {

        try{

            if (isset($idUser) ) { 

               $sql = "SELECT * FROM `membresias` WHERE idUser = $idUser";

                // $sql = "SELECT * FROM `membresias` as m INNER JOIN `jquery_locations` as g ON g.idMem = m.idMem WHERE m.idUser = $idUser";


                $stmt =  $this->conn->prepare($sql);
                $stmt->execute();

                $affected_rows = $stmt->rowCount();

                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            
                $arr_membresia = array();
                foreach($rows as $key => $value){
                    $arr_membresia[] =  $this->get_membresia($rows[$key]['idMem']);    
                }
                /*$this->array_membresia = $arr_membresia;*/
         

                $this->array_membresia = $arr_membresia;

                if($affected_rows<=0){
                    $response["status"] = "warning";
                    $response["message"] = "No se encontraron datos.";
                }else{
                    $response["status"] = "success";
                    $response["message"] = "Datos obtenidos de base de datos";
                    $response["data"] = $this->array_membresia;
                }
                return $response;

            }

        }catch(PDOException $e){
  
            $response["status"] = "error";
            $response["message"] = 'poblateMembresia ha fallado: ' .$e->getMessage();
            $response["data"] = null;
        }

    }




    public function editMembresia($obj,$idMem) {

        try{

            if (isset($idMem) ) { 
                $obj = $obj->membresia;
                $set = "";
                foreach($obj AS $key => $value) {
                    $obj->$key = utf8_encode(mysql_real_escape_string($value));

                    $set .= "`".$key."` =  '".$value."', ";
                }

                $set = substr($set, 0, -2) ;

                //$sql = "UPDATE `membresias` SET  `titulo` =  '{$obj->titulo}' ,  `club` =  '{$obj->club}' ,  `pais` =  '{$obj->pais}' ,  `estado` =  '{$obj->estado}' ,  `ciudad` =  '{$obj->ciudad}' ,  `afiliado` =  '{$obj->afiliado}' ,  `caracteristicas` =  '{$obj->caracteristicas}' ,  `info_adicional` =  '{$obj->info_adicional}' ,  `info_adicional_ingles` =  '{$obj->info_adicional_ingles}' ,  `url` =  '{$obj->url}' ,  `tipo_semana` =  '{$obj->tipo_semana}' ,  `tipo_unidad` =  '{$obj->tipo_unidad}' ,  `tipo_unidad_ing` =  '{$obj->tipo_unidad_ing}' ,  `lock_off` =  '{$obj->lock_off}' ,  `sala` =  '{$obj->sala}' ,  `dormitorios` =  '{$obj->dormitorios}' ,  `banos` =  '{$obj->banos}' ,  `tipo_cocina` =  '{$obj->tipo_cocina}' ,  `cap_privacidad` =  '{$obj->cap_privacidad}' ,  `cap_max` =  '{$obj->cap_max}' ,  `res_num_sem` =  '{$obj->res_num_sem}' ,  `res_freq_sem` =  '{$obj->res_freq_sem}' ,  `venta` =  '{$obj->venta}' ,  `renta` =  '{$obj->renta}' ,  `intercambio` =  '{$obj->intercambio}' ,  `importe_compra` =  '{$obj->importe_compra}' ,  `ocultar_importe` =  '{$obj->ocultar_importe}' ,  `fecha_compra` =  '{$obj->fecha_compra}' ,  `ocultar_fecha` =  '{$obj->ocultar_fecha}' ,  `caducidad_compra` =  '{$obj->caducidad_compra}' ,  `sin_caducidad` =  '{$obj->sin_caducidad}' ,  `anos_restantes` =  '{$obj->anos_restantes}' ,  `importe_mantenimiento` =  '{$obj->importe_mantenimiento}' ,  `entrada_renta` =  '{$obj->entrada_renta}' ,  `salida_renta` =  '{$obj->salida_renta}' ,  `precio_renta` =  '{$obj->precio_renta}' ,  `precio_venta` =  '{$obj->precio_venta}' ,  `ubicacion` =  '{$obj->ubicacion}' ,  `capacidad_inter` =  '{$obj->capacidad_inter}' ,  `destino_inter` =  '{$obj->destino_inter}' ,  `diferencia_inter` =  '{$obj->diferencia_inter}' ,  `idUser` =  '{$obj->idUser}' ,  `moneda_renta` =  '{$obj->moneda_renta}' ,  `moneda_venta` =  '{$obj->moneda_venta}' ,  `status` =  '{$obj->status}' ,  `tel_contacto` =  '{$obj->tel_contacto}' ,  `fecha` =  '{$obj->fecha}' ,  `fecha_actualizacion` =  '{$obj->fecha_actualizacion}' ,  `especial` =  '{$obj->especial}' ,  `num_pais` =  '{$obj->num_pais}' ,  `num_estado` =  '{$obj->num_estado}' ,  `moneda_cuota` =  '{$obj->moneda_cuota}' ,  `fija_datos` =  '{$obj->fija_datos}' ,  `puntos_datos` =  '{$obj->puntos_datos}' ,  `noches_datos` =  '{$obj->noches_datos}' ,  `flotante_datos` =  '{$obj->flotante_datos}' ,  `destacar` =  '{$obj->destacar}' ,  `fecha_destacado` =  '{$obj->fecha_destacado}'   WHERE `idMem` = '$idMem' "; 
                $sql = "UPDATE membresias SET ".$set." WHERE `idMem` = '$idMem' "; 

                $stmt =  $this->conn->prepare($sql);
                $stmt->execute();
                $lastInsertId = $this->conn->lastInsertId();

                if($stmt){
                    return $lastInsertId;
                } else {
                    return NULL;
                }
            } else {
                return NULL;
            }
        }catch(PDOException $e){
            
            return NULL;
        }

    }



    public function deleteMembresia($id) {

        try{

            $idMem = (int) $id; 
            $sql = "DELETE FROM `membresias` WHERE `idMem` = '$idMem' "; 

            $stmt =  $this->conn->prepare($sql);
            $stmt->execute();

            if($stmt){
                return $id;
            } else {
                return NULL;
            }
        }catch(PDOException $e){
            
                return NULL;
        }

    }



    public function deleteImage($id) {

        try{

            $idMem = (int) $id; 
            $sql = "DELETE FROM `files` WHERE `id` = '$id' "; 

            $stmt =  $this->conn->prepare($sql);
            $stmt->execute();

            if($stmt){
                return $id;
            } else {
                return NULL;
            }
        }catch(PDOException $e){
            return NULL;
        }

    }




    /* GENERALES */



    public function postContacto($obj) {

        try{

            $obj = $obj->contacto;
            foreach($obj AS $key => $value) {
                $obj->$key = utf8_encode(mysql_real_escape_string($value));
            }

            $nombre = $obj->nombre;
            $email_envia = $obj->email;
            $telefono = $obj->telefono;
            $lenguaje = $obj->lenguaje;
            $cuerpo = $obj->mensaje;

            $email_recibe = "cesar_alonso_m_g@hotmail.com";


            $titulo = "Tiempocompartido ha recibido un mensaje";
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
            $stmt = mail($email_recibe, $titulo, $mensaje, $cabeceras);

            if ($stmt){
                return true;
            } else {
                return false;
            }


        }catch(PDOException $e){
            
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




    public function selectPublicidad() {

        try{

            $response = array();

            $sql = "SELECT * FROM `publicidad` WHERE posicion = 'index_der' AND status = 'publicado'";

            $stmt =  $this->conn->prepare($sql);
            $stmt->execute();

            $affected_rows = $stmt->rowCount();

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


            if($affected_rows<=0){
                $response["status"] = "warning";
                $response["message"] = "No se encontró publicidad.";
            }else{

                foreach($rows as $key => $value) {
                    foreach($rows[$key] as $key2 => $value2) {
                        $rows[$key][$key2] = utf8_decode($value2);
                    }
                }

                foreach($rows as $key => $value) {
                    $rows[$key]["enlace"] = $this->normaliza($rows[$key]["argumento"]);
                }

                $response["status"] = "success";
                $response["message"] = "Publicidad obtenida de base de datos";
                $response["data"] = $rows;
            }
            return $response;


        }catch(PDOException $e){
  
            $response["status"] = "error";
            $response["message"] = 'selectPublicidad ha fallado: ' .$e->getMessage();
            $response["data"] = null;
        }

    }




    public function selectPromociones() {

        try{

            $response = array();

            $sql = "SELECT * FROM `promociones` WHERE status = 'publicado'";

            $stmt =  $this->conn->prepare($sql);
            $stmt->execute();

            $affected_rows = $stmt->rowCount();

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if($affected_rows<=0){
                $response["status"] = "warning";
                $response["message"] = "No se encontró promoción.";
            }else{

                foreach($rows as $key => $value) {
                    foreach($rows[$key] as $key2 => $value2) {
                        $rows[$key][$key2] = utf8_decode($value2);
                    }
                }


                foreach($rows as $key => $value) {
                    $rows[$key]["enlace"] = $this->normaliza($rows[$key]["argumento"]);
                }

                //$rows["enlace"] = urlencode($rows["titulo"]);
                $response["status"] = "success";
                $response["message"] = "Promociones obtenidas de base de datos";
                $response["data"] = $rows;
            }
            return $response;


        }catch(PDOException $e){
  
            $response["status"] = "error";
            $response["message"] = 'selectPromociones ha fallado: ' .$e->getMessage();
            $response["data"] = null;
        }

    }




    public function getPromocion($idPro) {

        try{

            $response = array();

            $sql = "SELECT * FROM `promociones` WHERE status = 'publicado' AND id = $idPro";

            $stmt =  $this->conn->prepare($sql);
            $stmt->execute();

            $affected_rows = $stmt->rowCount();

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if($affected_rows<=0){
                $response["status"] = "warning";
                $response["message"] = "No se encontró promoción.";
            }else{


                foreach($rows as $key => $value) {
                    foreach($rows[$key] as $key2 => $value2) {
                        $rows[$key][$key2] = utf8_decode($value2);
                    }
                }


                foreach($rows as $key => $value) {
                    $rows[$key]["enlace"] = $this->normaliza($rows[$key]["argumento"]);
                }

                //$rows["enlace"] = urlencode($rows["titulo"]);
                $response["status"] = "success";
                $response["message"] = "Promoción obtenida de base de datos";
                $response["data"] = $rows;
            }
            return $response;


        }catch(PDOException $e){
  
            $response["status"] = "error";
            $response["message"] = 'getPromocion ha fallado: ' .$e->getMessage();
            $response["data"] = null;
        }

    }





    public function getPublicidad($idPub) {

        try{

            $response = array();

            $sql = "SELECT * FROM `publicidad` WHERE status = 'publicado' AND id = $idPub";

            $stmt =  $this->conn->prepare($sql);
            $stmt->execute();

            $affected_rows = $stmt->rowCount();

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if($affected_rows<=0){
                $response["status"] = "warning";
                $response["message"] = "No se encontró publicidad.";
            }else{

                foreach($rows as $key => $value) {
                    foreach($rows[$key] as $key2 => $value2) {
                        $rows[$key][$key2] = utf8_decode($value2);
                    }
                }

                foreach($rows as $key => $value) {
                    $rows[$key]["enlace"] = $this->normaliza($rows[$key]["argumento"]);
                }

                //$rows["enlace"] = urlencode($rows["titulo"]);
                $response["status"] = "success";
                $response["message"] = "Publicidad obtenida de base de datos";
                $response["data"] = $rows;
            }
            return $response;


        }catch(PDOException $e){
  
            $response["status"] = "error";
            $response["message"] = 'getPublicidad ha fallado: ' .$e->getMessage();
            $response["data"] = null;
        }

    }






    public function getEncuesta($id) {

        try{

            $response = array();

            $sql = "SELECT * FROM `encuestas` WHERE idEnc = $id";

            $stmt =  $this->conn->prepare($sql);
            $stmt->execute();

            $affected_rows = $stmt->rowCount();

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);



            $sql = "SELECT * FROM `preg_form_promo` WHERE idEnc = $id order by orden";

            $stmt =  $this->conn->prepare($sql);
            $stmt->execute();

            $affected_rows2 = $stmt->rowCount();

            $rows2 = $stmt->fetchAll(PDO::FETCH_ASSOC);



            if($affected_rows2 <= 0){
                $response["status"] = "warning";
                $response["message"] = "No se encontró encuesta.";
            }else{

                $response["status"] = "success";
                $response["message"] = "encuesta obtenida de base de datos";
                $response["data"] = $rows[0];
                $response["data"]["preguntas"] = $rows2;
            }
            return $response;


        }catch(PDOException $e){
  
            $response["status"] = "error";
            $response["message"] = 'getEncuesta ha fallado: ' .$e->getMessage();
            $response["data"] = null;
        }

    }





}

?>
