<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Samsung_modo_futbol extends CI_Controller
{

    var $folder;
    var $app_url;
    var $condiciones;
    var $iconos;
    var $img_path;
    var $vista_principal;
    var $app_name = "samsung_modo_futbol";
    var $cache;


    public function __construct(){
        parent::__construct();
        $this->config->load('fb_config_modo_futbol');
        $this->load->model('samsung_usuario', 'usuario');
        $this->load->model('samsung_modo_futbols', 'modelo');
        $this->load->helper(array('url', 'form'));
        $this->folder = 'modo_futbol';
        $this->app_name = 'samsung_modo_futbol'; //Nombre de la aplicacion para desarrollo
        $this->cache = rand(1, 10000);
        $this->img_path = base_url() . 'imagenes/' . $this->folder . '/'; //Path para direccionar las imagenes
        $this->condiciones = "Revise <a href='" . base_url() . "archivos/Terminos_Condiciones_SAMSUNG_MODO_FUTBOL.pdf' target='_blank' >aqu&iacute;</a> los términos y condiciones.";
    }

    public function index(){
        $data['appId'] = $this->config->item('facebook_api_id');
        $data['signedRequest'] = (isset($_REQUEST['signed_request'])) ? $_REQUEST['signed_request'] : '';
        $data['base'] = base_url();
        $data['controler'] = 'samsung_modo_futbol';
        $data['namespace'] = $this->config->item('namespacepruebas');
        $data['permission'] = $this->config->item('facebook_permissions');
        $data['debug'] = json_encode(array('develop' => TRUE, 'like' => TRUE));
        $data['tabLiker'] = 'liker';
        $data['tabNoLiker'] = 'noLiker';
        $data['doesNtCare'] = false;
        $data['content'] = 'content';
        $data['isPageTab'] = true;
        $data['data'] = "";
        $data['cache'] = $this->cache;
        $data['fondo'] = $this->img_path;
        $data['condiciones'] = $this->condiciones;
        $this->load->view($this->folder . '/welcome', $data);
    }

    public function getGeneral(){
        echo json_encode($this->parse_signed_request($_POST["signedRequest"], $this->config->item('facebook_secret_key')));
    }


    function parse_signed_request($signed_request, $secret)
    {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);
        $sig = $this->base64_url_decode($encoded_sig);
        $data = json_decode($this->base64_url_decode($payload), true);
        if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
            error_log('Unknown algorithm. Expected HMAC-SHA256');
            return null;
        }
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return null;
        }
        return $data;
    }

    function base64_url_decode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    function  pageTab()
    {
        $pageName = ($this->uri->segment(3) != 'undefined') ? $this->uri->segment(3) : $this->config->item('facebook_page');
        $appId = ($this->uri->segment(4) != '') ? $this->uri->segment(4) : $this->config->item('facebook_api_id');
        $namespace = ($this->uri->segment(5) != '') ? $this->uri->segment(5) : $this->config->item('namespacepruebas');
        echo "<script>parent.location.href='https://www.facebook.com/" . $pageName . "?v=app_" . $appId . "&app_data=" . $namespace . "';</script>";
    }

    function  pageApp()
    {
        $namespace = ($this->uri->segment(3) != '') ? $this->uri->segment(3) : $this->config->item('namespacepruebas');
        echo "<script>parent.location.href='https://apps.facebook.com/" . $namespace . "';</script>";
    }

    /*****************************/
    function noLiker()
    {
        $data['api_id'] = $this->config->item('facebook_api_id');
        $data['nofan'] = $this->img_path . 'nofan.jpg?fbrefresh=' . $this->cache;
        $this->load->view($this->folder . '/tab_noliker', $data);
    }

    function liker()
    {
        $data['api_id'] = $this->config->item('facebook_api_id');
        $data['user'] = json_decode($_POST['user']);
        $data['fb_page'] = json_decode($_POST['fb_page']);
        
        $user = $this->usuario->getUserFbid($data['user']->id);     
        
        if (isset($user->ciudad) && isset($user->telefono) && isset($user->cedula) && ($user != false)) {
            // caso no exist el usuario en el juego
            if ($this->modelo->busquedaRegistro($user->id) == "0") {
                $insertJuego = array('id_user' => $user->id);
                $this->db->insert('modo_futbol', $insertJuego);

                $this->vista_registro($data['user'], $data['fb_page']);
//                $this->vista_completo($data['user'], $data['fb_page']);
                $data_update = array(
                    'ultima_app' => $this->app_name);
                $this->db->where(array('id' => $user->id));
                $this->db->update('usuarios', $data_update);

                //en caso de estar referido por algun amigo, se le suma a ese amigo o amigos 1 punto.
                $amigos = $this->modelo->obtenerAmigosPuntos($data['user']->id);

                if ($amigos != FALSE) {
                    //$amigos=$this->modelo->obtenerAmigosPuntos('100000125463918');
                    foreach ($amigos as $row){

	                     if($this->modelo->sumarPuntaje($row->id_user)){
	                     	$this->envioMailUser($row->id_user);                     	
	                     	$this->modelo->setEtapaUser($row->id_user, 2);
	                    }
                    } 
                }

                // fin en caso referido
            } else {
                // $bloqueo = $this->modelo->obtenerBloqueo($user->id);
                //$compruebaPasaEtapa1 = pasaEtapa1($user->id);

                $etapaUsuario = $this->getEtapaUser($user->id);


                switch ($etapaUsuario) {
                    case 0:
                        $this->etapa1_invitar($user->id);
                        break;
                    case 1:
                        $this->etapa1_espera($user->id);
                        break;
                    case 2:
                        //segun la fecha se toman acciones
                        $hoy = new DateTime(date("Y-m-d "));
                        $fechaInicio = new DateTime('2013-9-5');
                        $interval = $hoy->diff($fechaInicio);
                        if ($interval->format('%R%a ') > 0 )
                        {
                           // $this->etapa1_espera($user->id);
                            $this->etapa2_videopregunta($user->id);
                        }
                        else
                        {
                            $this->etapa2_videopregunta($user->id);
                        }
                        //
                        break;
                        case 3:
                            $this->etapa3_declaracion($user->id);

                        break;
                        case 4:
                            $this->ranking($user->id);

                        break;
                }
                //$this->vista_compartido( $user->id );
                /*if ($bloqueo != 1) {
                    $this->vista_completo($data['user'], $data['fb_page']);
                } else {
                    $this->vista_completo($data['user'], $data['fb_page']);
                    //$this->vista_compartido( $user->id );
                }*/
            }
        } else {
            // se envia a llenar el formulario de registro
            $this->vista_registro($data['user'], $data['fb_page'], 0);
            // luego de llenar se inserta un registro
        }
    }
    
    function envioMailUser($id){    
	    $data['registro']=$this->modelo->obtenerDatosUser($id);
	    $this->load->library('email');
	    $config['protocol'] = 'sendmail';
	    $config['charset'] = 'utf8';
	    $config['mailtype'] = 'html';
	    $config['wordwrap'] = FALSE;
	    
	    $this->email->initialize($config);
	    $this->email->from('no-reply@misiva.com.ec', 'Modo Fútbol');
	    $this->email->to($data['registro']->mail);
	    //$this->email->cc('jtaairo_ramiro@yahoo.com');
	    //$this->email->cc('jfchiriboga@misiva.com.ec');    
	    
	    $data['informacion']=$data;
	    $body=$this->load->view($this->folder.'/email',$data,TRUE);     
	    $this->email->subject("Modo Fútbol te Informa");
	    $this->email->message($body);
	    $this->email->send();    
    }
    

    function ranking($user){

    	$data['id_user']=$user;
    	$data['registro']=$this->modelo->getTop2();
    	$this->load->view($this->folder . '/ranking', $data);
    }

    //paso 1
    function etapa1_invitar($user){
        $data['id_user'] = $user;
        $data['puntaje'] = $this->puntaje_modo_futbol($user);
        $this->load->view($this->folder . '/etapa1_invitar', $data);
    }

    function etapa1_espera($user){
        $amigosEfectivos = $this->getAmigosEfectivos($user);
        $data['id_user'] = $user;
        $data['puntaje'] = $this->puntaje_modo_futbol($user);
        if ($amigosEfectivos < 5) {
            $this->etapa1_resumen2($user);
        } else {
            $this->load->view($this->folder . '/etapa1_espera', $data);
            $data['grabo'] = $this->modelo->setEtapaUser($user, 2);
        }
    }

    function puntaje_modo_futbol($user)    {
        $amigosEfectivos = $this->getAmigosEfectivos($user);
        $respuestavideo= $this->modelo->getRespuestavideo($user);
        $calificacionconcurso= $this->modelo->getCalificacionconcurso($user);
      //  $puntaje = $amigosEfectivos ;
        $puntaje = $amigosEfectivos + $respuestavideo->respuestavideo + $calificacionconcurso->calificacionconcurso;
        return $puntaje;
    }

    function amigos_invitados($user){
        $amigos = $this->modelo->totalCompartidos($user);
        return $amigos->compartido;
    }

    function getAmigosEfectivos($user){
        $amigos = $this->modelo->totalCompartidos($user);
        return $amigos->compartidoefectivo;
    }

    function getEtapaUser($user){
        $amigos = $this->modelo->getEtapaUser($user);
        return $amigos->etapa;
    }

    //paso 2
    function etapa1_resumen(){
        $data['user'] = json_decode($_POST['user']);
        $data['id_user'] = json_decode($_POST['user']);
//        $data['user'] = $user;
        $data['puntaje'] = $this->puntaje_modo_futbol($data['user']);
        $data['amigos'] = $this->amigos_invitados($data['user']);
        $data['compartidoefectivo'] = $this->getAmigosEfectivos($data['user']);
        //cambio etapa 1 del usuario logeado

        $data['grabo'] = $this->modelo->setEtapaUser($data['user'], 1);

        $this->load->view($this->folder . '/etapa1_resumen', $data);
    }


    function etapa1_resumen2($user){
//        $data['user'] = json_decode($_POST['user']);
        $data['user'] = $user;
        $data['id_user'] = $user;
        $data['puntaje'] = $this->puntaje_modo_futbol($data['user']);
        $data['amigos'] = $this->amigos_invitados($data['user']);
        $data['compartidoefectivo'] = $this->getAmigosEfectivos($data['user']);
        $this->load->view($this->folder . '/etapa1_resumen', $data);
    }

    function etapa3_invitacion($user, $nombreArchivo){
       // { 'user': JSON.stringify(LibraryFacebook.getUserFbData()), 'fb_page': JSON.stringify(LibraryFacebook.getSignedRequest()) });


        $data['api_id'] = $this->config->item('facebook_api_id');

        $data['user'] = $user;
        $data['nombreArchivo'] = $nombreArchivo;
        $this->load->view($this->folder . '/etapa3_invitacion', $data);
    }

    //paso etapa2_videopregunta
    function etapa2_videopregunta($user){
        $data['id_user'] = $user;
        $data['puntaje'] = $this->puntaje_modo_futbol($user);

        $this->load->view($this->folder . '/etapa2_videopregunta', $data);

    }

    //paso etapa3_declaracion
    function etapa3_declaracion($user)
    {
        $data['id_user'] = $user;
        $data['puntaje'] = $this->puntaje_modo_futbol($user);
        $this->load->view($this->folder . '/etapa3_declaracion', $data);
    }


    function calculoPuntaje()
    {
        $amigos = $this->modelo->obtenerPuntosTotales();
        foreach ($amigos as $row) {
            $this->modelo->obtenerfbid($row->id_user);
        }
    }

    function fecha_fin()
    {
        $dia_limite = 7;
        $dia_actual = (int)date("d");
        $hora_actual = (int)date("H");
        $mes_actual = (int)date("m");
        if ($dia_actual >= $dia_limite && $hora_actual >= 20)
            return true;
        else
            return false;
    }

    function cambioEvento()
    {
        $dia_limite = 5;
        $dia_actual = (int)date("d");
        $hora_actual = (int)date("H");
        if ($dia_actual >= $dia_limite && $hora_actual >= 16)
            return true;
        else
            return false;
    }

    function fin_app()
    {
        $data['compartir'] = $this->img_path . 'graciasporparticipar.png?fbrefresh=' . $this->cache;
        $this->load->view($this->folder . '/tab_gracias', $data);
    }

    function verificarAmigo($id)
    {
        $amigos = array();
        $datos = $this->modelo->verAmigos($id);
        foreach ($datos as $row)
            array_push($amigos, $row->id_amigo);
        echo json_encode($amigos);
    }

    function registrarAmigos($id)
    {
        //todo: arreglar como registra a amigos no es necesario validar que cada vez que comparte cuantos amigos
        // solo que se registren

        $arreglo = json_decode($_GET['data']);
        $total = count($arreglo);
        $dato = $this->modelo->obtenerCampartidos($id);
        $val_ant = $dato->compartido;

        $val_nuevo = $total + $val_ant;

        $data_update = array(
            'compartido' => (string)$val_nuevo);
        $this->db->where(array('id_user' => $id));
        $this->db->update('modo_futbol', $data_update);

        foreach ($arreglo as $row) {
            $this->db->insert("modo_futbol_compartido", array("id_user" => $id, "id_amigo" => $row));
        }

        if ($val_nuevo > 5) {
            $data_update = array(
                'bloqueo' => "0");
            $this->db->where(array('id_user' => $id));
            $this->db->update('modo_futbol', $data_update);
            echo "1";
        } else {
            echo "0";
        }
    }

    function grabarEtapa2()
    {
        //todo: arreglar como registra a amigos no es necesario validar que cada vez que comparte cuantos amigos
        // solo que se registren

        $user = json_decode($_POST['user']);
        $puntos = json_decode($_POST['puntos']);
        $this->modelo->setPuntosEtapa2($user, $puntos);
        $data['grabo'] = $this->modelo->setEtapaUser($user, 3);
        $this->etapa3_declaracion($user);
    }


    function grabarEtapa3()
    {
        //todo: arreglar como registra a amigos no es necesario validar que cada vez que comparte cuantos amigos
        // solo que se registren

        $user = json_decode($_POST['user']);
        $declaracion = $_POST['caja1'];
        $concurso = $_POST['caja2'];


        $this->modelo->setPuntosEtapa3($user, $declaracion ,$concurso );

        $data['grabo'] = $this->modelo->setEtapaUser($user, 4);
         $this->ranking( $user);
    }

    public function vista_registro($user = FALSE, $page_data = FALSE, $primeravez = 1)
    {
        if (isset($_POST['telefono'])) {
            if (($_POST['ciudad'] != "Ej: Quito") && ($_POST['cedula'] != "Ej: 1720254478") && ($_POST['telefono'] != 'Ej: 099233547')) {
                $data = $this->usuario->getUserFbid($_POST['fbid']);
                $data_update = array(
                    'telefono' => $_POST['telefono'],
                    'cedula' => $_POST['cedula'],
                    'ciudad' => $_POST['ciudad'],
                    'ultima_app' => $this->app_name);
                $this->db->where(array('fbid' => $_POST['fbid']));
                $this->db->update('usuarios', $data_update);
                echo "1";
            }
        } else {
            $data['user_db'] = ($user !== FALSE) ? $this->usuario->getUserFbid($user->id) : $this->usuario->getUserFbid($_POST['fbid']);
            $page_data = ($page_data != FALSE) ? $page_data : json_decode($_POST['fb_page']);
            $data['pageid'] = $page_data->page->id;
            $data['api_id'] = $this->config->item('facebook_api_id');
            $data['img_path'] = $this->img_path;
            $data['user'] = $user;
            $array_insert = array(
                'fbid' => $user->id,
                'nombre' => $user->first_name,
                'apellido' => $user->last_name,
                'completo' => $user->name,
                'mail' => $user->email,
                'genero' => $user->gender,
                'ultima_app' => $this->app_name);


            $this->usuario->newRegister($array_insert);
            $insert_id = $this->db->insert_id();

            //            // en caso de que sea la primera vez ingresamos el registro en caso de que sea la primera vez ingresamos el registro

            if ($primeravez == 0) {
                $insertJuego = array('id_user' => $insert_id);
                $this->db->insert('modo_futbol', $insertJuego);
            }


            if (isset($data['user_db']->cedula)) {
                if (strlen($data['user_db']->cedula) > 0 || $data['user_db']->cedula != false)
                    $data['cedula'] = $data['user_db']->cedula;
                else
                    $data['cedula'] = "Ej: 1720254478";
            } else
                $data['cedula'] = "Ej: 1720254478";
            if (isset($data['user_db']->telefono)) {
                if (strlen($data['user_db']->telefono) > 0 || $data['user_db']->cedula != false)
                    $data['telefono'] = $data['user_db']->telefono;
                else
                    $data['telefono'] = "Ej: 0993000547";
            } else
                $data['telefono'] = "Ej: 0993000547";

            if (isset($data['user_db']->ciudad)) {
                if (strlen($data['user_db']->ciudad) > 0)
                    $data['ciudad'] = $data['user_db']->ciudad;
                else
                    $data['ciudad'] = "Ej: Quito";
            } else
                $data['ciudad'] = "Ej: Quito";

            $data['img_fondo'] = $this->img_path . 'registro.jpg?fbrefresh=5646465464';
            $data['boton1'] = $this->img_path . 'btn-registro.png?fbrefresh=465465465465';
            $data['boton2'] = $this->img_path . 'btn-registro-on.png?fbrefresh=464565465464';
            $this->load->view($this->folder . '/vista_registro', $data);
        }
    }

    public
    function vista_completo($user = FALSE, $page_data = FALSE)
    {
        $data['api_id'] = $this->config->item('facebook_api_id');
        $user = ($user != FALSE) ? $user : json_decode($_POST['user']);
        $page_data = ($page_data != FALSE) ? $page_data : json_decode($_POST['fb_page']);
        $user_db = $this->usuario->getUserFbid($user->id);
        $data['user'] = $user_db;
        $update = array("bloqueo" => "1");
        $this->db->where("id_user", $data['user']->id);
        $this->db->update('modo_futbol', $update);


        $this->load->view($this->folder . '/informacion', $data);
    }

    function informacion($id)
    {
        $data['id'] = $id;

        $data['pop'] = $this->img_path . 'popup.png?fbrefresh=' . $this->cache;
        $this->load->view($this->folder . '/informacion', $data);
    }

    function getTop($id)
    {
        $data['id'] = $id;
        $data['registro'] = $this->modelo->get_modo_futbol_Puntaje($id);
        $data['pop'] = $this->img_path . 'ranking.png?fbrefresh=464654654883651651';
        $data['boton1'] = $this->img_path . 'bt-ranking-regresar.png?fbrefresh=' . $this->cache;
        $data['boton2'] = $this->img_path . 'bt-ranking-regresar-on.png?fbrefresh=' . $this->cache;
        $data['datos'] = $this->modelo->getTop2();
        $this->load->view($this->folder . '/ranking', $data);
    }


    function guardar_datos()
    {
        $tiempo = $_POST['tiempo'];
        $movimiento = $_POST['movimiento'];
        $user = $_POST['user'];
        $this->modelo->actualizar($tiempo, $movimiento, $user);
        echo "1";
    }

    function vista_compartido($user)
    {
        $dato = $this->modelo->obtenerCampartidos($user);
        $data['compartido'] = 5 - ($dato->compartido % 5);
        $data['id_user'] = $user;
        $data['compartir'] = $this->img_path . 'compartir.jpg?fbrefresh=' . $this->cache;
        $data['boton1'] = $this->img_path . 'btn-compartir.png?fbrefresh=' . $this->cache;
        $data['boton2'] = $this->img_path . 'btn-compartir-on.png?fbrefresh=' . $this->cache;
        $this->load->view($this->folder . '/tab_compartir', $data);
    }

    function compartidos($id)
    {
        $totalCompartido = $this->modelo->totalCompartidos($id);
        $resultado = 5 - ($totalCompartido->compartido % 5);
        echo $resultado;
    }


}