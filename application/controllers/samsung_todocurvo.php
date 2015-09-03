<?php 
class Samsung_todocurvo extends Facebook_Controller{ 
	
	public $data;	
	public $config_file = 'fb_config_todocurvo';	
	public $_rules = array(
			'nombre' => array('field' => 'nombre', 'label' => 'Nombre', 'rules' => 'trim|required|xss_clean'),
			'ciudad' => array('field' => 'ciudad', 'label' => 'Ciudad', 'rules' => 'trim|required|xss_clean'),
			'mail' => array('field' => 'mail', 'label' => 'Email', 'rules' => 'trim|required|valid_email|xss_clean'),
			'telefono' => array('field' => 'telefono', 'label' => 'Teléfono', 'rules' => 'trim|required|xss_clean|numeric'),
			'cedula' => array('field' => 'cedula', 'label' => 'Cédula', 'rules' => 'trim|required|xss_clean|numeric'));
	
	public function __construct(){
		parent::__construct();
		$this->load->model('samsung_usuario','usuario_samsung');
		$this->load->model('mdl_samsung_todocurvo','modelo');
		$this->load->helper('form');
		$this->config->load($this->config_file);
		$this->data['appId'] = $this->config->item('facebook_api_id');  // Api Id proporcionado por Facebook
		$this->data['facebook_page'] = $this->config->item('facebook_page');  // Api Id proporcionado por Facebook
		$this->data['signedRequest'] = ( isset($_REQUEST['signed_request'] ) ) ? $_REQUEST['signed_request'] : '' ; //Signed Request en el caso de estar en una Fan Page
		$this->data['base'] = base_url();
		$this->data['controler'] = strtolower( get_class($this) ); // Nombre del controlador ( El ombre de la clase de este mismo archivo)
		$this->data['namespace'] = $this->config->item('facebook_namespace'); // Namespace proporcionado por Facebook
		$this->data['permission'] = $this->config->item('facebook_permissions'); // String con los permisos para acceder a la informacion del usuario
		$this->data['debug'] = json_encode( array( 'develop' =>FALSE, 'like' => TRUE ) ); // Array para configurar el modo Debug y simular "LIKE" y "NO LIKE" del usuario
		$this->data['tabLiker'] =  'liker'; // nombre del metodo que crga la vista de "LIKER"
		$this->data['tabNoLiker'] = 'noLiker'; // nombre del metodo que crga la vista de "NO LIKER"
		$this->data['doesNtCare'] = false; // parametro de configuracion en caso de q la Tab no requiera q el usuario sea o no Fan de la Fan page
		$this->data['content'] = 'content'; // id del div que se actualiza con las vistas de "LIKER", "NO LIKER" , etc
		$this->data['isPageTab'] = true; // parametro de configuracion para setear q la App es una Tab dentro de una Fan Page
		$this->data['data'] = ( $this->uri->segment(3) != "" ) ? $this->uri->segment(3) : "undefined";
		$this->data['fondo'] = base_url().strtolower( get_class( $this ) )."/01_fondo.jpg";
		$this->data['img_path'] = base_url()."imagenes/samsung_todocurvo/";
		$this->data['app_name'] = strtolower( get_class($this) );
		$this->data['folder'] = strtolower( get_class( $this ) );
		$this->data['condiciones'] = "
				<a href='".base_url()."archivos/samsung_todocurvo/reglamento.pdf.pdf' 
				   target='_blank' >T&eacute;rminos y Condiciones:</a>";	
	}

	
	function index(){
		$this->load->view('samsung_todocurvo/index', $this->data );
	}
	
	function noLiker(){
		$this->load->view('samsung_todocurvo/no_liker', array( 'data' => $this->data) );
	}
	
	function liker(){
		$this->data['user'] = json_decode( $_POST['user'] );
		if( $this->usuario_samsung->alreadyRegistrer( 'usuarios', array( 'fbid' => $this->data['user']->id ) ) ){
			$user = $this->usuario_samsung->getUserFbid( $this->data['user']->id );
			$this->ingresoActividad("0");
		}
		else
			$this->ingresoActividad();		
	}
	
	function register(){
		if( isset( $_POST['nombre'] ) ){
			$this->data['user'] = json_decode( $_POST['user'] );			
			if( $this->usuario_samsung->alreadyRegistrer( 'usuarios', array( 'fbid' => $this->data['user']->id ) ) ){
				$updateUser = array(
						'nombre' => $_POST['nombre'],
						//'apellido' => $_POST['apellido'],
						'completo' => $_POST['nombre']." ".$_POST['apellido'],
						'mail' => $_POST['mail'],
						'ultima_app' => $this->data['app_name'],
						'ciudad' => $_POST['ciudad'],
						'cedula' => $_POST['cedula'],
						'telefono' => $_POST['telefono']
				);				
				$this->db->update( 'usuarios', $updateUser, array( 'fbid' => $this->data['user']->id ));
				$participante=$this->usuario_samsung->getUserFbid($this->data['user']->id);
				$id = $participante->id;
				$this->db->insert("todocurvo",array("id_user"=>$id));				
				$resp="1";
			}
			else{
				$insertUser = array(
						'nombre' => $_POST['nombre'],
						//'apellido' => $_POST['apellido'],
						'completo' => $_POST['nombre']." ".$_POST['apellido'],
						'mail' => $_POST['mail'],
						'ultima_app' => $this->data['app_name'],
						'ciudad' => $_POST['ciudad'],
						'cedula' => $_POST['cedula'],
						'telefono' => $_POST['telefono'],
						'fbid' => $this->data['user']->id
						//'genero' => ( isset($this->data['user']->gender) ) ? $this->data['user']->gender : 'ND'
				);				
					$this->db->insert( 'usuarios', $insertUser );
					$id = $this->db->insert_id();
					$this->db->insert("todocurvo",array("id_user"=>$id));				
				$resp="1";
			}
			echo $resp;
		}
		else{
			$this->data['errors'] = array(
					'nombre' => form_error('nombre') || FALSE,
					'ciudad' => form_error('ciudad') || FALSE,
					'mail' => form_error('mail') || FALSE,
					'telefono' => form_error('telefono') || FALSE,
					'cedula' => form_error('cedula') || FALSE
			);
			$this->data['user'] = json_decode( $_POST['user'] );
			$user = $this->usuario_samsung->getUserFbid( $this->data['user']->id );
			$this->data['user']->telefono = ( isset( $user->telefono ) ) ? $user->telefono : '';
			$this->data['user']->cedula = ( isset( $user->cedula ) ) ? $user->cedula : '';
			$this->load->view( 'samsung_todocurvo/register', array( 'data' => $this->data ) );
		}
	}
	/**************************************************************/
	
	function ingresoActividad($sw="0"){
		$data['elementos']=array("teclado","pescado","pizza","teclado","teclado",
					 			 "teclado","teclado","teclado","teclado","teclado");
		$data['elemento1']=$this->getElemento();
		$data['elemento2']=$this->getElemento2($data['elemento1']);
		if ( $data['elemento2']== "" )
			$data['elemento2']=$this->getElemento2($data['elemento1']);		
		$data['user'] = json_decode( $_POST['user'] );						
		$participanteRegistrado=$sw;
		$this->load->view( 'samsung_todocurvo/actividad', $data);
	}
	
	function getElemento(){
		return rand(0, 9);
	}
	
	function getElemento2($valor){
		$dato = rand(0, 9);
		if ((int)$dato == (int)$valor)
			$this->getElemento2($valor);
		else 
			return $dato;
			
	}
	
	function verificarParticipante($id){
		$participante=$this->usuario_samsung->getUserFbid($id);		
		$registro=$this->modelo->buscarUser($participante->id);
		if ($registro==FALSE)
			echo "F";
		else
			echo "0";
	}
	function actualizarPuntaje($idUser,$totalPuntaje){
		$this->modelo->actualizarPuntaje($idUser,$totalPuntaje);	
	}
	
	function compartir($id){
		$reg    = $this->modelo->obtenerCampartidos($id);
		$dato['compartido']=$reg->compartido;
		$dato["id_user"]=$id;
		$this->load->view('samsung_todocurvo/compartir',$dato);
	}

	function registroActividad($id){
		$participante=$this->usuario_samsung->getUserFbid($id);
		$reg   = $this->modelo->obtenerCampartidos($participante->id);
		$compartido=(int)$reg->compartidos ;
		$actividad= (int) $reg->actividades;
		$compartido=$compartido+1;
		$actividad = $actividad+1;
		
		$actualizarReg=array("compartidos"=>$compartido,"actividades"=>$actividad);
		$this->db->where("id",$participante->id);
		$this->db->update("samsung_todocurvo",$actualizarReg);
		var_dump($reg)
	}
		
	function agradecimiento(){
		$this->load->view('samsung_todocurvo/agradecimiento');
	}
	


}




























