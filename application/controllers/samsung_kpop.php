<?php
class Samsung_kpop extends Facebook_Controller{
	
	public $data;	
	public $config_file = 'fb_config_samsung_kpop';	
	public $_rules = array(
			'nombre' => array('field' => 'nombre', 'label' => 'Nombre', 'rules' => 'trim|required|xss_clean'),
			'ciudad' => array('field' => 'ciudad', 'label' => 'Ciudad', 'rules' => 'trim|required|xss_clean'),
			'mail' => array('field' => 'mail', 'label' => 'Email', 'rules' => 'trim|required|valid_email|xss_clean'),
			'telefono' => array('field' => 'telefono', 'label' => 'Teléfono', 'rules' => 'trim|required|xss_clean|numeric'),
			'cedula' => array('field' => 'cedula', 'label' => 'Cédula', 'rules' => 'trim|required|xss_clean|numeric'));
	
	public function __construct(){	
		parent::__construct();	
		$this->load->model('samsung_usuario','usuario');
		$this->load->model('samsung_kpop_modelo','modelo');
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
		$this->data['fondo'] = base_url().strtolower( get_class( $this ) )."/01_fondo.jpg";
		$this->data['img_path'] = base_url()."imagenes/samsung_kpop/";
		$this->data['app_name'] = strtolower( get_class($this) );
		$this->data['folder'] = strtolower( get_class( $this ) );
		$this->data['condiciones'] = "<a href='".base_url()."archivos/terminos_condiciones.pdf' target='_blank' >aqu&iacute;</a>";
	}
	
	//Metodo que carga la vista cuando el usuario es "NO LIKER" de la "FAN PAGE" y recibe los datos de la Fan Page por POST
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
			$this->fin_app_movil();
		/*if ($this->fecha_fin()==true)

		else{
			$this->fin_app_movil();
			$this->load->view( $this->data['folder'].'/movil');
		}*/
		
	}
	
	
	function fin_app_movil(){
		$this->load->view( $this->data['folder'].'/fin_app_movil');		
	}
	
	//Metodo que carga la vista cuando el usuario es "LIKER" de la "FAN PAGE" y recibe los datos del usuario y fan page por POST
function liker(){
	/*$this->fin_app();
	*/
/*	if ($this->fecha_fin()==true)
	 $this->fin_app();
	else{ */

		$this->data['user'] = json_decode( $_POST['user'] );		
		$this->data['fb_page'] = json_decode( $_POST['fb_page'] );		
		if( $this->usuario->alreadyRegistrer( 'usuarios', array( 'fbid' => $this->data['user']->id ) ) ){
			$user = $this->usuario->getUserFbid( $this->data['user']->id );
			if ($user != "0"){				
				if($this->modelo->verificarUser($user->id) == FALSE)
						$this->register();
					else
						$this->gracias();								
			}else
				$this->register();
			
		}
		else			
			$this->register();
	/*}*/
				
	}
	
	function fin_app(){
		$this->data['fondo']=base_url()."imagenes/".$this->data['folder']."/fondo.jpg";
		$this->load->view( $this->data['folder'].'/fin_app',  $this->data  );		
	}
	
	
	
	function fecha_fin(){
		$dia_limite=1;
		$dia_actual=(int)date("d");
		$hora_actual =(int)date("H");
		$hora_limite=24;
		if($dia_actual >=$dia_limite)
			return true;
		else
			return false;
	}
	
	function gracias(){
		$this->data['fondo']=base_url()."imagenes/".$this->data['folder']."/fondo.jpg";
		$this->load->view( $this->data['folder'].'/gracias',  $this->data  );
	}
	
	
	function register(){			
		$this->form_validation->set_rules($this->_rules);		
		if( isset( $_POST['nombre'] )  ){			
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
						'telefono' => $_POST['telefono'],
						);
				$this->db->update( 'usuarios', $updateUser, array( 'fbid' => $this->data['user']->id ));
				
				$id_registro=$this->usuario->getUserFbid($this->data['user']->id);
				 				
				$insertDatosGrupo = array(
						'cantidad' => $_POST['cantidad'],
						'nombre_grupo' => $_POST['nombre_grupo'],
						'email' => $_POST['mail'],
						'cover' => $_POST['cover'],
						'id_user' => $id_registro->id,
						'telefono' => $_POST['telefono']
				);
				$this->db->insert( 'kpop', $insertDatosGrupo );
				$id=$this->db->insert_id();
				$this->sendMail($id);
				
			}	
			else{
				$insertUser = array(
						'nombre' => $_POST['nombre'],						
						'completo' => $_POST['nombre']." ",
						'mail' => $_POST['mail'],
						'ultima_app' => $this->data['app_name'],
						'ciudad' => $_POST['ciudad'],
						'telefono' => $_POST['telefono'],
						'fbid' => $this->data['user']->id,
						'genero' => ( isset($this->data['user']->gender) ) ? $this->data['user']->gender : 'ND'
				);
				$this->db->insert( 'usuarios', $insertUser );
				
				$id=$this->db->insert_id();				
				$insertDatosGrupo = array(
						'cantidad' => $_POST['cantidad'],
						'nombre_grupo' => $_POST['nombre_grupo'],
						'email' => $_POST['mail'],
						'cover' => $_POST['cover'],
						'id_user' => $id,
						'telefono' => $_POST['telefono']
				);
				$this->db->insert( 'kpop', $insertDatosGrupo );
				
				$id=$this->db->insert_id();
				$this->sendMail($id);
			}			
			echo "1";
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
			$this->data['fondo']=base_url()."imagenes/".$this->data['folder']."/fondo.jpg";			
			
			$this->load->view( $this->data['folder'].'/register', array( 'data' => $this->data ) );
		}				
	}
	
	function register_movil(){
		if( isset( $_POST['cantidad'] ) ){			
			$insertDatosGrupo = array(
					'cantidad' => $_POST['cantidad'],
					'nombre_grupo' => $_POST['nombre_grupo'],
					'email' => $_POST['mail'],
					'cover' => $_POST['cover'],
					'origen' => "movil",
					'telefono' => $_POST['telefono']
			);
			$this->db->insert( 'kpop', $insertDatosGrupo );
			$id=$this->db->insert_id();			
			$this->sendMail($id);
		}
		echo "1";
	}
	
		
	function sendMail($id){
		$this->db->where('id',$id);
		$dato=current($this->db->get('kpop')->result());		
	
		$this->load->library('email');
		$config['protocol'] = 'sendmail';
		$config['charset'] = 'utf8';
		$config['mailtype'] = 'html';
		$config['wordwrap'] = FALSE;
	
		$this->email->initialize($config);
		$this->email->from('info@misiva.com.ec','Kpop Concert');
		$this->email->to('jortiz@misiva.com.ec');
	
		$data['informacion']=$dato;
		$body=$this->load->view( $this->data['folder'].'/email',$data,TRUE);
		$this->email->subject("Samsung Kpop Concert ");
		$this->email->message($body);
		$this->email->send();
	}
	
	
	
			
	
	
	
}
