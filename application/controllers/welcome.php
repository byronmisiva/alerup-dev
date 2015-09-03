<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Welcome extends CI_Controller {	
	
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
		$this->config->load('fb_config_welcome');
		$this->load->model( 'samsung_usuario','usuario' );
		$this->load->helper( array( 'url' ) );	
		$this->folder = 'samsung_mobile_palco';
		$this->app_name = 'samsung_mobile_palco'; //Nombre de la aplicacion para desarrollo
		$this->posicion = array( 1 => '', 2 => '', 3 => '', 4 => '', 5 => '' ) ;
		$this->img_path = base_url().'imagenes/'.$this->folder.'/'; //Path para direccionar las imagenes
		$this->condiciones="Esta promoci&oacute;n no guarda ning&uacute;n tipo de relaci&oacute;n directa o indirecta con Facebook, Compa&ntilde;ias Afiliadas, Filiales de la misma o Subsidiarias. 
		De la misma manera, Facebook no patrocina, endosa o administra directa o indirectamente esta promoci&oacute;n. Todas las preguntas concernientes a la misma deber&aacute;n 
		ser remitidas directamente a Samsung Electronics Ecuador y NO a Facebook. La informaci&oacute;n recolectada a trav&eacute;s de esta promoci&oacute;n se realiza de manera 
		independiente a Facebook con fines exclusivos de identificaci&oacute;n, al entregarla, el usuario acepta los t&eacute;rminos y condiciones establecidos para la misma. 
		Todas las marcas registradas son propiedad de sus respectivos due&ntilde;os. <a href='".base_url()."archivos/reglamento_en_primera_fila.pdf' target='_blank' >aqu&iacute;</a>";
	}

	public function index(){
		$data['appId'] = $this->config->item('facebook_api_id');
		$data['signedRequest'] = ( isset($_REQUEST['signed_request'] ) ) ? $_REQUEST['signed_request'] : '' ;
		$data['base'] = base_url();
		$data['controler'] = 'welcome';
		$data['namespace'] = $this->config->item('namespacepruebas');
		$data['permission'] = $this->config->item('facebook_permissions');
		$data['debug'] = false;
		$data['tabLiker'] =  'liker';
		$data['tabNoLiker'] = 'noLiker';
		$data['doesNtCare'] = false;
		$data['content'] = 'content';
		$data['isPageTab'] = true;
		$data['fondo'] = $this->img_path."bg_general.jpg?fb_refresh=123456";
		$data['condiciones'] = $this->condiciones;
		$this->load->view( $this->folder.'/welcome_message', $data );
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
	
	function noLiker(){
		$data['api_id'] = $this->config->item('facebook_api_id');
		$data['img_path'] = $this->img_path;
		$data['info'] = $this->img_path.'info_fan.png?fbrefresh=123456789';
		$data['celu'] = $this->img_path.'cel_inferior.png?fbrefresh=123456789';
		$data['palco'] = $this->img_path.'cel_vertical.png?fbrefresh=123456789';		
		$this->load->view( $this->folder.'/tab_noliker', $data );		
	}
	
	function liker(){			
		$data['api_id'] = $this->config->item('facebook_api_id');		
		$data['img_path'] = $this->img_path;		
		$data['user'] = json_decode( $_POST['user'] );
		$data['fb_page'] = json_decode( $_POST['fb_page'] );		
		$data['celu'] = $this->img_path.'cel_inferior.png?fbrefresh=123456789';
		$data['palco'] = $this->img_path.'cel_vertical.png?fbrefresh=123456789';
		$data['sillas'] = $this->img_path.'box_foto_invitar.png?fbrefresh=123456789';
		$data['info'] = $this->img_path.'info_invitar.png?fbrefresh=123456789';				
		$user = $this->usuario->getUserFbid( $data['user']->id );	
			
		$array_insert = array(
		 		'fbid' => $data['user']->id,
		 		'nombre' => $data['user']->first_name,
		 		'apellido' => $data['user']->last_name,
		 		'completo' => $data['user']->name,
		 		'mail' => $data['user']->email,
		 		'genero' => $data['user']->gender,
		 		'ultima_app' => $this->app_name);			
		$this->usuario->newRegister( $array_insert );		
		if( $this->usuario->alreadyRegistrer( 'usuarios', array( 'fbid' => $data['user']->id, 'cedula !=' => '', 'telefono !=' => '', 'ciudad !=' => '' ) ) ){
			if( !$this->usuario->alreadyRegistrer( 'mobile_palco', array( 'id_user' => $user->id, 'posicion' => 5, 'fb_page' => $data['fb_page']->page->id ) ) ){
				$this->db->insert( 'mobile_palco', array( 'id_user' => $user->id, 'fbid_user_invitado' => $user->fbid, 'posicion' => 5, 'nombre_inscrito' => $user->completo, 'fb_page' => $data['fb_page']->page->id ) );
			}
			$amigos = $this->db->get_where( 'mobile_palco', array( 'id_user' => $user->id, 'fb_page' => $data['fb_page']->page->id ) )->result();
			foreach ( $amigos as $key => $row ){
				$this->posicion [ $row->posicion ] = $row->fbid_user_invitado;
			}
			$data['amigos'] = $this->posicion;
			$this->db->select( 'count(*) as num' );
			$this->db->where( array( 'id_user' => $user->id, 'fb_page' => $data['fb_page']->page->id ) );
			$result = current( $this->db->get( 'mobile_palco' )->result() )->num;
			if ( $result < 5 ){
				$this->load->view( $this->folder.'/tab_liker', $data );
			}
			else{
				$this->vista_completo( $data['user'], $data['fb_page']  );				
			}			
		}
		else{			
			$this->vista_registro( $data['user'], $data['fb_page'] );			
		}
	}
	
	public function vista_registro( $user = FALSE, $page_data = FALSE  ){
		$data['user_db'] =  ( $user !== FALSE ) ? $this->usuario->getUserFbid( $user->id ) : $this->usuario->getUserFbid( $_POST['fbid'] );	
		$data['api_id'] = $this->config->item('facebook_api_id');
		$data['img_path'] = $this->img_path;
		$data['user'] = $user;		
		$data['celu'] = $this->img_path.'cel_inferior_registro.png?fbrefresh=123456789';
		$data['palco'] = $this->img_path.'cel_registro.png?fbrefresh=123456789';		
		$data['info'] = $this->img_path.'enviar_bot.png?fbrefresh=123456789';
		$data['fb_page'] = $page_data;
		if( isset( $_POST['telefono'] ) ){
			var_dump($_POST);
			$data_update = array(
					'telefono' => $_POST['telefono'],
					'cedula' => $_POST['cedula'],
					'ciudad' => $_POST['ciudad'],
					'ultima_app' => $this->app_name);
			$user = $this->usuario->getUserFbid( $_POST['fbid'] );
			$this->db->where( array( 'id' => $_POST['id'] ) );
			$this->db->update( 'usuarios', $data_update );
			if( !$this->usuario->alreadyRegistrer( 'mobile_palco', array( 'id_user' => $_POST['id'], 'posicion' => 5, 'fb_page' => $_POST['fb_page'] ) ) ){
				$this->db->insert( 'mobile_palco', array( 'id_user' => $user->id, 'fbid_user_invitado' => $user->fbid, 'posicion' => 5, 'nombre_inscrito' => $user->completo, 'fb_page' => $_POST['fb_page'] ) );
			}
		}
		else{
			$this->load->view( $this->folder.'/vista_registro', $data);
		}						
	}
	
	public function registrar_amigo(){		
		$data_get = $_GET['data'];
		$user = json_decode( $_GET['user'] );	
		$page_data = json_decode( $_GET['fb_page'] );	
		$user_db = $this->usuario->getUserFbid( $user->id );
		if ( $this->usuario->alreadyRegistrer( 'mobile_palco', array( 'id_user' => $user_db->id, 'posicion' => $data_get['posicion'], 'fb_page' => $page_data->page->id ) ) ){
			$this->db->delete( 'mobile_palco', array( 'id_user' => $user_db->id, 'posicion' => $data_get['posicion'], 'fb_page' => $page_data->page->id ) );
		}
		$insert_palco = array( 'id_user' => $user_db->id, 'fbid_user_invitado' => $data_get['id'], 'posicion' => $data_get['posicion'], 'nombre_invitado' => $data_get['name'], 'nombre_inscrito' => $user_db->completo, 'fb_page' => $page_data->page->id );
		$this->db->insert( 'mobile_palco', $insert_palco );
		$this->db->select( 'count(*) as num' );
		$this->db->where( array( 'id_user' => $user_db->id, 'fb_page' => $page_data->page->id ) );
		$result = current( $this->db->get( 'mobile_palco' )->result() )->num;
		echo $result;
	}
	
	public function vista_completo( $user = FALSE, $page_data = FALSE ){
		$data['api_id'] = $this->config->item('facebook_api_id');		
		$data['img_path'] = $this->img_path;			
		$data['celu'] = $this->img_path.'cel_inferior.png?fbrefresh=123456789';
		$data['palco'] = $this->img_path.'cel_vertical.png?fbrefresh=123456789';
		$data['sillas'] = $this->img_path.'box_foto_invitar.png?fbrefresh=123456789';
		$data['info'] = $this->img_path.'info_invitar.png?fbrefresh=123456789';		
		$data['final'] = $this->img_path."/final.jpg";		
		$user = ( $user != FALSE ) ? $user : json_decode( $_POST['user'] );		
		$page_data = ( $page_data != FALSE ) ? $page_data : json_decode( $_POST['fb_page'] );		
		$user_db = $this->usuario->getUserFbid( $user->id );
		$amigos = $this->db->get_where( 'mobile_palco', array( 'id_user' => $user_db->id, 'fb_page' => $page_data->page->id ) )->result();		
		foreach ( $amigos as $key => $row ){
			$this->posicion [ $row->posicion ] = $row->fbid_user_invitado;
		}	
		$data['amigos'] = $this->posicion;
		$this->load->view( $this->folder.'/tab_completo', $data );	
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
	
	public function init_selected_friend(){
		$user =  json_decode( $_POST['user'] );				
		$user_db = $this->usuario->getUserFbid( $user->id );
		$page_data = json_decode( $_POST['fb_page'] );
		$this->db->select( 'fbid_user_invitado, posicion' );
		$this->db->where( 'id_user', $user_db->id, FALSE);
		$this->db->where( 'fb_page', $page_data->page->id, FALSE);
		$this->db->order_by("posicion", "asc");
		$amigos = $this->db->get( 'samsung_mobile_palco' )->result();
		foreach ( $amigos as $key => $row ){
			$this->posicion[ $row->posicion ] = $row->fbid_user_invitado;
		}
		echo json_encode( $this->posicion );
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
