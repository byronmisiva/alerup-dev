<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Samsung_laberinto extends CI_Controller {	
	
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
		$this->config->load('fb_config_laberinto');
		$this->load->model( 'samsung_usuario','usuario' );
		$this->load->model( 'samsung_laberintos','modelo' );
		$this->load->helper( array( 'url','form' ) );	
		$this->folder = 'laberinto';
		$this->app_name = 'samsung_laberinto'; //Nombre de la aplicacion para desarrollo
		$this->cache=rand(1,10000);		
		$this->img_path = base_url().'imagenes/'.$this->folder.'/'; //Path para direccionar las imagenes
		$this->condiciones="Revise <a href='".base_url()."archivos/Terminos_Condiciones_SAMSUNG_LABERINTATIV.pdf' target='_blank' >aqu&iacute;</a> los términos y condiciones.";
	}

	public function index(){
		$data['appId'] = $this->config->item('facebook_api_id');
		$data['signedRequest'] = ( isset($_REQUEST['signed_request'] ) ) ? $_REQUEST['signed_request'] : '' ;
		$data['base'] = base_url();
		$data['controler'] = 'samsung_laberinto';
		$data['namespace'] = $this->config->item('namespacepruebas');
		$data['permission'] = $this->config->item('facebook_permissions');
		$data['debug'] = json_encode( array( 'develop' => TRUE, 'like' => TRUE ) );
		$data['tabLiker'] =  'liker';
		$data['tabNoLiker'] = 'noLiker';
		$data['doesNtCare'] = false;
		$data['content'] = 'content';
		$data['isPageTab'] = true;
		$data['data'] = "";
		$data['cache']=$this->cache;
		$data['fondo'] = $this->img_path;		
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
	
	/*****************************/
	function noLiker(){
		$data['api_id'] = $this->config->item('facebook_api_id');				
		$data['nofan']= $this->img_path.'nofan.jpg?fbrefresh='.$this->cache;		
		$this->load->view( $this->folder.'/tab_noliker', $data );		
	}
	
	function liker(){		
		$data['api_id'] = $this->config->item('facebook_api_id');
		$data['user'] = json_decode( $_POST['user'] );
		$data['fb_page'] = json_decode( $_POST['fb_page'] );	
		$user = $this->usuario->getUserFbid( $data['user']->id );	
		/*if($this->fecha_fin()==true)
		 $this->fin_app();
		else{*/
			if ((strlen($user->ciudad)>0) && (strlen($user->telefono)>0) && (strlen($user->cedula)>0) && ($user!=false) ){
				if ($this->modelo->busquedaRegistro($user->id)=="0"){				
						$insertJuego= array('id_user' => $user->id);
						$this->db->insert( 'laberinto', $insertJuego);
						$this->vista_completo( $data['user'], $data['fb_page'] );	
						$data_update = array(
								'ultima_app' => $this->app_name);
						$this->db->where( array( 'id' =>  $user->id) );
						$this->db->update( 'usuarios', $data_update );					
						//valor puntaje
						$amigos=$this->modelo->obtenerAmigosPuntos($data['user']->id);
						if ($amigos!=FALSE){
							//$amigos=$this->modelo->obtenerAmigosPuntos('100000125463918');
							foreach($amigos as $row)
								$this->modelo->sumarPuntaje($row->id_user);
						}										
				}else{	
					$bloqueo=$this->modelo->obtenerBloqueo($user->id);
					
					
					if ($bloqueo!=1){					
						$this->vista_completo( $data['user'], $data['fb_page'] );
					}else{					
						$this->vista_compartido( $user->id );
					}
				}
			}else{		
		       	$this->vista_registro( $data['user'], $data['fb_page'] );
			}	
		//}		      
	}
	
	
	function calculoPuntaje(){
		$amigos=$this->modelo->obtenerPuntosTotales();
		foreach($amigos as $row){
			$this->modelo->obtenerfbid($row->id_user);
		}
	}
	
	function fecha_fin(){
		$dia_limite=23;		
		$dia_actual=(int)date("d");
		$hora_actual =(int)date("H");
		$mes_actual=(int)date("m");			
		if($dia_actual >=$dia_limite )
			return true;
		else
			return false;
	}
		
	function fin_app(){
		$data['logo']=$this->img_path.'ganador.jpg?fbrefresh='.$this->cache;			
		$this->load->view(  $this->folder.'/tab_fin', $data );
	}
	
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
		
		$data_update = array(
				'compartido' => (string)$val_nuevo);		
		$this->db->where( array( 'id_user' => $id ) );
		$this->db->update( 'laberinto', $data_update );
		
		foreach($arreglo as $row){
			$this->db->insert("laberinto_compartido",array("id_user"=>$id,"id_amigo"=>$row));
		}
		
		if( $total>=5 || $val_nuevo%5==0 ){		
			$data_update = array(
					'bloqueo' => "0");
			$this->db->where( array( 'id_user' => $id ) );
			$this->db->update('laberinto', $data_update );
			echo "1";
		}
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
				
			$data['img_fondo']=$this->img_path.'registro.jpg?fbrefresh=5646465464';
			$data['boton1']=$this->img_path.'btn-registro.png?fbrefresh=465465465465';
			$data['boton2']=$this->img_path.'btn-registro-on.png?fbrefresh=464565465464';
			$this->load->view( $this->folder.'/vista_registro', $data);
		}
	}
	
	public function vista_completo( $user = FALSE, $page_data = FALSE ){
		$data['api_id'] = $this->config->item('facebook_api_id');		
		$user = ( $user != FALSE ) ? $user : json_decode( $_POST['user'] );
		$page_data = ( $page_data != FALSE ) ? $page_data : json_decode( $_POST['fb_page'] );	
		$user_db = $this->usuario->getUserFbid( $user->id );
		$data['user']=$user_db;		
		
		/*$update=array("bloqueo"=>"1");
		$this->db->where("id_user",$data['user']->id);
		$this->db->update('laberinto',$update);*/
											
		$data['fondo']=$this->img_path.'juego.jpg?fbrefresh='.$this->cache;		
		$this->load->view( $this->folder.'/tab_completo', $data );		
	}
	
	function informacion($id){
		$data['id']=$id;
		$data['boton1']=$this->img_path.'bt-instruccuines-ranking.png?fbrefresh='.$this->cache;
		$data['boton2']=$this->img_path.'bt-instruccuines-ranking-on.png?fbrefresh='.$this->cache;
		$data['boton3']=$this->img_path.'bt-instruccuines-jugar.png?fbrefresh='.$this->cache;
		$data['boton4']=$this->img_path.'bt-instruccuines-jugar-on.png?fbrefresh='.$this->cache;
		
		$data['pop']=$this->img_path.'popup.png?fbrefresh='.$this->cache;
		$this->load->view( $this->folder.'/informacion', $data );		
	}
	
	function getTop($id){
		$data['id']=$id;
		$data['registro']=$this->modelo->get_Laberinto_Puntaje($id);
		$data['pop']=$this->img_path.'ranking.png?fbrefresh=464654654883651651';
		$data['boton1']=$this->img_path.'bt-ranking-regresar.png?fbrefresh='.$this->cache;
		$data['boton2']=$this->img_path.'bt-ranking-regresar-on.png?fbrefresh='.$this->cache;
		$data['datos']=$this->modelo->getTop2();
		$this->load->view( $this->folder.'/ranking', $data );
	}
	
		
	function guardar_datos(){
		$tiempo=$_POST['tiempo'];
		$movimiento=$_POST['movimiento'];
		$user=$_POST['user'];		
		$this->modelo->actualizar($tiempo,$movimiento,$user);
		echo "1";
	}
	
	function vista_compartido($user){		
		$dato=$this->modelo->obtenerCampartidos($user);		
		$data['compartido']=5-($dato->compartido%5);
		$data['id_user']=$user;			
		$data['compartir']=$this->img_path.'compartir.jpg?fbrefresh='.$this->cache;
		$data['boton1']=$this->img_path.'btn-compartir.png?fbrefresh='.$this->cache;
		$data['boton2']=$this->img_path.'btn-compartir-on.png?fbrefresh='.$this->cache;		
		$this->load->view( $this->folder.'/tab_compartir', $data );		
	}
	
	function compartidos($id){
		$totalCompartido=$this->modelo->totalCompartidos($id);
		$resultado=5-($totalCompartido->compartido%5);
		echo $resultado;
	}
	
	
	
	
	
	
}