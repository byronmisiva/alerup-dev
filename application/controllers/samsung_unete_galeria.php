<?php
class Samsung_unete_galeria extends Facebook_Controller{
	
	public $data;	
	public $config_file = 'fb_config_galeria';	
	public $_rules = array(
			'nombre' => array('field' => 'nombre', 'label' => 'Nombre', 'rules' => 'trim|required|xss_clean'),
			'ciudad' => array('field' => 'ciudad', 'label' => 'Ciudad', 'rules' => 'trim|required|xss_clean'),
			'mail' => array('field' => 'mail', 'label' => 'Email', 'rules' => 'trim|required|valid_email|xss_clean'),
			'telefono' => array('field' => 'telefono', 'label' => 'Teléfono', 'rules' => 'trim|required|xss_clean|numeric'),
			'cedula' => array('field' => 'cedula', 'label' => 'Cédula', 'rules' => 'trim|required|xss_clean|numeric'));
	
	public function __construct(){	
		parent::__construct();	
		$this->load->model('samsung_usuarios_galeria','usuarios');
		$this->load->model('samsung_galeria','modelo');
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
		$this->data['img_path'] = base_url()."imagenes/samsung_unete_galeria/";
		$this->data['app_name'] = strtolower( get_class($this) );
		$this->data['folder'] = strtolower( get_class( $this ) );
		$this->data['condiciones'] = "<a href='".base_url()."archivos/REGLAMENTO-DE-TERMINOS-Y-CONDICIONES-PARA-EL-CONCURSO-UNETE.pdf' target='_blank' >T&eacute;rminos y Condiciones:</a>";
	}
	
	function index(){
		$this->load->view( $this->data['folder'].'/index', $this->data );
	}

	function reporteAprobados(){
		$dato['registro']=$this->modelo->getAllaprodos();
		$this->load->view( $this->data['folder'].'/reporte', $dato );
	}
	
	//Metodo que carga la vista cuando el usuario es "NO LIKER" de la "FAN PAGE" y recibe los datos de la Fan Page por POST
	function noLiker(){	
		$this->data['fondo']=base_url()."imagenes/".$this->data['folder']."/fondo.jpg";	
		$this->load->view( $this->data['folder'].'/no_liker', $this->data );
	}
	
	function liker(){
		$this->data['user'] = json_decode( $_POST['user'] );
		$this->load->view( $this->data['folder'].'/liker',  $this->data  );
	}
	
	function register(){
		if( isset( $_POST['nombre'] )  ){
			$this->data['user'] = json_decode( $_POST['user'] );
			$this->data['fb_page'] = json_decode( $_POST['fb_page'] );
			$resultado=$this->usuarios->alreadyRegistrer('usuarios_galeria', array( 'fbid' => $this->data['user']->id ) );
			if( $resultado=="y" ){
				$updateUser = array(
						'nombre' => $_POST['nombre'],
						'apellido' =>$_POST['apellido'],
						'completo' => $_POST['nombre']." ".$_POST['apellido'],
						'mail' => $_POST['mail'],
						'ultima_app' => $this->data['app_name'],
						'ciudad' => $_POST['ciudad'],
						'cedula' => $_POST['cedula'],
						'telefono' => $_POST['telefono']
				);
				$this->db->update('usuarios_galeria', $updateUser, array( 'fbid' => $this->data['user']->id ));
				$id_registro=$this->usuarios->getUserFbid($this->data['user']->id);
				 
				$insertDatosGrupo = array('id_user' => $id_registro->id);
				$this->db->insert('unete_galeria', $insertDatosGrupo );
				$id=$this->db->insert_id();
				echo $id;
			}
			else{
				$insertUser = array(
						'nombre' => $_POST['nombre'],
						'apellido' =>$_POST['apellido'],
						'completo' => $_POST['nombre']." ".$_POST['apellido'],
						'mail' => $_POST['mail'],
						'ultima_app' => $this->data['app_name'],
						'ciudad' => $_POST['ciudad'],
						'telefono' => $_POST['telefono'],
						'cedula' => $_POST['cedula'],
						'fbid' => $this->data['user']->id,
						'genero' => (isset($this->data['user']->gender) ) ? $this->data['user']->gender : 'ND'
				);
				$this->db->insert('usuarios_galeria', $insertUser );
				$id=$this->db->insert_id();
				$insertDatosGrupo = array('id_user' => $id);
				$this->db->insert('unete_galeria', $insertDatosGrupo );
				$id=$this->db->insert_id();
				echo $id;
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
			$user = $this->usuarios->getUserFbid( $this->data['user']->id );
			$this->data['user']->telefono = ( isset( $user->telefono ) ) ? $user->telefono : '';
			$this->data['user']->cedula = ( isset( $user->cedula ) ) ? $user->cedula : '';
			$this->data['fondo']=base_url()."imagenes/".$this->data['folder']."/fondo.jpg";
			$this->load->view( $this->data['folder'].'/register', array( 'data' => $this->data ) );
		}
	}
	
	function initGame($id){
		$dato['registro']=$this->modelo->getUser($id);	
		$this->load->view( $this->data['folder'].'/game', $dato );
	}	
	
	function ajaxCarga($id){
		$dato['id_user']=$id;
		$this->load->view( $this->data['folder'].'/carganew', $dato );
	}
	
	function ingresoRegistro(){		
		$this->modelo->actualizaRegistro($_POST);
		echo "1";
	}
						
	function ingresoActividad(){
		$this->data['user'] = json_decode( $_POST['user'] );
		$this->data['fb_page'] = json_decode( $_POST['fb_page'] );
		if( $this->usuarios->alreadyRegistrer( 'usuarios_galeria', array( 'fbid' => $this->data['user']->id ) ) ){
			$user = $this->usuarios->getUserFbid( $this->data['user']->id );
			if ($user != "0"){
				if($this->modelo->verificarUser($user->id) == FALSE)
					$this->register();
				else{
					$dato=$this->modelo->buscarUser($user->id);
					if($dato->compartidos=="0")
						$this->initGame($dato->id);
					elseif($dato->compartidos%5==0)
						$this->initGame($dato->id);
					else
						$this->initGame($dato->id);
				}
			}else
				$this->register();
		}
		else
			$this->register();
	}
	
	function getGaleria(){
		$data['registro']=$this->modelo->getAll();	
		$data['inicio']=0;
		$data['fin']=12;
		$this->load->view( $this->data['folder'].'/ajaxgaleria',$data );
	}
	
	function jsGaleria($inicio,$fin,$opcion){
		$opcion=(int)$opcion;
		
		if($opcion==0){
			$inicio=$inicio-12;
			$fin=$fin-12;
		}
		else{
			$inicio=$inicio+12;
			$fin=$fin+12;
		}		
		$data['registro']=$this->modelo->galeriaPaginacion($inicio,$fin);
	
		$data['inicio']=$inicio;
		$data['fin']=$fin;
		$this->load->view( $this->data['folder'].'/ajaxgaleria',$data );
	}
	
	function adminGaleria(){
		$data['registro']=$this->modelo->getImagenAprobar();
		$this->load->view( $this->data['folder'].'/imagens_aprobar',$data );
	}
	
	function actualizarAdmin(){
		$data['registro']=$this->modelo->getImagenAprobar();
		$this->load->view( $this->data['folder'].'/view_admin_galeria',$data );
	}
	
	function procesoActualizar($id,$parametro){		
		$this->db->where("id",$id);
		$this->db->update("galeria",array("activo"=>$parametro));
		echo "1";
	}
	
	function imagenPieza(){
		$data['script']="";
		if(isset($_FILES['image'])){
			$config['upload_path'] = './imagenes/samsung_unete_galeria/user_galeria/';
			$config['allowed_types'] = 'jpeg|jpg|png';
			$config['max_size']	= '200000';
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('image')){
				$error = array('error' => $this->upload->display_errors());
				$data['script']="<script>alert('Error... Al cargar imagen');parent.$('#carga').hide();</script> ";
			}else{
				$upload=$this->upload->data();	
				$nombre="imagenes/samsung_unete_galeria/user_galeria/".$upload["file_name"];
				$thumb=$this->crear_thumb($upload["file_name"]);				
				$data['script']="
						
				<script>
					parent.$('#archivo').val('".$nombre."');
					parent.$('#thumb').val('".$thumb."');					
					/*alert('El Archivo fue cargado correctamente');*/	
					fotos('".$nombre."');					
				</script>";
			}
		}
		$this->load->view( $this->data['folder'].'/cargaimagen',$data );
	}
	
	
	function crear_thumb($imagen){
		$base_imagen='imagenes/samsung_unete_galeria/user_galeria/thumbs/';
		$base_fuente='imagenes/samsung_unete_galeria/user_galeria/';
		$config['image_library'] = 'gd2';
		$config['source_image'] = './'.$base_fuente.$imagen;
		$config['maintain_ratio'] = FALSE;
		$config['create_thumb'] = FALSE;
		$config['quality'] = "50%";		
		$config['width'] = 99;
		$config['height'] =151;
		$config['new_image'] = './'.$base_imagen.$imagen;
		$this->load->library('image_lib',$config);
		if (!$this->image_lib->resize()){
			echo $this->image_lib->display_errors('<p>', '</p>');
		}
		return $base_imagen.$imagen;
	}
	
	function gracias(){
		$this->data['fondo']=base_url()."imagenes/".$this->data['folder']."/fondo.jpg";
		$this->load->view( $this->data['folder'].'/gracias',  $this->data  );
	}
	
	function agradecimiento(){		
		$this->load->view( $this->data['folder'].'/agradecimiento');
	}
					
	/*function compartir($id){				
		$reg = $this->modelo->obtenerCampartidos($id);		
		$data['compartidos']=((int)$reg->compartidos%5);
		$dato["id_user"]=$id;	
		$dato=$this->modelo->buscarUser($id);
		$data['registro']=$this->modelo->getRanking();
		$data['puntos']=$dato->goles;
		$data['id_user']=$dato->id_user;
		$this->load->view( $this->data['folder'].'/ranking', $data );		
	}*/	
	
	function registrarInvitados($id){
		$arreglo = json_decode($_POST['data']);
		$total   = count($arreglo);
		
		foreach($arreglo as $row){
			$this->db->insert("unete_galeria_invitados",array("id_user"=>$id,"fbid"=>$row));
		}		
		if($total>0){
			$dato    = $this->modelo->obtenerCampartidos($id);
			$data['user']= $this->modelo->obtenerRegistro($id);		
			$val_nuevo = $total + (int)$dato->compartidos;
			$data_update = array(
				"compartidos" => (string)$val_nuevo);
			$this->db->where(array('id_user' => $id));
			$this->db->update('unete_galeria', $data_update);
			if ($val_nuevo%5==0 || $total > 5)
				echo "C-0";
			else
				echo "I-".($val_nuevo%5);
		}		
	}
	
	function verificarAmigo($id){
		$amigos=array();
		$datos=$this->modelo->verAmigos($id);
		foreach ($datos as $row)
			array_push($amigos, $row->fbid);
		echo json_encode($amigos);
	}
		
	
	
	
	
			
	
	
	
}
















