<?php
class Samsung_penales extends Facebook_Controller{
	
	public $data;	
	public $config_file = 'fb_config_penales';	
	public $_rules = array(
			'nombre' => array('field' => 'nombre', 'label' => 'Nombre', 'rules' => 'trim|required|xss_clean'),
			'ciudad' => array('field' => 'ciudad', 'label' => 'Ciudad', 'rules' => 'trim|required|xss_clean'),
			'mail' => array('field' => 'mail', 'label' => 'Email', 'rules' => 'trim|required|valid_email|xss_clean'),
			'telefono' => array('field' => 'telefono', 'label' => 'Teléfono', 'rules' => 'trim|required|xss_clean|numeric'),
			'cedula' => array('field' => 'cedula', 'label' => 'Cédula', 'rules' => 'trim|required|xss_clean|numeric'));
	
	public function __construct(){	
		parent::__construct();	
		/*$this->load->model('samsung_usuario','modelouser');*/
		
		$this->load->model('primax_usuarios','modelouser');
		$this->load->model('mdl_bigpromo','modelo');
		//$this->load->model('samsung_penal','modelo');
		//$this->load->model('mdl_bigpromo','modelo');
		$this->load->helper('form');		
		$this->config->load($this->config_file);	
		$this->data['appId'] = $this->config->item('facebook_api_id');  // Api Id proporcionado por Facebook
		$this->data['facebook_page'] = $this->config->item('facebook_page');  // Api Id proporcionado por Facebook
		$this->data['signedRequest'] = ( isset($_REQUEST['signed_request'] ) ) ? $_REQUEST['signed_request'] : '' ; //Signed Request en el caso de estar en una Fan Page		
		$this->data['base'] = base_url();		
		$this->data['controler'] = strtolower( get_class($this) ); // Nombre del controlador ( El ombre de la clase de este mismo archivo)
		$this->data['namespace'] = $this->config->item('facebook_namespace'); // Namespace proporcionado por Facebook
		$this->data['permission'] = $this->config->item('facebook_permissions'); // String con los permisos para acceder a la informacion del usuario		
		$this->data['debug'] = json_encode( array( 'develop' =>FALSE, 'like' => true ) ); // Array para configurar el modo Debug y simular "LIKE" y "NO LIKE" del usuario
		$this->data['tabLiker'] =  'liker'; // nombre del metodo que crga la vista de "LIKER"
		$this->data['tabNoLiker'] = 'noLiker'; // nombre del metodo que crga la vista de "NO LIKER"
		$this->data['doesNtCare'] = false; // parametro de configuracion en caso de q la Tab no requiera q el usuario sea o no Fan de la Fan page
		$this->data['content'] = 'content'; // id del div que se actualiza con las vistas de "LIKER", "NO LIKER" , etc
		$this->data['isPageTab'] = true; // parametro de configuracion para setear q la App es una Tab dentro de una Fan Page
		$this->data['data'] = ( $this->uri->segment(3) != "" ) ? $this->uri->segment(3) : "undefined";		
		$this->data['fondo'] = base_url().strtolower( get_class( $this ) )."/01_fondo.jpg";
		$this->data['img_path'] = base_url()."imagenes/primax_bigpromo/";
		$this->data['app_name'] = strtolower( get_class($this) );
		$this->data['folder'] = strtolower( get_class( $this ) );
		$this->data['condiciones'] = "<a href='".base_url()."archivos/REGLAMENTO-DE-TERMINOS-Y-CONDICIONES-PARA-EL-CONCURSO-PRIMAX.pdf' target='_blank' >T&eacute;rminos y Condiciones:</a>";
	}
		
	function index(){
		$this->load->view( $this->data['folder'].'/index', $this->data );
	}
	
	function noLiker(){
		$this->data['fondo']=base_url("imagenes/primax_bigpromo/no-fan.jpg");
		$this->load->view( $this->data['folder'].'/no_liker', array( 'data' => $this->data) );
	}
	
	function liker(){
		$this->data['user'] = json_decode( $_POST['user'] );
		$this->data['fb_page'] = json_decode( $_POST['fb_page'] );
		if( $this->usuario->alreadyRegistrer( 'usuarios', array( 'fbid' => $this->data['user']->id ) ) ){
			$user = $this->usuario->getUserFbid( $this->data['user']->id );
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
	
	function register(){
		if( isset( $_POST['nombre'] ) ){
			$this->data['user'] = json_decode( $_POST['user'] );
			$this->data['fb_page'] = json_decode( $_POST['fb_page'] );
			if( $this->usuario->alreadyRegistrer( 'usuarios', array( 'fbid' => $this->data['user']->id ) ) ){
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
				$this->db->close();
				$this->db1=$this->load->database('appspr',true);
				$this->db1->update( 'usuarios', $updateUser, array( 'fbid' => $this->data['user']->id ));
				$participante=$this->usuario->getUserFbid($this->data['user']->id);
				$id = $participante->id;
				$this->db1->insert("bigpromo",array("id_user"=>$id));
				$this->db1->close();
				$this->db=$this->load->database('samsung',true);
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
				$this->db->close();
				$this->db1=$this->load->database('appspr',true);
					$this->db1->insert( 'usuarios', $insertUser );
					$id = $this->db1->insert_id();
					$this->db1->insert("bigpromo",array("id_user"=>$id));
				$this->db1->close();
				$this->db=$this->load->database('samsung',true);
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
			$user = $this->usuario->getUserFbid( $this->data['user']->id );
			$this->data['user']->telefono = ( isset( $user->telefono ) ) ? $user->telefono : '';
			$this->data['user']->cedula = ( isset( $user->cedula ) ) ? $user->cedula : '';
			
			$this->load->view( $this->data['folder'].'/register', array( 'data' => $this->data ) );
		}
	}
	/**************************************************************/
	
	function ingresoActividad($sw="0"){
		$this->data['user'] = json_decode( $_POST['user'] );		
		$this->verificarInvitacion($this->data['user']->id);
		
		$this->data["participante"]=$this->usuario->getUserFbid($this->data["user"]->id);
		$registrado=$this->modelo->getRegistradoAmigos($this->data["participante"]->id);
		//$registrado=$this->modelo->getRegistradoAmigos("96784");
			
		IF ($registrado == FALSE)
			$this->data["registrados"]=0;
		else{
			$this->data["registrados"]=$registrado;
			$this->db->close();
			$this->db1=$this->load->database('appspr',true);
				$this->db1->where("id_user",$this->data["participante"]->id);
				$this->db1->update("bigpromo",array("registros_activos"=>$registrado));
			$this->db1->close();
			$this->db=$this->load->database('samsung',true);
		}
		$this->data["participanteRegistrado"]=$sw;
		$this->load->view( $this->data['folder'].'/actividad', array( 'data' => $this->data ) );
	}
	
	function verificarInvitacion($id){
		$this->db->close();
		$this->db1=$this->load->database('appspr',true);
			$this->db1->where("fbid_amigo",$id);
			$this->db1->where("estado","0");
			$this->db1->from("bigpromo_invitados");
			$consulta=$this->db1->get();
			if ($consulta->num_rows() > 0) {
				$consulta=current($consulta->result());
				$this->db1->where('id_user',$consulta->id_user);
				$this->db1->where('fbid_amigo',$id);
				$this->db1->update("bigpromo_invitados",array('estado'=>"1"));			
			}
		$this->db1->close();
		$this->db=$this->load->database('samsung',true);
	}
	
	function verificarParticipante($id){
		$registro=$this->modelo->buscarUser($id);
		if ($registro==FALSE)
			echo "F";
		else
			echo $registro->actividad;
	}
	
	function registrarInvitados($id){
		$arreglo = json_decode($_POST['data']);		
		$data_insert=array(
			"fbid_amigo"=>$arreglo[0],
			"id_user"=>$id);
		$this->db->close();
		$this->db1=$this->load->database('appspr',true);
			$this->db1->insert("bigpromo_invitados",$data_insert);
		$this->db1->close();
		$this->db=$this->load->database('samsung',true);
		echo "1";
				
	}
	
	
	
			
	
	
	
}
















