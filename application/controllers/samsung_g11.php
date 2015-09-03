<?php
class Samsung_g11 extends Facebook_Controller{
		
	public $data;
	public $config_file = 'fb_config_samsung_g11';
	public $_rules = array(
			'nombre' => array('field' => 'nombre', 'label' => 'Nombre', 'rules' => 'trim|required|xss_clean'),
			'ciudad' => array('field' => 'ciudad', 'label' => 'Ciudad', 'rules' => 'trim|required|xss_clean'),
			'mail' => array('field' => 'mail', 'label' => 'Email', 'rules' => 'trim|required|valid_email|xss_clean'),
			'telefono' => array('field' => 'telefono', 'label' => 'Teléfono', 'rules' => 'trim|required|xss_clean|numeric'),
			'cedula' => array('field' => 'cedula', 'label' => 'Cédula', 'rules' => 'trim|required|xss_clean|numeric'));
	
	public function __construct(){	
		parent::__construct();	
		$this->load->model('samsung_usuario','modelouser');
		$this->load->model('samsung_g11_registro','modelo');
		$this->load->helper('form');
		
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
		$this->data['fondo'] = base_url().strtolower( get_class( $this ) )."/fondo.jpg";
		$this->data['img_path'] = base_url()."imagenes/samsung_g11/";
		$this->data['app_name'] = strtolower( get_class($this) );
		$this->data['folder'] = strtolower( get_class( $this ) );
		$this->data['condiciones'] = "<a href='".base_url()."archivos/REGLAMENTO-DE-TERMINOS-Y-CONDICIONES-PARA-EL-CONCURSO-GALAXY-11-FUTBOL-TENIS.pdf' target='_blank' >T&eacute;rminos y Condiciones</a>";
	}
		
	function noLiker(){	
		$this->data['fondo']=base_url()."imagenes/".$this->data['folder']."/fondo.jpg";
		$this->load->view( $this->data['folder'].'/no_liker', $this->data );
	}
	
	function index(){
				
		$this->load->library('user_agent');	
			$mobiles=array('Apple iPhone','Apple iPod Touch','Android','Apple iPad');	
		if ($this->agent->is_mobile()){			
			$this->movil();
		}else{			
			$this->load->view( $this->data['folder'].'/index', $this->data );
		}	
	}
	

	function movil(){
			$this->load->view( $this->data['folder'].'/movil');
	}
		
	function fin_app_movil(){
		$this->load->view( $this->data['folder'].'/fin_app_movil');		
	}
		
	function liker(){		
		$this->data['user'] = json_decode( $_POST['user'] );		
		$this->data['fb_page'] = json_decode( $_POST['fb_page'] );
		if( $this->usuario->alreadyRegistrer( 'usuarios', array( 'fbid' => $this->data['user']->id ) ) ){
			$user = $this->usuario->getUserFbid( $this->data['user']->id );						
			if ($user != "0"){
				if($this->modelo->verificarUser($user->id) == FALSE){					
					$this->load->view( $this->data['folder'].'/liker',  $this->data  );
				}else{
					$this->gracias();
				}
			}else{				
				$this->load->view( $this->data['folder'].'/liker',  $this->data  );				
			}
		}else{
			$this->load->view( $this->data['folder'].'/liker',  $this->data  );
		}
		
	}	
		
	function fin_app(){
		$this->data['fondo']=base_url()."imagenes/".$this->data['folder']."/fondo.jpg";
		$this->load->view( $this->data['folder'].'/fin_app',  $this->data  );		
	}
	
	function fecha_fin(){
		$dia_limite=27;
		$dia_actual=(int)date("d");
		$hora_actual =(int)date("H");
		$hora_limite=24;
		if($dia_actual >=$dia_limite)
			return true;
		else
			return false;
	}
	
	function gracias(){		
		$this->load->view( $this->data['folder'].'/gracias',  $this->data  );
	}
	
	
	function register(){
		if( isset( $_POST['nombre_int1'] )  ){			
			$this->data['user'] = json_decode( $_POST['user'] );
			$this->data['fb_page'] = json_decode( $_POST['fb_page'] );
			$resultado=$this->usuario->alreadyRegistrer( 'usuarios', array( 'fbid' => $this->data['user']->id ) );		
			
			if( $resultado=="y" ){
				$user = $this->usuario->getUserFbid( $this->data['user']->id );				
				$updateUser = array(
						'nombre' => $_POST['nombre_int1'],
						'mail' => $_POST['mail_int1'],
						'telefono' => $_POST['telefono_int1'],
						'cedula' => $_POST['cedula_int1'],
						'ciudad' => " ",												
						'completo' => $_POST['nombre_int1']." ",						
						'ultima_app' => $this->data['app_name']
						);
				$this->db->update( 'usuarios', $updateUser, array( 'fbid' => $this->data['user']->id ));	
				
				$insertinformacion = array(
						'nombre_int1' => $_POST['nombre_int1'],
						'mail_int1' => $_POST['mail_int1'],
						'telefono_int1' => $_POST['telefono_int1'],
						'cedula_int1' => $_POST['cedula_int1'],
						'edad_int1' => $_POST['edad_int1'],
						'nombre_int2' => $_POST['nombre_int2'],
						'telefono_int2' => $_POST['telefono_int2'],
						'mail_int2' => $_POST['mail_int2'],
						'cedula_int2' => $_POST['cedula_int2'],
						'edad_int2' => $_POST['edad_int2'],
						'nombre_equipo' => $_POST['nombre_equipo'],
						'ingreso' => "normal",
						'id_user' => $user->id);
				$this->db->insert( 'samsung_g11_formulario', $insertinformacion );
			}	
			else{
				$insertUser = array(
						'nombre' => $_POST['nombre_int1'],						
						'completo' => $_POST['nombre_int1']." ",
						'mail' => $_POST['mail_int1'],
						'ultima_app' => $this->data['app_name'],
						'ciudad' => " ",
						'telefono' => $_POST['telefono_int1'],
						'cedula' => $_POST['cedula_int1'],
						'fbid' => $this->data['user']->id,
						'genero' => ( isset($this->data['user']->gender) ) ? $this->data['user']->gender : 'ND'
				);
				$this->db->insert( 'usuarios', $insertUser );				
				$id=$this->db->insert_id();
				$insertinformacion = array(
					'nombre_int1' => $_POST['nombre_int1'],
					'mail_int1' => $_POST['mail_int1'],
					'telefono_int1' => $_POST['telefono_int1'],
					'cedula_int1' => $_POST['cedula_int1'],
					'edad_int1' => $_POST['edad_int1'],
					'nombre_int2' => $_POST['nombre_int2'],
					'telefono_int2' => $_POST['telefono_int2'],
					'mail_int2' => $_POST['mail_int2'],
					'cedula_int2' => $_POST['cedula_int2'],
					'edad_int2' => $_POST['edad_int2'],
					'nombre_equipo' => $_POST['nombre_equipo'],
					'ingreso' => "normal",
					'id_user' => $id);
			$this->db->insert( 'samsung_g11_formulario', $insertinformacion );
			}			
			
		}
		else{				
			$this->data['errors'] = array(
					'nombre' => form_error('nombre') || FALSE,
					'ciudad' => form_error('ciudad') || FALSE,
					'mail' => form_error('mail') || FALSE,
					'telefono' => form_error('telefono') || FALSE
			);
			$this->data['fb_page'] = json_decode( $_POST['fb_page'] );
			$user = $this->usuario->getUserFbid( $this->data['user']->id );
			$this->data['user']->telefono = ( isset( $user->telefono ) ) ? $user->telefono : '';
			$this->data['user']->cedula = ( isset( $user->cedula ) ) ? $user->cedula : '';								
			$this->load->view( $this->data['folder'].'/register', array( 'data' => $this->data ) );
		}				
	}
		
	function register_movil(){
		$dato=$this->modelo->buscarRegistro($_POST['cedula_int1']);
		if($dato=="0"){		
			if( isset( $_POST['nombre_equipo'] ) ){			
				$insertinformacion = array(
						'nombre_int1' => $_POST['nombre_int1'],
						'mail_int1' => $_POST['mail_int1'],
						'telefono_int1' => $_POST['telefono_int1'],
						'cedula_int1' => $_POST['cedula_int1'],
						'edad_int1' => $_POST['edad_int1'],
						'nombre_int2' => $_POST['nombre_int2'],
						'telefono_int2' => $_POST['telefono_int2'],
						'mail_int2' => $_POST['mail_int2'],
						'cedula_int2' => $_POST['cedula_int2'],
						'edad_int2' => $_POST['edad_int2'],
						'nombre_equipo' => $_POST['nombre_equipo'],
						'ingreso' => "movil");
				$this->db->insert( 'samsung_g11_formulario', $insertinformacion );
				echo "1";
			}
		}else{
			echo "0";
		}
		
	}	
	
	
	
}
















