<?php 
class Pronostico extends Facebook_Controller{ 
	
	public $data;	
	public $config_file = 'fb_config_halloween';	
	public $_rules = array(
			'nombre' => array('field' => 'nombre', 'label' => 'Nombre', 'rules' => 'trim|required|xss_clean'),
			'ciudad' => array('field' => 'ciudad', 'label' => 'Ciudad', 'rules' => 'trim|required|xss_clean'),
			'mail' => array('field' => 'mail', 'label' => 'Email', 'rules' => 'trim|required|valid_email|xss_clean'),
			'telefono' => array('field' => 'telefono', 'label' => 'Teléfono', 'rules' => 'trim|required|xss_clean|numeric'),
			'cedula' => array('field' => 'cedula', 'label' => 'Cédula', 'rules' => 'trim|required|xss_clean|numeric'));
	
	public function __construct(){
		parent::__construct();
		$this->load->model('samsung_usuario','usuario_samsung');
		$this->load->model('mdl_samsung_halloween','modelo');
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
		$this->data['img_path'] = base_url()."imagenes/samsumg_halloween/";
		$this->data['app_name'] = strtolower( get_class($this) );
		$this->data['folder'] = strtolower( get_class( $this ) );
		$this->data['condiciones'] = "<a href='".base_url()."archivos/REGLAMENTO-DE-TERMINOS-Y-CONDICIONES-PARA-EL-CONCURSO-4G.pdf' target='_blank' >T&eacute;rminos y Condiciones:</a>";	
	}

	
	function index(){
		$this->load->view('samsumg_halloween/index', $this->data );
	}
	
	function noLiker(){
		$this->load->view('samsumg_halloween/no_liker', array( 'data' => $this->data) );
	}
	
	function liker(){
		$this->data['user'] = json_decode( $_POST['user'] );
		$this->data['fb_page'] = json_decode( $_POST['fb_page'] );		
		if( $this->finApp()==TRUE ){
			$this->load->view('samsumg_halloween/fin', array( 'data' => $this->data) );
		}else{		
			if( $this->usuario_samsung->alreadyRegistrer( 'usuarios', array( 'fbid' => $this->data['user']->id ) ) ){
				$user = $this->usuario_samsung->getUserFbid( $this->data['user']->id );
				//$dato=$this->modelo->verificarUser($user->id);
				//if($dato==FALSE)
					$this->ingresoActividad("0");
				//else
					//$this->ingresoActividad("1");
			}
			else{
				$this->ingresoActividad();
			}
		}
	}
	
	function finApp(){
		$dia_fin=11;				
		$dia_actual=(int)date("d");		 		
		if( $dia_actual >= $dia_fin  )
			return TRUE;
		else
			return FALSE;		
	}
	
	
	function register(){
		if( isset( $_POST['nombre'] ) ){
			$this->data['user'] = json_decode( $_POST['user'] );
			$this->data['fb_page'] = json_decode( $_POST['fb_page'] );
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
				$this->db->insert("samsung_halloween",array("id_user"=>$id));				
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
					$this->db->insert("samsung_halloween",array("id_user"=>$id));				
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
	
			$this->data['fb_page'] = json_decode( $_POST['fb_page'] );
			$this->data['user'] = json_decode( $_POST['user'] );
			$user = $this->usuario_samsung->getUserFbid( $this->data['user']->id );
			$this->data['user']->telefono = ( isset( $user->telefono ) ) ? $user->telefono : '';
			$this->data['user']->cedula = ( isset( $user->cedula ) ) ? $user->cedula : '';
			$this->load->view( 'samsumg_halloween/register', array( 'data' => $this->data ) );
		}
	}
	/**************************************************************/
	
	function ingresoActividad($sw="0"){
		$this->data['user'] = json_decode( $_POST['user'] );
		$this->data["participante"]=$this->usuario_samsung->getUserFbid($this->data["user"]->id);				
		$this->data["participanteRegistrado"]=$sw;
		$this->load->view( 'samsumg_halloween/actividad', array( 'data' => $this->data ) );
	}
	
	function ranking(){
		$data["registro"]=$this->modelo->getRanking();
		$this->load->view( 'samsumg_halloween/ranking', $data );
	}
	
	
	function verificarParticipante($id){
		$registro=$this->modelo->buscarUser($id);
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
		$dato['puntos']=$reg->puntos;
		
		$dato["id_user"]=$id;
		$this->load->view('samsumg_halloween/compartir',$dato);
	}
	
	function registrarInvitados($id){
		$arreglo = json_decode($_POST['data']);
		$total   = count($arreglo);
		$dato    = $this->modelo->obtenerCampartidos($id);
		$data['user']= $this->modelo->obtenerRegistro($id);
	
		$val_nuevo = $total + (int)$dato->compartido;
		$data_update = array(
				"compartido" => (string)$val_nuevo);
		$this->db->where(array('id_user' => $id));
		$this->db->update('halloween', $data_update);
		if ($val_nuevo%5==0 || $total > 5)
			echo "C-0";
		else
			echo "I-".($val_nuevo%5);
	}
	
	function agradecimiento(){
		$this->load->view('samsumg_halloween/agradecimiento');
	}
	


}




























