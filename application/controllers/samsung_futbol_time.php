<?php 
class Samsung_futbol_time extends Facebook_Controller{ 
	
	public $data;	
	public $config_file = 'fb_config_navidad2014';	
	public $_rules = array(
			'nombre' => array('field' => 'nombre', 'label' => 'Nombre', 'rules' => 'trim|required|xss_clean'),
			'ciudad' => array('field' => 'ciudad', 'label' => 'Ciudad', 'rules' => 'trim|required|xss_clean'),
			'mail' => array('field' => 'mail', 'label' => 'Email', 'rules' => 'trim|required|valid_email|xss_clean'),
			'telefono' => array('field' => 'telefono', 'label' => 'Teléfono', 'rules' => 'trim|required|xss_clean|numeric'),
			'cedula' => array('field' => 'cedula', 'label' => 'Cédula', 'rules' => 'trim|required|xss_clean|numeric'));
	
	public function __construct(){
		parent::__construct();
		$this->load->model('samsung_usuario','usuario_samsung');
		$this->load->model('mdl_samsung_santa_2014','modelo');
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
		$this->data['img_path'] = base_url()."imagenes/samsumg_navidad2014/";
		$this->data['app_name'] = strtolower( get_class($this) );
		$this->data['folder'] = strtolower( get_class( $this ) );
		$this->data['condiciones'] = "<a href='".base_url()."archivos/trineo/REGLAMENTO-DE-TERMINOS-Y-CONDICIONES-El-Trineo-de-Santa.pdf' target='_blank' >T&eacute;rminos y Condiciones:</a>";
	}
	
	function index(){
		$this->load->library('user_agent');	
		$mobiles=array('Apple iPhone','Apple iPod Touch','Android','Apple iPad');	
		if ($this->agent->is_mobile())
			$this->movil();
		else
		$this->load->view('samsung_navidad2014/index', $this->data );
				
	}
	
	
	function movil(){
		$this->load->view( 'samsung_navidad2014/movil') ;
	}
	
	function noLiker(){
		$this->load->view('samsung_navidad2014/no_liker', array( 'data' => $this->data) );
	}
	
	function check(){
			if(isset($_GET['response']['status'])!= "unknown"){	
				$info['participante']=$_GET['response'];				
				$info['sw']="0";
				$ifbid = $info['participante']['id'];					
				if( $this->usuario->alreadyRegistrer( 'usuarios', array( 'fbid' => $ifbid ) ) ){
					$user = $this->usuario->getUserFbid( $ifbid );
					$user =$this->usuario->getUserFbid( $ifbid );				
					
					$info['usuario']=$this->modelo->buscarUser($user->id);					 
					$data['contenedor']="content";
					$info["directo"]="0";
					$info["participanteRegistrado"]="0";
					$data['cuerpo']=$this->load->view('samsung_navidad2014/actividad',$info,TRUE);
					echo json_encode($data);
				}
				else{					
					$data['contenedor']="content";
					$info["directo"]="0";
					$info["participanteRegistrado"]="0";
					$data['cuerpo']=$this->load->view('samsung_navidad2014/actividad',$info,TRUE);
					echo json_encode($data);
				}			
			}else{				
				$info["participanteRegistrado"]="0";
				$data['contenedor']="content";
				$data['cuerpo']=$this->load->view('samsung_navidad2014/actividad',$info,TRUE);
				echo json_encode($data);
			} 		
	}
	
	function getRanking($id){				
		$data['registros']=$this->modelo->getRanking();
		$this->load->view('samsung_navidad2014/ranking',$data);
	}
	
	function game(){
		$this->load->view('samsung_navidad2014/game');
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
				$this->db->insert("flappy_santa",array("id_user"=>$id));				
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
					$this->db->insert("flappy_santa",array("id_user"=>$id));				
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
			$this->load->view( 'samsung_navidad2014/register', array( 'data' => $this->data ) );
		}
	}
	/**************************************************************/
	
	function ingresoActividad($sw="0"){
		$data['user'] = json_decode( $_POST['user'] );				
		$data["participanteRegistrado"]=$sw;
		$this->load->view( 'samsung_navidad2014/actividad',$data );
	}
	
	function verificarInvitacion($id){		
			$this->db->where("fbid_amigo",$id);
			$this->db->where("estado","0");
			$this->db->from("samsung_halloween");
			$consulta=$this->db->get();
			if ($consulta->num_rows() > 0) {
				$consulta=current($consulta->result());
				$this->db->where('id_user',$consulta->id_user);
				$this->db->where('fbid_amigo',$id);
				$this->db->update("samsung_halloween_invitados",array('estado'=>"1"));			
			}
	}
	
	function verificarParticipante($id){	
		$participante=$this->usuario_samsung->getUserFbid($id);
		$participante=$this->usuario_samsung->getUserFbid($id);
		if( $participante == FALSE)
			echo "F";
		else{
			$registro=$this->modelo->buscarUser($participante->id);		
			if ($registro==FALSE)
				echo "F";
			else
				echo "0";
			}
	}
	
	function verificarAmigo($id){
		$amigos=array();
		$datos=$this->modelo->verAmigos($id);
		foreach ($datos as $row)
			array_push($amigos, $row->fbid_amigo);
		echo json_encode($amigos);
	}
	
	function registrarAmigos($id){
		$arreglo=json_decode($_GET['data']);
		foreach($arreglo as $row){
			$this->db->insert("flappy_santa_invitado",array("fbid_user"=>$id,"fbid_amigo"=>$row));
		}
		
		$participante=$this->usuario_samsung->getUserFbid($id);
		$registro=$this->modelo->buscarUser($participante->id);
		$puntos=(int)$registro->invitar;
		$puntos=$puntos+1;
		$this->db->where("id_user",$participante->id);
		$this->db->update("flappy_santa",array("invitar"=>$puntos));
	}
	
	function insertarPuntaje($id){
		$puntaje=json_decode($_POST['data']);
		$participante=$this->usuario_samsung->getUserFbid($id);
		$registro=$this->modelo->buscarUser($participante->id);		
		$puntosActual=(int)$registro->puntos;
		$juegos=(int)$registro->juegos;
		$juegos=$juegos+1;
		$puntaje=(int)$puntaje;
		if( $puntosActual < $puntaje  ){
			$this->db->where("id_user",$participante->id);
			$this->db->update("flappy_santa",array("puntos"=>$puntaje));
		}
			$this->db->where("id_user",$participante->id);
			$this->db->update("flappy_santa",array("juegos"=>$juegos));
	}
	
	function sumarCompartida($id){
		$participante=$this->usuario_samsung->getUserFbid($id);
		$registro=$this->modelo->buscarUser($participante->id);
		$puntos=(int)$registro->compartidos;
		$puntos=$puntos+2;
		$this->db->where("id_user",$participante->id);
		$this->db->update("flappy_santa",array("compartidos"=>$puntos));
	}
	


}

