<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Samsung_palco extends CI_Controller {
	
	var $folder;
	var $app_url;	
	var $condiciones;
	var $iconos;
	var $img_path;
	var $vista_principal;
	var $app_name;
	var $posicion;
	
	public function __construct(){
		parent::__construct();
		$this->load->helper( array( 'url' ) );
		$this->load->model( 'samsung_usuario','usuario' ); //Base de datos de usuarios general de Samsung
		$this->load->library( 'session' );
		$this->develop = TRUE;
		$this->folder = 'samsung_palco';
		$this->app_name = 'samsung_palco'; //Nombre de la aplicacion para desarrollo
		$this->img_path = base_url().'imagenes/'.$this->folder.'/'; //Path para direccionar las imagenes
		$this->vista_principal = 'vista_palco'; //Funcion de la vista principal de la app
		$this->posicion = array( 1 => '', 2 => '', 3 => '', 4 => '', 5 => '' ) ;
		if(!$this->develop){
			$config = array('file' => 'fb_samsung_palco');		
			$this->load->library('myfacebook',$config);
			if(isset($_POST['signed_request']))
				$this->session->set_flashdata('signed_request', $_POST['signed_request']);
			$this->app_id=$this->config->item('facebook_api_id');
			$this->facebook_namespace=$this->config->item('facebook_namespace');
		}
		else
			$this->app_id='000000000000001';
		
		$this->condiciones="Esta promoci&oacute;n no guarda ning&uacute;n tipo de relaci&oacute;n directa o indirecta con Facebook, Compa&ntilde;ias Afiliadas, Filiales de la misma o Subsidiarias. 
		De la misma manera, Facebook no patrocina, endosa o administra directa o indirectamente esta promoci&oacute;n. Todas las preguntas concernientes a la misma deber&aacute;n 
		ser remitidas directamente a Samsung Electronics Ecuador y NO a Facebook. La informaci&oacute;n recolectada a trav&eacute;s de esta promoci&oacute;n se realiza de manera 
		independiente a Facebook con fines exclusivos de identificaci&oacute;n, al entregarla, el usuario acepta los t&eacute;rminos y condiciones establecidos para la misma. 
		Todas las marcas registradas son propiedad de sus respectivos due&ntilde;os. <a href='".base_url()."archivos/Reglamento_Palco_Samsung_3.pdf' target='_blank' >aqu&iacute;</a>.";	
		$this->output->set_header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
	}
	
	public function index(){		
		$signed_request=$this->session->flashdata('signed_request');
		if($signed_request !== false){
			$data=$this->myfacebook->getGeneral($signed_request);
			
			$this->permisos($this->uri->segment(3),$data['page']['id']);
		}
		elseif($this->session->userdata('fbpage') != false)
			$this->permisos($this->session->userdata('fbpage'));
		else
			$this->permisos();
			
	}
	
	public function permisos($id=0,$fbpage=0){
		if($id=="")
			$id=0;
		if(!$this->develop)
			$this->myfacebook->permission($id,$fbpage);
		else
			$this->check();	
	}
	
	public function autentificacion(){
		$data="";
		if(isset($_GET['data']))
			$data=$_GET['data'];
		
		if(isset($_GET['code']))
			$this->myfacebook->getToken($_GET['code'],$data,$_GET['fbpg']);
		else
			redirect('http://www.facebook.com');
		
	}
	
	public function check($fbpage=0){
		
		$vista_principal=$this->vista_principal;
		
		if(!$this->develop){
			$fbdatos=$this->myfacebook->getThinks('me');
				
			if(is_null($fbdatos->id)){
				$this->permisos();
			}
			else{
				$this->$vista_principal();
			}
		}
		else
			$this->$vista_principal();
	}
	
	public function tab(){			
		$data=array();
		if(!$this->develop){
			$pedido=$_POST['signed_request'];
			$datos=$this->myfacebook->getGeneral($pedido);			
			$page_id=$datos['page']['id'];
			$page_liked = $datos['page']['liked'];	
		}
		else{
			$page_id = "pruebas";
			//$datos['app_data']='prueba'; //Comentar o descomentar para probar una aplicacion
			$page_liked = TRUE; //Cambiar el valor (true / false) para realizar pruebas
		}
		
		if(isset($datos['app_data']) and $page_liked=='true')
			$this->check();
		else{
			if( $page_liked === TRUE ){
				$this->session->set_userdata( 'page_id' , $page_id );
				redirect( 'samsung_palco' );
			}
			else{				
				$data['api_id'] = $this->app_id;
				$data['img_path'] = $this->img_path;
				$data['fondo'] = $this->img_path.'apppalconogfan.jpg?fbrefresh=1234566465465';				
				$data['condiciones'] = $this->condiciones;				
				$this->load->view( $this->folder.'/tab_noliker', $data );
			}
		}		
	}	
	
	function vista_palco(){		
		$data['api_id'] = $this->app_id;
		$data['fondo'] = $this->img_path.'escojer.jpg?fbrefresh=654566465';		
		$data['condiciones'] = $this->condiciones;
		$data['img_path'] = $this->img_path;
		$data['user'] = $this->getFbData();
		$user = $this->usuario->getUserFbid( $data['user']['fbdatos']['fbid'] );
		
		
		$array_insert = array(
		 		'fbid' => $data['user']['fbdatos']['fbid'],
		 		'nombre' => $data['user']['fbdatos']['nombre'],
		 		'apellido' => $data['user']['fbdatos']['apellido'],
		 		'completo' => $data['user']['fbdatos']['completo'],
		 		'mail' => $data['user']['fbdatos']['mail'],
		 		'genero' => $data['user']['fbdatos']['genero'],
		 		'ultima_app' => $this->app_name);			
		$this->usuario->newRegister( $array_insert );
		if( $this->usuario->alreadyRegistrer( 'usuarios', array( 'fbid' => $data['user']['fbdatos']['fbid'], 'cedula !=' => '', 'telefono !=' => '', 'ciudad !=' => '' ) ) ){
			if( !$this->usuario->alreadyRegistrer( 'palco_3', array( 'id_user' => $user->id, 'posicion' => 5 ) ) ){
				$this->db->insert( 'palco_3', array( 'id_user' => $user->id, 'fbid_user_invitado' => $user->fbid, 'posicion' => 5, 'nombre_inscrito' => $user->completo ) );				
			}
			$amigos = $this->db->get_where( 'palco_3', array( 'id_user' => $user->id ) )->result();
			foreach ( $amigos as $key => $row ){
				$this->posicion [ $row->posicion ] = $row->fbid_user_invitado;
			}
			$data['amigos'] = $this->posicion;
			$this->db->select( 'count(*) as num' );
			$this->db->where( array( 'id_user' => $user->id ) );
			$result = current( $this->db->get( 'palco_3' )->result() )->num;
			if ( $result < 5 ){
				$this->load->view( $this->folder.'/tab_liker', $data );
			}
			else{
				redirect( 'samsung_palco/vista_completo' );	
			}			
		}
		else{
			$this->vista_registro();
		}		
	}
		
	public function vista_registro(){
		$data_user = $this->getFbData();
		$user = $this->usuario->getUserFbid( $data_user['fbdatos']['fbid'] );	
		$data['api_id'] = $this->app_id;
		$data['fondo'] = $this->img_path.'apppalcoregistro.jpg?fbrefresh=1234567986465654655646545664545554654656';
		$data['img_path'] = $this->img_path;
		$data['condiciones'] = $this->condiciones;
		$data['user'] = $this->getFbData();		
		if( isset( $_POST['submit'] ) ){
			$data_update = array(						
					'telefono' => $_POST['telefono'],
					'cedula' => $_POST['cedula'],
					'ciudad' => $_POST['ciudad'],
					'ultima_app' => $this->app_name);
			$this->db->where( array( 'id' => $user->id ) );
			$this->db->update( 'usuarios', $data_update );				
			if( !$this->usuario->alreadyRegistrer( 'palco_3', array( 'id_user' => $user->id, 'posicion' => 5 ) ) ){
				$this->db->insert( 'palco_3', array( 'id_user' => $user->id, 'fbid_user_invitado' => $user->fbid, 'posicion' => 5, 'nombre_inscrito' => $user->completo ) );
			}
			redirect( 'samsung_palco/'.$this->vista_principal );								
		}
		else{			
			$this->load->view( $this->folder.'/vista_registro', $data);			
		}				
	}	
	
	public function init_selected_friend(){
		$data = $this->getFbData();
		$user = $this->usuario->getUserFbid( $data['fbdatos']['fbid']);
		$this->db->select( 'fbid_user_invitado, posicion' );
		$this->db->where( 'id_user', $user->id, FALSE);		
		$this->db->order_by("posicion", "asc");
		$amigos = $this->db->get( 'palco_3' )->result();		
		foreach ( $amigos as $key => $row ){
			$this->posicion[ $row->posicion ] = $row->fbid_user_invitado;
		}		
		echo json_encode( $this->posicion );
	}
	
	public function registrar_amigo(){
		$data_get = $_GET['data'];
		$data = $this->getFbData();
		$user = $this->usuario->getUserFbid( $data['fbdatos']['fbid']);
		if ( $this->usuario->alreadyRegistrer( 'palco_3', array( 'id_user' => $user->id, 'posicion' => $data_get['posicion'] ) ) ){
			$this->db->delete( 'palco_3', array( 'id_user' => $user->id, 'posicion' => $data_get['posicion'] ) );
		}
		$insert_palco = array( 'id_user' => $user->id, 'fbid_user_invitado' => $data_get['id'], 'posicion' => $data_get['posicion'], 'nombre_invitado' => $data_get['name'], 'nombre_inscrito' => $user->completo );
		$this->db->insert( 'palco_3', $insert_palco );
		$this->db->select( 'count(*) as num' );
		$this->db->where( array( 'id_user' => $user->id ) );				
		$result = current( $this->db->get( 'palco_3' )->result() )->num;		
		echo $result;		
	}
	
	public function vista_completo(){
		$data['api_id'] = $this->app_id;
		$data['fondo'] = $this->img_path.'appalcofinal.jpg?fbrefresh=123456789789546465465454564654655';		
		$data['condiciones'] = $this->condiciones;
		$data['img_path'] = $this->img_path;
		$data['user'] = $this->getFbData();
		$user = $this->usuario->getUserFbid( $data['user']['fbdatos']['fbid'] );
		$amigos = $this->db->get_where( 'palco_3', array( 'id_user' => $user->id ) )->result();		
		foreach ( $amigos as $key => $row ){
			$this->posicion [ $row->posicion ] = $row->fbid_user_invitado;
		}	
		$data['amigos'] = $this->posicion;
		$this->load->view( $this->folder.'/tab_completo', $data );				
	}
	
	/* FUNCIONES PRIVADAS */
	
	private function getFbData(){
		
		if(!$this->develop){
			$fbdatos=$this->myfacebook->getThinks('me');
			$data['fbdatos'] = array( 'nombre' => $fbdatos->first_name, 'apellido' => $fbdatos->last_name, 'fbid' => $fbdatos->id, 'completo' => $fbdatos->name, 'mail' => $fbdatos->email, 'genero' => $fbdatos->gender );
			if( $fbdatos->gender == "" )
				$data['fbdatos']['genero'] = "ND";
			$data['facebook'] = $this->myfacebook->getAppData();
		}
		else{
			$this->session->set_userdata( 'fbpage', '007' );
			$data['fbdatos'] = array( 'nombre' => 'nombre_fb', 'apellido' => 'apellido_fb', 'fbid' => 'fbid', 'completo'=> '---', 'mail' => 'a@a.com', 'genero' => 'male' );
			$data['facebook'] = array( 'api_id' => $this->app_id, 'session' => '' );
		}
		
		return $data;
	}
	
	/* END */
}	