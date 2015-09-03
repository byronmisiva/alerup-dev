<?php
class Samsung_santa extends Facebook_Controller{
	
	public $data;	
	public $config_file = 'fb_config_samsung_santa';	
	public $_rules = array(
			'nombre' => array('field' => 'nombre', 'label' => 'Nombre', 'rules' => 'trim|required|xss_clean'),
			'ciudad' => array('field' => 'ciudad', 'label' => 'Ciudad', 'rules' => 'trim|required|xss_clean'),
			'mail' => array('field' => 'mail', 'label' => 'Email', 'rules' => 'trim|required|valid_email|xss_clean'),
			'telefono' => array('field' => 'telefono', 'label' => 'Teléfono', 'rules' => 'trim|required|xss_clean|numeric'),
			'cedula' => array('field' => 'cedula', 'label' => 'Cédula', 'rules' => 'trim|required|xss_clean|numeric'),
	);
	
	public function __construct(){	
		parent::__construct();	
		$this->load->model('samsung_usuario','usuario');
		$this->config->load($this->config_file);	
		$this->data['appId'] = $this->config->item('facebook_api_id');  // Api Id proporcionado por Facebook
		$this->data['facebook_page'] = $this->config->item('facebook_page');  // Api Id proporcionado por Facebook
		$this->data['signedRequest'] = ( isset($_REQUEST['signed_request'] ) ) ? $_REQUEST['signed_request'] : '' ; //Signed Request en el caso de estar en una Fan Page		
		$this->data['base'] = base_url();		
		$this->data['controler'] = strtolower( get_class($this) ); // Nombre del controlador ( El ombre de la clase de este mismo archivo)
		$this->data['namespace'] = $this->config->item('facebook_namespace'); // Namespace proporcionado por Facebook
		$this->data['permission'] = $this->config->item('facebook_permissions'); // String con los permisos para acceder a la informacion del usuario		
		$this->data['debug'] = json_encode( array( 'develop' =>false, 'like' => true ) ); // Array para configurar el modo Debug y simular "LIKE" y "NO LIKE" del usuario
		$this->data['tabLiker'] =  'liker'; // nombre del metodo que crga la vista de "LIKER"
		$this->data['tabNoLiker'] = 'noLiker'; // nombre del metodo que crga la vista de "NO LIKER"
		$this->data['doesNtCare'] = false; // parametro de configuracion en caso de q la Tab no requiera q el usuario sea o no Fan de la Fan page
		$this->data['content'] = 'content'; // id del div que se actualiza con las vistas de "LIKER", "NO LIKER" , etc
		$this->data['isPageTab'] = true; // parametro de configuracion para setear q la App es una Tab dentro de una Fan Page
		$this->data['data'] = ( $this->uri->segment(3) != "" ) ? $this->uri->segment(3) : "undefined";		
		$this->data['fondo'] = base_url().strtolower( get_class( $this ) )."/01_fondo.jpg";
		$this->data['img_path'] = base_url()."imagenes/samsung_santa/";
		$this->data['app_name'] = strtolower( get_class($this) );
		$this->data['folder'] = strtolower( get_class( $this ) );
		$this->data['condiciones'] = "<a href='".base_url()."archivos/terminos_condiciones.pdf' target='_blank' >aqu&iacute;</a>";
	}
	
	//Metodo que carga la vista cuando el usuario es "NO LIKER" de la "FAN PAGE" y recibe los datos de la Fan Page por POST
	function noLiker(){
		$this->load->view( $this->data['folder'].'/no_liker', array( 'data' => $this->data) );
	}
	
	//Metodo que carga la vista cuando el usuario es "LIKER" de la "FAN PAGE" y recibe los datos del usuario y fan page por POST
function liker(){		
		$this->data['user'] = json_decode( $_POST['user'] );		
		$this->data['fb_page'] = json_decode( $_POST['fb_page'] );		
		if( $this->usuario->alreadyRegistrer( 'usuarios', array( 'fbid' => $this->data['user']->id ) ) ){
			$user = $this->usuario->getUserFbid( $this->data['user']->id );			
			if( $user->ultima_app == $this->data['app_name'] ){				
				$this->db->where( 'id_user', $user->id );
				$this->db->order_by('creado', 'desc');
				$this->db->limit(1);
				$app_data = $this->db->get('samsung_santa')->row();				
				if( count( $app_data) > 0 ){				
					if ( $app_data->complete == 1 ){
						$this->instrucciones();
					}
					else{
						$this->data['register_id'] =  ( isset( $app_data->complete) ) ? $app_data->id : '';						
						$this->data['premio'] = $this->db->get_where( 'santa_premios', array( 'id' => $app_data->premio_id ) )->row();
						$this->data['accesorio'] = $this->db->get_where( 'santa_premios', array( 'id' => $app_data->accessorie_id ) )->row();						
						$this->invitar();
					}
				}						
				else{
					$this->instrucciones();
				}
			}
			else{
				$this->register();
			}
		}
		else{
			$this->register();
		}		
	}
	
	function register(){		
		$this->form_validation->set_rules($this->_rules);		
		if( isset( $_POST['nombre'] ) && $this->form_validation->run() == TRUE ){
			$this->data['user'] = json_decode( $_POST['user'] );
			$this->data['fb_page'] = json_decode( $_POST['fb_page'] );			
			if( $this->usuario->alreadyRegistrer( 'usuarios', array( 'fbid' => $this->data['user']->id ) ) ){
				$updateUser = array(
						'nombre' => $_POST['nombre'],
						//'apellido' => $_POST['apellido'],
						'completo' => $_POST['nombre']." ",
						'mail' => $_POST['mail'],
						'ultima_app' => $this->data['app_name'],
						'ciudad' => $_POST['ciudad'],
						'cedula' => $_POST['cedula'],
						'telefono' => $_POST['telefono'],
						);
				$this->db->update( 'usuarios', $updateUser, array( 'fbid' => $this->data['user']->id ));
			}	
			else{
				$insertUser = array(
						'nombre' => $_POST['nombre'],
						//'apellido' => $_POST['apellido'],
						'completo' => $_POST['nombre']." ",
						'mail' => $_POST['mail'],
						'ultima_app' => $this->data['app_name'],
						'ciudad' => $_POST['ciudad'],
						'cedula' => $_POST['cedula'],
						'telefono' => $_POST['telefono'],
						'fbid' => $this->data['user']->id,
						'genero' => ( isset($this->data['user']->gender) ) ? $this->data['user']->gender : 'ND'
				);
				$this->db->insert( 'usuarios', $insertUser );
			}			
			$this->instrucciones();
		}
		else{				
			$this->data['errors'] = array(
					'nombre' => form_error('nombre') || FALSE,
					'ciudad' => form_error('ciudad') || FALSE,
					'mail' => form_error('mail') || FALSE,
					'telefono' => form_error('telefono') || FALSE,
					'cedula' => form_error('cedula') || FALSE
			);
			$this->data['fb_page'] = json_decode( $_POST['fb_page'] );
			$user = $this->usuario->getUserFbid( $this->data['user']->id );
			$this->data['user']->telefono = ( isset( $user->telefono ) ) ? $user->telefono : '';
			$this->data['user']->cedula = ( isset( $user->cedula ) ) ? $user->cedula : '';
			$this->load->view( $this->data['folder'].'/register', array( 'data' => $this->data ) );
		}				
	}
	
	function instrucciones(){
		$this->load->view( $this->data['folder'].'/instrucciones', array( 'data' => $this->data ) );
	}
	
	function invitar(){
		$this->db->select('count(*) as num');
		$this->db->where( array( 'fbid_user' => $this->data['user']->id, 'register_id' => $this->data['register_id'] ) );
		$this->data['num'] = 5 - $this->db->get( 'samsung_santa_invitados' )->row()->num; 		
		$this->data['accessorie'] = $this->db->get_where( 'santa', array( 'id' => $this->data['register_id'] ) )->row();
		$this->load->view( $this->data['folder'].'/invitar', array( 'data' => $this->data ) );
	}
	
	function lista(){
		$this->db->where( 'premio_id is ', 'NULL', FALSE );
		$this->data['premios'] = $this->db->get('santa_premios')->result();
		$this->db->where( 'premio_id is ', 'NULL', FALSE );
		$this->data['premios'] = $this->db->get('santa_premios')->result();
		$this->load->view($this->data['folder']."/liker", array( 'data' => $this->data ) );
	}
	
	function accesorios(){		
		$this->db->where( 'premio_id ', $this->uri->segment(3), FALSE );
		$this->data['accesorios'] = $this->db->get('santa_premios')->result();		
		$this->load->view($this->data['folder']."/accessories", array( 'data' => $this->data ) );
	}
	
	function register_app(){
		$premio_id = $this->uri->segment(3);
		$accesorio_id = $this->uri->segment(4);
		$this->data['user'] = json_decode( $_POST['user'] );
		$this->data['fb_page'] = json_decode( $_POST['fb_page'] );
		$user = $this->usuario->getUserFbid( $this->data['user']->id ); 
		$insert_app =  array( 
				'id_user' => $user->id,
				'user_name' => $this->data['user']->username,
				'premio_id' => $premio_id,
				'accessorie_id' => $accesorio_id
				);
		$this->db->insert( 'samsung_santa', $insert_app );
		$this->data['register_id'] = $this->db->insert_id();		
		$this->data['premio'] = $this->db->get_where( 'santa_premios', array( 'id' => $premio_id ) )->row();
		$this->data['accesorio'] = $this->db->get_where( 'santa_premios', array( 'id' => $accesorio_id ) )->row();
		$this->invitar();
	}
	
	public function init_friend(){
		$this->data['user'] =  json_decode( $_POST['user'] );		
		$this->data['fb_page'] = json_decode( $_POST['fb_page'] );
		$this->db->select( 'fbid_friend' );
		$this->db->where( 'fbid_user', $this->data['user']->id );
		$this->db->where( 'register_id', $_POST['register_id'] );
		$amigos = $this->db->get( 'samsung_santa_invitados' )->result();
		$ids_friends = array();		
		foreach ( $amigos as $row ){
			array_push($ids_friends, $row->fbid_friend);			 
		}
		echo json_encode( $ids_friends );
	}
	
	public function insert_amigo(){
		$this->data['user'] =  json_decode( $_POST['user'] );		
		$this->data['fb_page'] = json_decode( $_POST['fb_page'] );
		$invitados = json_decode( $_POST['to'] );
		$user = $this->usuario->getUserFbid( $this->data['user']->id );		
		foreach ( $invitados as $value ){			
			$this->db->insert( 'santa_invitados' , array( 'user_id' => $user->id, 'fbid_user' => $this->data['user']->id, 'fbid_friend' => $value, 'register_id' => $_POST['register_id'] ) );	
		}		
		$this->db->select('count(*) as num');
		$this->db->where( array( 'fbid_user' => $this->data['user']->id, 'register_id' => $_POST['register_id'] ) );
		$num = $this->db->get( 'samsung_santa_invitados' )->row()->num;
		if( $num >= 5 ){
			$this->db->update( 'samsung_santa', array( 'complete' => 1 ), array( 'id' => $_POST['register_id'] ) );
		}
		echo $num;
	}
	
	public function finalApp(){
		$this->data['user'] =  json_decode( $_POST['user'] );
		$this->data['fb_page'] = json_decode( $_POST['fb_page'] );
		$this->data['premio'] = $_POST['premio'];
		$this->load->view( $this->data['folder'].'/final', array( 'data' => $this->data ) );
	}
}