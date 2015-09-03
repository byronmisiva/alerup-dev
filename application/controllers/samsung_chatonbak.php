<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Samsung_chaton extends CI_Controller {	
	
	var $folder;
	var $app_url;	
	var $condiciones;
	var $iconos;
	var $img_path;
	var $vista_principal;
	var $app_name;	
	var $cache;
	
	public function __construct(){
		parent::__construct();
		$this->config->load('fb_chaton');
		$this->load->model( 'samsung_usuario','usuario' );
		$this->load->model( 'samsung_chato','modelo' );
		$this->load->helper( array( 'url','form' ) );	
		$this->folder = 'samsung_chaton';
		$this->app_name = 'samsung_chaton'; //Nombre de la aplicacion para desarrollo
		$this->cache=rand(1,100000);		//
		$this->img_path = base_url().'imagenes/'.$this->folder.'/'; //Path para direccionar las imagenes
		
		$this->condiciones="Esta promoci&oacute;n no guarda ning&uacute;n tipo de relaci&oacute;n directa o indirecta con Facebook, Compa&ntilde;ias Afiliadas, Filiales de la misma o Subsidiarias. 
		De la misma manera, Facebook no patrocina, endosa o administra directa o indirectamente esta promoci&oacute;n. Todas las preguntas concernientes a la misma deber&aacute;n 
		ser remitidas directamente a Samsung Electronics Ecuador y NO a Facebook. La informaci&oacute;n recolectada a trav&eacute;s de esta promoci&oacute;n se realiza de manera 
		independiente a Facebook con fines exclusivos de identificaci&oacute;n, al entregarla, el usuario acepta los t&eacute;rminos y condiciones establecidos para la misma. 
		Todas las marcas registradas son propiedad de sus respectivos due&ntilde;os. 
		<a href='".base_url()."archivos/REGLAMENTO_CONCURSO.pdf' target='_blank' >aqu&iacute;</a>";
	}

	public function index(){
		$data['appId'] = $this->config->item('facebook_api_id');
		$data['signedRequest'] = ( isset($_REQUEST['signed_request'] ) ) ? $_REQUEST['signed_request'] : '' ;
		$data['base'] = base_url();
		$data['controler'] = 'samsung_chaton';
		$data['namespace'] = $this->config->item('namespacepruebas');
		$data['permission'] = $this->config->item('facebook_permissions');
		$data['debug'] = json_encode( array( 'develop' => TRUE, 'like' => true ) );
		$data['tabLiker'] =  'liker';
		$data['tabNoLiker'] = 'noLiker';
		$data['doesNtCare'] = false;
		$data['content'] = 'content';
		$data['isPageTab'] = true;
		$data['data'] = "";
		$data['cache']=$this->cache;
		$data['fondo'] = $this->img_path."fondo.jpg?fb_refresh=".$this->cache;		
		$data['condiciones'] = $this->condiciones;
		$this->load->view( $this->folder.'/welcome', $data );
	}
	
	public function getGeneral(){
		echo json_encode( $this->parse_signed_request( $_POST["signedRequest"], $this->config->item('facebook_secret_key') ) );
	}
	
	function parse_signed_request($signed_request, $secret) {
		list($encoded_sig,$payload)=explode('.',$signed_request,2);
		$sig=$this->base64_url_decode($encoded_sig);
		$data=json_decode($this->base64_url_decode($payload),true);
		if(strtoupper($data['algorithm'])!=='HMAC-SHA256'){
			error_log('Unknown algorithm. Expected HMAC-SHA256');
			return null;
		}
		$expected_sig=hash_hmac('sha256',$payload,$secret,$raw=true);
		if($sig!==$expected_sig) {
			error_log('Bad Signed JSON signature!');
			return null;
		}
		return $data;
	}
	
	function base64_url_decode($input){
		return base64_decode(strtr($input,'-_','+/'));
	}
	
	function  pageTab(){
		$pageName = ( $this->uri->segment(3) != 'undefined') ? $this->uri->segment(3) : $this->config->item('facebook_page');
		$appId = ( $this->uri->segment(4) != '' ) ? $this->uri->segment(4) : $this->config->item('facebook_api_id');
		$namespace = ( $this->uri->segment(5) != '' ) ? $this->uri->segment(5) : $this->config->item('namespacepruebas');		
		echo "<script>parent.location.href='https://www.facebook.com/".$pageName."?v=app_".$appId."&app_data=".$namespace."';</script>";
	}
	
	function  pageApp(){		
		$namespace = ( $this->uri->segment(3) != '' ) ? $this->uri->segment(3) : $this->config->item('namespacepruebas');		
		echo "<script>parent.location.href='https://apps.facebook.com/".$namespace."';</script>";
	}
	
	function qr(){
		$this->load->view( $this->folder.'/qr' );
	}
	
	/*****************************/
	function noLiker(){
		$data['api_id'] = $this->config->item('facebook_api_id');				
		$data['nofan']= $this->img_path.'nofan.png?fbrefresh='.$this->cache;
		$data['texto']= $this->img_path.'haztefan.png?fbrefresh='.$this->cache;		
		$this->load->view( $this->folder.'/tab_noliker', $data );		
	}
	
	function liker(){			
		$data['api_id'] = $this->config->item('facebook_api_id');		
		$data['img_path'] = $this->img_path;	
			$data['user'] = json_decode( $_POST['user'] );
			$data['fb_page'] = json_decode( $_POST['fb_page'] );	
			$user = $this->usuario->getUserFbid( $data['user']->id );
			if (($user!=0))
				if ( (strlen($user->ciudad)>0) && (strlen($user->telefono)>0) && (strlen($user->cedula)>0) ){				
					$dato=$this->modelo->busquedaRegistro($user->id);				
					if ($dato==FALSE){
						$insertJuego= array('id_user' => $user->id);
						$this->db->insert( 'chaton', $insertJuego);
						$this->vista_completo( $data['user'], $data['fb_page'] );
					}else if( $dato->bloqueado =="0") 				 		
						$this->vista_completo( $data['user'], $data['fb_page'] );
					else{
						$this->vista_compartido($user->id,TRUE);
				}			
			}else			
	        	$this->vista_registro( $data['user'], $data['fb_page'] );
			else
				$this->vista_registro( $data['user'], $data['fb_page'] );
	}
	
	function fecha_fin(){
		$dia_limite=31;		
		$dia_actual=(int)date("d");
		$hora_actual =(int)date("H");
		$mes_actual=(int)date("m");			
		if($dia_actual >=$dia_limite)
			return true;
		else
			return false;
	}
	
	function fin_app(){
		$data['compartir']=$this->img_path.'graciasporparticipar.png?fbrefresh='.$this->cache;			
		$this->load->view( $this->folder.'/tab_gracias', $data );
	}
	
	public function vista_registro( $user = FALSE, $page_data = FALSE  ){	
		if( isset( $_POST['telefono'])){
			if( ($_POST['ciudad']!="Ej: Quito") && ($_POST['cedula']!="Ej: 1720254478") &&($_POST['telefono']!='Ej: 099233547')){
				$data=$this->usuario->getUserFbid($_POST['fbid']);
					
				$data_update = array(
						'telefono' => $_POST['telefono'],
						'cedula' => $_POST['cedula'],
						'ciudad' => $_POST['ciudad'],
						'ultima_app' => $this->app_name);
				$this->db->where( array( 'fbid' =>  $_POST['fbid']) );
				$this->db->update( 'usuarios', $data_update );
				echo "1";
			}
		}else{
			$data['user_db'] =( $user !== FALSE ) ? $this->usuario->getUserFbid( $user->id ) : $this->usuario->getUserFbid( $_POST['fbid'] );
			$page_data = ( $page_data != FALSE ) ? $page_data : json_decode( $_POST['fb_page'] );
			$data['pageid']=$page_data->page->id;
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
					'ultima_app' => $this->app_name );
			$this->usuario->newRegister( $array_insert );
					
			if(strlen($data['user_db']->cedula)>0 || $data['user_db']->cedula!=false )
				$data['cedula']=$data['user_db']->cedula;
			else 
				$data['cedula']="Ej: 1720254478";
			
			if(strlen($data['user_db']->telefono)>0 || $data['user_db']->cedula!=false )
				$data['telefono']=$data['user_db']->telefono;
			else
				$data['telefono']="Ej: 0993000547";
			
			if(strlen($data['user_db']->ciudad)>0)
				$data['ciudad']=$data['user_db']->ciudad;
			else
				$data['ciudad']="Ej: Quito";
				
			$data['img_fondo']=$this->img_path.'registro.png?fbrefresh='.$this->cache;
			$data['enviar1']=$this->img_path.'bt-enviar1.png?fbrefresh='.$this->cache;
			$data['enviar2']=$this->img_path.'enviar2.png?fbrefresh='.$this->cache;
			$this->load->view( $this->folder.'/vista_registro', $data);
		}
	}
	
	public function vista_completo( $user = FALSE, $page_data = FALSE ){
		$data['api_id'] = $this->config->item('facebook_api_id');
		$data['img_path'] = $this->img_path;
		$user = ( $user != FALSE ) ? $user : json_decode( $_POST['user'] );
		$page_data = ( $page_data != FALSE ) ? $page_data : json_decode( $_POST['fb_page'] );	
		$user_db = $this->usuario->getUserFbid( $user->id );
		$data['user']=$user_db;
		$data['pageid']=$page_data->page->id;
		$data['gplay']=$this->img_path.'gplay.png?fbrefresh='.$this->cache;
		$data['itunes']=$this->img_path.'itunes.png?fbrefresh='.$this->cache;
		$data['fondo']=$this->img_path.'contenido-pantallainicial.png?fbrefresh='.$this->cache;
		$data['texto']=$this->img_path.'frasepasos.png?fbrefresh='.$this->cache;
		$data['qr']=$this->img_path.'qr.jpg?fbrefresh='.$this->cache;
		$data['boton1']=$this->img_path.'bt-inicia.png?fbrefresh='.$this->cache;
		$data['boton2']=$this->img_path.'bt-inicia-on.png?fbrefresh='.$this->cache;				
		$this->load->view( $this->folder.'/tab_completo', $data );		
	}
	
	function juego($id){
		$data['numeros']=$this->modelo->numero();				
		$data['id']=$id;		
		if ($data['numeros']->ganador==1){
			$ganador= array(
					"fbidganador"=>$id,
					"sorteado"=>"1"
				);
			$this->db->where("id",$data['numeros']->id);
		    $this->db->update("numeros",$ganador);
		    $this->mailGanador($id);
		}	
		
		$data['fondo']=$this->img_path.'contenido_numero.png?fbrefresh='.$this->cache;
		$data['qr']=$this->img_path.'qr.jpg?fbrefresh='.$this->cache;		
		$data['boton1']=$this->img_path.'bt-continuar1.png?fbrefresh='.$this->cache;
		$data['boton2']=$this->img_path.'bt-continuar1-on.png?fbrefresh='.$this->cache;
		$this->load->view( $this->folder.'/juego', $data );		
	}
	
	function mailGanador($id){
		$dato=$this->usuario->getDatos($id);
		$this->load->library('email');
		$config['protocol'] = 'sendmail';
		$config['charset'] = 'utf8';
		$config['mailtype'] = 'html';
		$config['wordwrap'] = FALSE;
				
		$this->email->initialize($config);
		$this->email->from('info@misiva.com.ec', 'Posible Ganador');
		$this->email->to('jairo_ramiro@yahoo.com');
		//$this->email->cc('jairo_ramiro@yahoo.com');
		//$this->email->cc('jfchiriboga@misiva.com.ec');
		$this->email->bcc('jortiz@misiva.com.ec');
		
		$data['informacion']=$dato;		
		$body=$this->load->view( $this->folder.'/email',$data,TRUE);
		$this->email->subject("Encuentra a Chato");
		$this->email->message($body);
		$this->email->send();	
	}
	
		
	function felicitacion($id){
		$data['id']=$id;
		$data['fondo']=$this->img_path.'volver.png?fbrefresh='.$this->cache;
		$data['boton1']=$this->img_path.'bt-reiniciar.png?fbrefresh='.$this->cache;
		$data['boton2']=$this->img_path.'bt-reiniciar-on.png?fbrefresh='.$this->cache;
		$this->load->view( $this->folder.'/felicitacion', $data );		
	}
	
	function insertarDatos($tipo,$eq1,$eq2,$id){
		$_POST["equipo1"]=$eq1;
		$_POST["equipo2"]=$eq2;
		$_POST["tipo"]=$tipo;
		
		if($tipo==1){
			$this->db->where('id_user',$id);						
			$this->db->update('futbol_time',$_POST);
		}else{
			$this->db->where('id_user',$id);
			$this->db->update('futbol_time',$_POST);
		}
	}
	
	function vista_compartido($user,$blo=FALSE){
		if ($blo=="1")
			$this->modelo->bloquearJuego($user);
				
		$dato=$this->modelo->obtenerCampartidos($user);
			$data['compartido']=5-($dato->compartido%5);
			$data['id_user']=$user;			
			$data['fondo']=$this->img_path.'contenido-comparte.png?fbrefresh=464987491649684';
			$data['boton1']=$this->img_path.'bt-comparrtir.png?fbrefresh='.$this->cache;
			$data['boton2']=$this->img_path.'bt-comparrtir-on.png?fbrefresh='.$this->cache;			
			$this->load->view( $this->folder.'/tab_compartir', $data );
		
	}
	
	/**************FUNCIONS PARA COMPARTIR****************/
	function verificarAmigo($id){
		$amigos=array();
		$datos=$this->modelo->verAmigos($id);
		foreach ($datos as $row)
			array_push($amigos, $row->id_amigo);
		echo json_encode($amigos);
	}
	
	function registrarAmigos($id){
		$arreglo=json_decode($_GET['data']);
		$total=count($arreglo);
		$dato=$this->modelo->obtenerCampartidos($id);
		$val_ant=$dato->compartido;
		$val_ac=(int)$total;
		$val_nuevo=$total+$val_ant;			
		if( $val_nuevo >= 5 )
			echo "1";
		$data_update = array(
				'compartido' => (string)$val_nuevo				
				);
		$this->db->where( array( 'id_user' => $id ) );
		$this->db->update( 'chaton', $data_update );
		
		if($total>=5){
			$data_update = array('bloqueado' => "0");
			$this->db->where( array( 'id_user' => $id ) );
			$this->db->update( 'chaton', $data_update );
		}
		foreach($arreglo as $row){
			$this->db->insert("chaton_compartido",array("id_user"=>$id,"id_amigo"=>$row));
		}
	}
	
	function compartidos($id){
		$totalCompartido=$this->modelo->totalCompartidos($id);
		$resultado=5-$totalCompartido->compartido;
		echo $resultado;
	}
	
	
	/**************************/
	
	
	
	
	
}