<?php
class Samsung_doble_mama extends Facebook_Controller{
	
	public $data;	
	public $config_file = 'fb_config_samsung_mama';	
	public $_rules = array(
			'nombre' => array('field' => 'nombre', 'label' => 'Nombre', 'rules' => 'trim|required|xss_clean'),
			'ciudad' => array('field' => 'ciudad', 'label' => 'Ciudad', 'rules' => 'trim|required|xss_clean'),
			'mail' => array('field' => 'mail', 'label' => 'Email', 'rules' => 'trim|required|valid_email|xss_clean'),
			'telefono' => array('field' => 'telefono', 'label' => 'Teléfono', 'rules' => 'trim|required|xss_clean|numeric'),
			'cedula' => array('field' => 'cedula', 'label' => 'Cédula', 'rules' => 'trim|required|xss_clean|numeric'));
	
	public function __construct(){	
		parent::__construct();	
		$this->load->model('samsung_usuario','modelouser');
		$this->load->model('samsung_mama','modelo');
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
		$this->data['img_path'] = base_url()."imagenes/samsung_doble_mama/";
		$this->data['app_name'] = strtolower( get_class($this) );
		$this->data['folder'] = strtolower( get_class( $this ) );
		$this->data['condiciones'] = "<a href='".base_url()."archivos/REGLAMENTO-DE-TERMINOS-Y-CONDICIONES-PARA-EL-CONCURSO-MAMA.pdf' target='_blank' >T&eacute;rminos y Condiciones:</a>";
	}
	
	//Metodo que carga la vista cuando el usuario es "NO LIKER" de la "FAN PAGE" y recibe los datos de la Fan Page por POST
	function noLiker(){	
		$this->data['fondo']=base_url()."imagenes/".$this->data['folder']."/nofan/mama_cierre.jpg";	
		//$this->load->view( $this->data['folder'].'/no_liker', $this->data );
		$this->load->view( $this->data['folder'].'/fin', $this->data );
	}
	
	function index(){
		$this->load->library('user_agent');	
		$mobiles=array('Apple iPhone','Apple iPod Touch','Android','Apple iPad');	
		/*if ($this->agent->is_mobile()){
			$this->movil();
		}else{*/
			$this->load->view( $this->data['folder'].'/index', $this->data );
		/*}*/	
	}
	
	function generarReporte(){
		$dato['registro']=$this->modelo->reporteApp();		
		$this->load->view( $this->data['folder'].'/reportesApp', $dato );
	}
	
	function crearReporte(){
		$registro=$this->modelo->getReporte();		
		header('Content-type: application/vnd.ms-excel;charset=utf-8');
		header("Content-Disposition: attachment; filename=reporteDatos.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		$print = "<table>";
		$print .= "<tr>
			<th>Nombres</th><th>Ciudad</th>
			<th>Cedula</th><th>Telefono</th>
			<th>E-mail</th><th>Compartidos</th>
			<th>Imagenes Generadas</th>";
		$print .= "</tr>";
		foreach($registro as $row){
			$print .= "<tr>
			<td>".$row->completo."</td><td>".$row->ciudad."</td>
			<td>".$row->cedula."</td><td>".$row->telefono."</td>
			<td>".$row->mail."</td><td>".$row->compartidos."</td>
			<td>".$row->generado."</td>";
			$print .= "<tr>";
		}
		$print .= "</table>";		
		echo $print;
		
	} 
	

	function agradecimiento(){
		$this->data['user'] = json_decode( $_POST['user'] );
		$this->data['fb_page'] = json_decode( $_POST['fb_page'] );
		$this->load->view( $this->data['folder'].'/agradecimiento',   $this->data );		
	}


	
	function movil(){		
		/*if ($this->fecha_fin()==true)
			$this->fin_app_movil();
		else{*/
			$this->load->view( $this->data['folder'].'/movil');
		/*}*/		
	}
		
	function fin_app_movil(){
		$this->load->view( $this->data['folder'].'/fin_app_movil');		
	}
	
		
	//Metodo que carga la vista cuando el usuario es "LIKER" de la "FAN PAGE" y recibe los datos del usuario y fan page por POST
	function liker(){
		
		//$this->load->view( $this->data['folder'].'/fin', $this->data );
		
/*		if ($this->fecha_fin()==true)
		 $this->fin_app();
		else{*/
			$this->data['user'] = json_decode( $_POST['user'] );			
			$this->load->view( $this->data['folder'].'/liker',  $this->data  );*/
		/*}				*/
	}
	
	function ingresoActividad(){
		$this->data['user'] = json_decode( $_POST['user'] );
		$this->data['fb_page'] = json_decode( $_POST['fb_page'] );
		if( $this->usuario->alreadyRegistrer( 'usuarios', array( 'fbid' => $this->data['user']->id ) ) ){
			$user = $this->usuario->getUserFbid( $this->data['user']->id );
			if ($user != "0"){
				if($this->modelo->verificarUser($user->id) == FALSE)
					$this->register();
				else{
					$dato=$this->modelo->buscarUser($user->id);
					if($dato->compartido=="0")
						$this->initGame($dato->id);
					elseif($dato->compartido%5==0)
						$this->initGame($dato->id);
					else 
						$this->compartir($dato->id_user);
				}
			}else
				$this->register();
		}
		else
			$this->register();
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
		$this->data['fondo']=base_url()."imagenes/".$this->data['folder']."/fondo.jpg";
		$this->load->view( $this->data['folder'].'/gracias',  $this->data  );
	}
	
	
	function register(){			
		//$this->form_validation->set_rules($this->_rules);		
		if( isset( $_POST['nombre'] )  ){			
			$this->data['user'] = json_decode( $_POST['user'] );
			$this->data['fb_page'] = json_decode( $_POST['fb_page'] );
			$resultado=$this->usuario->alreadyRegistrer( 'usuarios', array( 'fbid' => $this->data['user']->id ) );			
			if( $resultado=="y" ){
				$updateUser = array(
						'nombre' => $_POST['nombre'],						
						'completo' => $_POST['nombre']." ",
						'mail' => $_POST['mail'],
						'ultima_app' => $this->data['app_name'],
						'ciudad' => $_POST['ciudad'],
						'cedula' => $_POST['cedula'],
						'telefono' => $_POST['telefono']
						);
				$this->db->update( 'usuarios', $updateUser, array( 'fbid' => $this->data['user']->id ));				
				$id_registro=$this->usuario->getUserFbid($this->data['user']->id);
				 				
				$insertDatosGrupo = array('id_user' => $id_registro->id);
				$this->db->insert('mama_doble', $insertDatosGrupo );
				$id=$this->db->insert_id();
				echo $id;			
			}	
			else{
				$insertUser = array(
						'nombre' => $_POST['nombre'],						
						'completo' => $_POST['nombre']." ",
						'mail' => $_POST['mail'],
						'ultima_app' => $this->data['app_name'],
						'ciudad' => $_POST['ciudad'],
						'telefono' => $_POST['telefono'],
						'cedula' => $_POST['cedula'],
						'fbid' => $this->data['user']->id,
						'genero' => ( isset($this->data['user']->gender) ) ? $this->data['user']->gender : 'ND'
				);
				$this->db->insert( 'usuarios', $insertUser );				
				$id=$this->db->insert_id();				
				$insertDatosGrupo = array('id_user' => $id);				
				$this->db->insert( 'mama_doble', $insertDatosGrupo );
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
			$user = $this->usuario->getUserFbid( $this->data['user']->id );
			$this->data['user']->telefono = ( isset( $user->telefono ) ) ? $user->telefono : '';
			$this->data['user']->cedula = ( isset( $user->cedula ) ) ? $user->cedula : '';
			$this->data['fondo']=base_url()."imagenes/".$this->data['folder']."/fondo.jpg";					
			$this->load->view( $this->data['folder'].'/register', array( 'data' => $this->data ) );
		}				
	}
	
	function initGame($id){
			$dato['registro']=$this->modelo->getUser($id);			
			$this->load->view( $this->data['folder'].'/preguntas', $dato );		
	}
	
	function registroOpciones(){
		$data['registro']=$_POST;
		$this->db->where("id_user",$_POST['id_user']);
		$this->db->update('mama_doble',$_POST);		
		echo "1";			
	}
	
	function generadorCollage($id){
		$dato["registro"]=$this->modelo->getparametrosImage($id);
		$this->load->view($this->data['folder'].'/preGenerador',$dato);
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
	
	function compartir($id){		
		$reg    = $this->modelo->obtenerCampartidos($id);		
		$dato['compartidos']=$reg->compartido;
		$dato["id_user"]=$id;		
		$this->load->view($this->data['folder'].'/compartir',$dato);
	}
	
	function registrarInvitados($id){
		$arreglo = json_decode($_POST['data']);
		$total   = count($arreglo);
		if($total>0){
			$dato    = $this->modelo->obtenerCampartidos($id);
			$data['user']= $this->modelo->obtenerRegistro($id);
		
			$val_nuevo = $total + (int)$dato->compartido;
			$data_update = array(
				"compartido" => (string)$val_nuevo);
			$this->db->where(array('id_user' => $id));
			$this->db->update('mama_doble', $data_update);
			if ($val_nuevo%5==0 || $total > 5)
				echo "C-0";
			else
				echo "I-".($val_nuevo%5);
		}		
	}
		
	
	function imagenPieza(){
		$dato['script']="";
		if(isset($_FILES['image'])){
			$config['upload_path'] = './imagenes/samsung_doble_mama/grupos/';
			$config['allowed_types'] = 'jpeg|gif|jpg|png';
			$config['max_size']	= '10000000';
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('image')){
				$error = array('error' => $this->upload->display_errors());
				$dato['script']="<script>alert('Error... Al cargar imagen');parent.$('#carga').hide();</script> ";
			}else{
				$upload=$this->upload->data();	
				$nombre="imagenes/samsung_doble_mama/grupos/".$upload["file_name"];
				$tipo=$upload["file_type"];				
				
				$base_imagen='imagenes/samsung_doble_mama/grupos/';
				$destino="imagenes/samsung_doble_mama/thumb/";
				$config['image_library'] = 'gd2';
				$config['source_image'] = './'.$base_imagen.$upload["file_name"];
				$config['maintain_ratio'] = FALSE;
				$config['width'] = 360;
				$config['height'] =386;
				$config['new_image'] = './'.$destino.$upload["file_name"];
				$this->load->library('image_lib',$config);
				if ( !$this->image_lib->resize()){
					echo $this->image_lib->display_errors('<p>', '</p>');
				}else{
					$dato['script']="
					<script>						
						parent.$('#archivo').val('".$destino.$upload["file_name"]."');
						parent.cambioImagen();
						parent.$('#carga').hide();
						parent.$('#imagenCargada').show();
					</script>";
				}
			}
		}
		$this->load->view($this->data['folder'].'/cargaimagen',$dato);
	}
	
	function check(){
		if(isset($_GET['response']['status'])!= "unknown"){	
			$info['usuario']=array("id"=>$_GET['response']['id']);
			$data['contenedor']="content";		
			$data['cuerpo']=$this->load->view($this->data['folder'].'/movil/empezar2',$info,TRUE);
				echo json_encode($data);
			}else{			
				$info['participante']="";
				$data['contenedor']="content";
				$data['cuerpo']=$this->load->view($this->data['folder'].'/movil/empezar1',$info,TRUE);
				echo json_encode($data);
			}
		
	}
	
	
	
			
	
	
	
}
















