<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class samsung_be_cool extends CI_Controller {	
	
	var $folder;
	var $app_url;	
	var $condiciones;
	var $iconos;
	var $img_path;
	var $vista_principal;
	var $app_name;	
	var $posicion;
	var $lista_cool;
	
	public function __construct(){
		parent::__construct();
		$this->config->load('fb_config_be_cool');
		$this->load->model( 'samsung_usuario','usuario' );
		$this->load->helper( array( 'url' ) );	
		$this->folder = 'samsung_be_cool';
		$this->app_name = 'samsung_be_cool'; //Nombre de la aplicacion para desarrollo
		$this->posicion = array( 1 => '', 2 => '', 3 => '', 4 => '', 5 => '' ) ;
		$this->lista_cool = array( 
				1 => 'Realizar mis deberes con mi Samsung Galxy Tab',
				2 => 'Chatear con mis amigos con mi Samsung Galxy Ace',
				3 => 'Consular en internet el deber de sociales con mi Samsung Galaxy Tab',
				4 => 'Poner  la alarma en mi celular', 
				5 => 'Consultar  los ejercicios de mate en mmi Tab',
				6 => 'Leer  el libro en mi  Samsung  Galaxy Tab' ) ;
		$this->img_path = base_url().'imagenes/'.$this->folder.'/'; //Path para direccionar las imagenes
		$this->condiciones="Esta promoci&oacute;n no guarda ning&uacute;n tipo de relaci&oacute;n directa o indirecta con Facebook, Compa&ntilde;ias Afiliadas, Filiales de la misma o Subsidiarias. 
		De la misma manera, Facebook no patrocina, endosa o administra directa o indirectamente esta promoci&oacute;n. Todas las preguntas concernientes a la misma deber&aacute;n 
		ser remitidas directamente a Samsung Electronics Ecuador y NO a Facebook. La informaci&oacute;n recolectada a trav&eacute;s de esta promoci&oacute;n se realiza de manera 
		independiente a Facebook con fines exclusivos de identificaci&oacute;n, al entregarla, el usuario acepta los t&eacute;rminos y condiciones establecidos para la misma. 
		Todas las marcas registradas son propiedad de sus respectivos due&ntilde;os. <a href='".base_url()."archivos/Reglamento_Be_cool_back_to_school.pdf' target='_blank' >aqu&iacute;</a>";
	}

	public function index(){
		$data['appId'] = $this->config->item('facebook_api_id');
		$data['signedRequest'] = ( isset($_REQUEST['signed_request'] ) ) ? $_REQUEST['signed_request'] : '' ;
		$data['base'] = base_url();
		$data['controler'] = 'samsung_be_cool';
		$data['namespace'] = $this->config->item('namespacepruebas');
		$data['permission'] = $this->config->item('facebook_permissions');
		$data['debug'] = json_encode( array( 'develop' => false, 'like' => true ) );
		$data['tabLiker'] =  'liker';
		$data['tabNoLiker'] = 'noLiker';
		$data['doesNtCare'] = false;
		$data['content'] = 'content';
		$data['isPageTab'] = true;
		$data['fondo'] = $this->img_path."01_fondo.jpg?fb_refresh=123";
		$data['tablet'] = $this->img_path."02_tablet.png?fb_refresh=123456545456464654655";
		$data['condiciones'] = $this->condiciones;
		$this->load->view( $this->folder.'/index', $data );
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
		$data['body'] = $this->img_path.'03_pantalla.jpg?fbrefresh=123456789';	
		$data['texto'] = $this->img_path.'04_texto.png?fbrefresh=123456789';
		$this->load->view( $this->folder.'/tab_noliker', $data );		
	}
	
	function liker(){			
		$data['api_id'] = $this->config->item('facebook_api_id');		
		$data['img_path'] = $this->img_path;		
		$data['user'] = json_decode( $_POST['user'] );
		$data['fb_page'] = json_decode( $_POST['fb_page'] );		
		$data['fondo_like'] = $this->img_path.'03_pantalla.jpg?fbrefresh=123456789';
		$data['btn_siguiente'] = $this->img_path.'02_botonsiguiente.png?fbrefresh=123456789646545';
		$data['texto'] = $this->img_path.'01_txtfan.png?fbrefresh=123456789';
		$data['fondo_registro'] = $this->img_path.'01_pantalla_registro.jpg?fbrefresh=123456789';
		$data['fondo_formulario'] = $this->img_path.'02_datos.png?fbrefresh=123456789';
		$data['fondo_lista'] = $this->img_path.'01_pantalla.jpg?fbrefresh=123456789';
		$data['items_lista'] = $this->img_path.'02_txt.png?fbrefresh=123456789';		
		$data['fondo_compartir'] = $this->img_path.'01_pantalla.jpg?fbrefresh=123456789';
		$data['texto_compartir'] = $this->img_path.'02_texto.png?fbrefresh=123456789';
		$data['nina_on'] = $this->img_path.'03_botonninaseleccionada.png?fbrefresh=123456789';
		$data['nina_off'] = $this->img_path.'03_1_botonninaapagada.png?fbrefresh=123456789';
		$data['nino_on'] = $this->img_path.'04_caraninoseleccionado.png?fbrefresh=123456789';
		$data['nino_off'] = $this->img_path.'04_1_caraninoapagado.png?fbrefresh=123456789';
		$data['texto_final'] = $this->img_path.'O2_texto_final.png?fbrefresh=123456789';
		$array_insert = array(
		 		'fbid' => $data['user']->id,
		 		'nombre' => $data['user']->first_name,
		 		'apellido' => $data['user']->last_name,
		 		'completo' => $data['user']->name,
		 		'mail' => $data['user']->email,
		 		'genero' => $data['user']->gender,
		 		'ultima_app' => $this->app_name);			
		$this->usuario->newRegister( $array_insert );
		$user = $this->usuario->getUserFbid( $data['user']->id );
		$data['user_db'] =  $user;
		$amigos = $this->db->get_where( 'be_cool', array( 'id_user' => $user->id, 'fb_page' => $data['fb_page']->page->id, 'tipo_registro' => 0 ) )->result();		
		$num_items = $this->db->get_where( 'be_cool', array( 'id_user' => $user->id, 'fb_page' => $data['fb_page']->page->id, 'tipo_registro' => 1 ) )->result();
		foreach ( $amigos as $key => $row ){
			$this->posicion [ $row->posicion ] = $row->fbid_invitado;
		}	
		
		$data['amigos'] = $this->posicion;
		$data['show_registro'] = "display: none;";
		$data['height'] = "1480";	
		$data['top'] = "0";
		if( !$this->usuario->alreadyRegistrer( 'usuarios', array( 'fbid' => $data['user']->id, 'cedula !=' => '', 'telefono !=' => '', 'ciudad !=' => '', 'genero !=' => '' ) ) ){			
			$data['show_registro'] = "";
			$data['height'] = "1850";
		}		
		if ( count( $num_items ) >= 3 ){
			$data['top'] = "-740";
		}
		if ( count( $amigos ) >= 5 ){
			$data['top'] = "-1110";
		}
		
		$this->load->view( $this->folder.'/tab_liker', $data );			
	}
	
	public function registro_user(){					
		if( isset( $_POST['telefono'] ) ){			
			$data_update = array(
					'telefono' => $_POST['telefono'],
					'cedula' => $_POST['cedula'],
					'ciudad' => $_POST['ciudad'],
					'genero' => $_POST['genero'],
					'ultima_app' => $this->app_name);			
			$this->db->where( array( 'fbid' => $_POST['fbid'] ) );
			$this->db->update( 'usuarios', $data_update );			
		}		
	}
	
	public function registrar_amigo(){		
		$data_get = $_GET['data'];
		$user = json_decode( $_GET['user'] ) ;	
		$page_data = json_decode( $_GET['fb_page'] );	
		$user_db = $this->usuario->getUserFbid( $user->id );
		if ( $this->usuario->alreadyRegistrer( 'be_cool', array( 'id_user' => $user_db->id, 'posicion' => $data_get['posicion'], 'fb_page' => $page_data->page->id, 'tipo_registro' => 0 ) ) ){
			$this->db->delete( 'be_cool', array( 'id_user' => $user_db->id, 'posicion' => $data_get['posicion'], 'fb_page' => $page_data->page->id, 'tipo_registro' => 0 ) );
		}
		$insert_be_cool = array( 'fbid' => $user->id, 'id_user' => $user_db->id, 'fbid_invitado' => $data_get['id'], 'posicion' => $data_get['posicion'], 'nombre_invitado' => $data_get['name'], 'nombre_participante' => $user_db->completo, 'fb_page' => $page_data->page->id, 'tipo_registro' => 0 );
		$this->db->insert( 'be_cool', $insert_be_cool );
		$this->db->select( 'count(*) as num' );
		$this->db->where( array( 'id_user' => $user_db->id, 'fb_page' => $page_data->page->id, 'tipo_registro' => 0 ) );
		$result = current( $this->db->get( 'be_cool' )->result() )->num;
		echo $result;
	}
	
	public function registrar_items(){		
		foreach ( $_POST['id_item_lista'] as $key => $value ){
			$insert_items = array( 
					'nombre_participante' => $_POST['nombre_participa'],
					'fbid' => $_POST['fbid'],
					'id_item_lista' => $value,
					'item_lista' => $this->lista_cool[$value],
					'tipo_registro' => 1,
					'id_user' => $_POST['id_user'],
					'fb_page' => $_POST['fb_page']);
			$this->db->insert( 'be_cool',$insert_items );						
		}				
	}
	
	public function init_selected_friend(){
		$user =  json_decode( $_POST['user'] );
		$user_db = $this->usuario->getUserFbid( $user->id );
		$page_data = json_decode( $_POST['fb_page'] );
		$this->db->select( 'fbid_invitado, posicion' );
		$this->db->where( 'id_user', $user_db->id, FALSE);
		$this->db->where( 'fb_page', $page_data->page->id, FALSE);
		$this->db->where( 'tipo_registro', 0, FALSE);
		$this->db->order_by("posicion", "asc");
		$amigos = $this->db->get( 'samsung_be_cool' )->result();
		foreach ( $amigos as $key => $row ){
			$this->posicion[ $row->posicion ] = $row->fbid_invitado;
		}
		echo json_encode( $this->posicion );
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

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
