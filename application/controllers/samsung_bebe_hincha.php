<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Samsung_bebe_hincha extends CI_Controller {	
	
	var $folder;
	var $app_url;	
	var $condiciones;
	var $iconos;
	var $img_path;
	var $vista_principal;
	var $app_name;	
	var $posicion;
	var $controlador;
	var $cache;
	
	public function __construct(){
		parent::__construct();
		$this->config->load('fb_samsung_bebe_hincha');
		$this->load->library('form_validation');
		$this->load->model('samsung_usuario','usuario'); //usuarios
		$this->load->model('samsung_bebe','modelo'); 
		$this->load->helper(array("form","url","html","date"));	
		$this->folder = 'samsung_bebe_hincha';
		$this->app_name = "samsung_bebe_hincha"; //Nombre de la aplicacion para desarrollo
		
		$this->img_path = base_url().'imagenes/bebe_hincha/app/'; //Path para direccionar las imagenes
		$this->condiciones="
		Aplican restricciones. Concurso DJ Galaxy válido del 20 de Mayo de 2013 al 31 de Mayo de 2013. Movistar se 
		reserva la atribución de extender la fecha de cierre de inscripciones. Se tomará como registro válido aquel 
		que registró una sola vez. Para ser beneficiario del premio tiene que ser fan de Movistar, registrar sus datos 
		correctamente y completar el juego DJ Galaxy según las instrucciones incluidas en la aplicación. El participante 
		puede jugar más de una vez, pero debe compartir la aplicación con 10 de sus amigos en Facebook para desbloquear el juego. 
		El premio es 1 equipo Samsung Galaxy S4 que se sorteará entre todos los participantes. Para recibir el equipo el ganador 
		tiene que cumplir con los siguientes requisitos: Contratar un Plan Smart Movistar o si ya es cliente Movistar, deberá renovar 
		su contrato Movistar y contratar un paquete de datos desde $19,99 en caso de tener uno contratado. Promoción válida solo para 
		planes individuales. Para la contratación de nuevo plan es necesario que el pago mensual del plan contratado sea a través de un 
		débito bancario o tarjeta de crédito. El 4 de Junio de 2013 se realizará el sorteo del equipo entre todos los participantes que 
		cumplieron con las condiciones de registro y participación. Movistar anunciará el ganador a través de su muro en Facebook el 5 de 
		Junio de 2013 para coordinar la entrega del premio con el ganador. Facebook no patrocina, avala ni administra de modo alguno esta 
		promoción, ni está asociado a ella. Eres consciente de que estas proporcionando tu información a Movistar y no a Facebook. La información
		que proporciones estará amparada con la Política de Privacidad de Movistar, revisa  <a href='https://appsm.misiva.com.ec/archivo/Politica_de_
		Privacidad_Movistar_Ecuador_Facebook.pdf'>aquí.</a>.";
		$this->controlador="samsung_bebe_hincha";
		$this->cache=rand(1000, 5000);
	}
	
	/*********Métodos para sistema JS**************/
	public function index(){
		$data['appId'] = $this->config->item('facebook_api_id');
		$data['signedRequest'] = ( isset($_REQUEST['signed_request'] ) ) ? $_REQUEST['signed_request'] : '' ;
		$data['base'] = base_url();
		$data['controler'] = $this->controlador;
		$data['namespace'] = $this->config->item('namespacepruebas');
		$data['permission'] = $this->config->item('facebook_permissions');
		$data['debug'] = json_encode( array( 'develop' => true, 'like' => true ) );
		$data['tabLiker'] =  'liker';
		$data['tabNoLiker'] = 'noLiker';
		$data['doesNtCare'] = false;
		$data['content'] = 'content';
		$data['isPageTab'] = true;
		$data['fondo']=$this->img_path.'fondo.jpg';
		$data['path']=$this->img_path;
			
		if ($this->uri->segment(3)!=""){
			
			$data['data']=$this->uri->segment(3);
		}else 
			$data['data']="0";		
		
		//$data['fondo'] = $this->img_path."bg_general.jpg?fb_refresh=123456";
		$data['condiciones'] = $this->condiciones;
		$this->load->view( $this->folder.'/welcome', $data );
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
	
	function base64_url_decode($input){
		return base64_decode(strtr($input,'-_','+/'));
	}
	
	function  pageTab(){
			$faceapp=$this->config->item('facebook_page');
			$idapp=$this->config->item('facebook_api_id');
			$namespace=$this->config->item('facebook_namespace');
			echo "<script>parent.location.href='https://www.facebook.com/".$faceapp."?v=app_".$idapp."&app_data=".$namespace."*".$this->uri->segment(4)."'</script>";
		
		/*$pageName = ( $this->uri->segment(3) != 'undefined') ? $this->uri->segment(3) : $this->config->item('facebook_page');
		$appId = ( $this->uri->segment(4) != '' ) ? $this->uri->segment(4) : $this->config->item('facebook_api_id');
		$namespace = ( $this->uri->segment(5) != '' ) ? $this->uri->segment(5) : $this->config->item('namespacepruebas');
		$data = ( $this->uri->segment(6) != '' ) ? $this->uri->segment(6) : "";
		
		echo "<script>parent.location.href='https://www.facebook.com/".$pageName."?v=app_".$appId."&app_data=".$namespace."&data=".$data.";</script>";*/
	}
	
	function  pageApp(){
		$namespace = ( $this->uri->segment(3) != '' ) ? $this->uri->segment(3) : $this->config->item('namespacepruebas');
		
		echo "<script>parent.location.href='https://apps.facebook.com/".$namespace."';</script>";
	}
	
	public function init_selected_friend(){
		$user =  json_decode( $_POST['user'] );
		$user_db = $this->usuario->getUserFbid( $user->id );
		$page_data = json_decode( $_POST['fb_page'] );
		$this->db->select( 'fbid_user_invitado, posicion' );
		$this->db->where( 'id_user', $user_db->id, FALSE);
		$this->db->where( 'fb_page', $page_data->page->id, FALSE);
		$this->db->order_by("posicion", "asc");
		$amigos = $this->db->get( 'samsung_mobile_palco' )->result();
		foreach ( $amigos as $key => $row ){
			$this->posicion[ $row->posicion ] = $row->fbid_user_invitado;
		}
		echo json_encode( $this->posicion );
	}
	/****************************/	
	/**********Método para App*****************/	
	function noLiker(){
		$data['api_id'] = $this->config->item('facebook_api_id');
		$data['img_path'] = $this->img_path;
		$data['no_fan'] = $this->img_path.'nofan.jpg?fbrefresh='.$this->cache;
		$this->load->view( $this->folder.'/tab_noliker', $data );
	}
	
	function liker(){		
		$data['api_id'] = $this->config->item('facebook_api_id');
		$data['user'] = json_decode( $_POST['user'] );
		$data['fb_page'] = json_decode( $_POST['fb_page'] );	
		if(isset($data['fb_page']->app_data)){		
			$cadena=($data['fb_page']->app_data);
				if(strlen($cadena)>19){
					$pos=strpos($cadena, "*"); 
					$data['data']=substr($cadena,$pos+1);	
			}
		}else 
			$data['data'] =  $_POST['data'] ;					
		
		$user = $this->usuario->getUserFbid( $data['user']->id );
		$this->vista_completo( $data['user'], $data['fb_page'],$data['data'] );				
	}
	
	public function vista_registro( $user = FALSE, $page_data = FALSE  ){
		if( isset( $_POST['telefono'])){
			if( ($_POST['ciudad']!="Ej: Quito") && ($_POST['cedula']!="Ej: 1720254478") && ($_POST['mail']!="Ej: nombre@dominio.com" ) &&
					($_POST['completo']!="Ej: José Perez" ) &&($_POST['telefono']!='Ej: 099233547')){
					
				$data=$this->usuario->getUserFbid($_POST['fbid']);
				$data_preventa = array(
						'id_user' => $data->id,
						'id_page' => $_POST["id_page"]
				);
				$this->db->insert( 'musical', $data_preventa );
				$data_update = array(
						'telefono' => $_POST['telefono'],
						'cedula' => $_POST['cedula'],
						'ciudad' => $_POST['ciudad'],
						'ultima_app' => $this->app_name);
				$this->db->where( array( 'fbid' =>  $_POST['fbid']) );
				$this->db->update( 'usuarios', $data_update );
			}
		}else{
			$data['user_db'] =( $user !== FALSE ) ? $this->usuario->getUserFbid( $user->id ) : $this->usuario->getUserFbid( $_POST['fbid'] );
			$page_data = ( $page_data != FALSE ) ? $page_data : json_decode( $_POST['fb_page'] );
			$data['pageid']=$page_data->page->id;
			$data['api_id'] = $this->config->item('facebook_api_id');
			$data['img_path'] = $this->img_path;
			$data['user'] = $user;
			$array_insert = array(
					'fbid' => $user->id,
					'nombre' => $user->first_name,
					'apellido' => $user->last_name,
					'completo' => $user->name,
					'mail' => $user->email,
					'genero' => $user->gender,
					'ultima_app' => $this->app_name );
			$this->usuario->newRegister( $array_insert );
				
			if(isset($data['user_db']->cedula) && ($data['user_db']->cedula !=""))
				$data['cedula']=$data['user_db']->cedula;
			else
				$data['cedula']="Ej: 1720254478";
				
			if(isset($data['user_db']->telefono))
				$data['telefono']=$data['user_db']->telefono;
			else
				$data['telefono']="Ej: 0993000547";
				
			if(isset($data['user_db']->ciudad))
				$data['ciudad']=$data['user_db']->ciudad;
			else
				$data['ciudad']="Ej: Quito";
				
			$data['img_fondo']=$this->img_path.'registro.jpg?fbrefresh='.$this->cache;
			$data['enviar']=$this->img_path;
			
			$this->load->view( $this->folder.'/tab_registro', $data);
		}
	}
	
	public function vista_completo( $user = FALSE, $page_data = FALSE,$id_bebe=""){		
		$data['api_id'] = $this->config->item('facebook_api_id');
		$data['img_path'] = $this->img_path;		
		$data['id_bebe']=$id_bebe;
		$page_data = ( $page_data != FALSE ) ? $page_data : json_decode( $_POST['fb_page'] );
		$data['id_page']=$page_data->page->id;		
		$user = ( $user != FALSE ) ? $user : json_decode( $_POST['user'] );				
		$user_db = $this->usuario->getUserFbid( $user->id );
		$data['user']=$user_db;		
		$data['id_page']=$page_data->page->id;		
		$data['img_fondo']=$this->img_path.'fan.jpg?fbrefresh='.$this->cache;
		$this->load->view( $this->folder.'/tab_completo', $data );
	}
	
	function vista_foto($user = FALSE, $page_data = FALSE){	
		$page_data = ( $page_data != FALSE ) ? $page_data : json_decode( $_POST['fb_page'] );
		$user = ( $user != FALSE ) ? $user : json_decode( $_POST['user'] );
		$user_db = $this->usuario->getUserFbid( $user->id );	
		
		if (($user_db!=false)&&(strlen($user_db->ciudad)>0)&&(strlen($user_db->telefono)>0)&&(strlen($user_db->cedula)>0)){
			$data['id_user']=$user_db->id;
			$data['id_page']=$page_data->page->id;
			if ($this->modelo->verificarBebe($data['id_user'])!=true){
				$data['img_fondo']=$this->img_path.'subefoto.jpg?fbrefresh='.$this->cache;
				$data['siguiente']=$this->img_path.'btn_siguiente.jpg?fbrefresh='.$this->cache;
				$this->load->view( $this->folder.'/tab_foto', $data );
			}else{
				$this->vista_galeria($data['id_user'],$data['id_page']);
			}
		}else
			$this->vista_registro( $user, $page_data );
	}
	
	function vista_compartir($id,$page){
		$data['img_fondo']=$this->img_path.'compartir.jpg?fbrefresh='.$this->cache;		
		$this->load->view( $this->folder.'/tab_compartir', $data );
	}
	
	function vista_galeria($id,$page){
		$data['id_user']=$id;
		$data['id_page']=$page;
		$data['img_fondo']=$this->img_path.'galeria.jpg?fbrefresh='.$this->cache;		
		$this->load->view( $this->folder.'/tab_galeria', $data );
	}
	
	function galeriaPop($id,$user,$page){
		$data['id_user']=$user;
		$data['id_page']=$page;
		$data['id_bebe']=$id;
		$data['img_fondo']=$this->img_path.'galeria.jpg?fbrefresh='.$this->cache;
		$this->load->view( $this->folder.'/tab_galeria', $data );
	}
	
	function getVoto($id,$id_user,$id_page){
		$registro=$this->modelo->obtenerVoto($id_user,$id);		
		if ($registro!=true){
			$votos=(int)$registro+1;
			$insertar=array(
					"id_bebe"=>$id,
					"id_page"=>$id_page,
					"id_voto"=>$id_user);			
			if($this->db->insert("bebe_votos",$insertar)!=false){
			$votos_actual=$this->modelo->votosTotales($id);
			$votos_actual=$votos_actual+1;
			$updater=array(
					"puntos"=>$votos_actual);
			$this->db->where('id',$id);
			$this->db->update('bebe_hincha',$updater);				
			}
			echo $votos_actual;
		}else{
			echo 0;
		}
	}
	
	function saveBebe(){
		$id=$this->modelo->insertBebe($_POST);
		echo $id;
	}
	
	public function uploadImage(){	
		$data['script']="";		
		if(isset($_FILES['image'])){			
			$config['upload_path'] = './imagenes/bebe_hincha/galeria/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']	= '5000';
			$config['max_width']  = '3000';
			$config['max_height']  = '3000';
			$config['encrypt_name'] = FALSE;				
			$this->load->library('upload', $config);				
			if ( ! $this->upload->do_upload('image')){
				$error = array('error' => $this->upload->display_errors());					
				$data['script']="<script>parent.cargar_imagen('imagenes/bebe_hincha/galeria/default.png');</script> ";
			}else{
				$data = $this->upload->data();
				$config=array();
				$config['image_library'] = 'gd2';
				$config['source_image']	= $data['full_path'];
				$config['create_thumb'] = FALSE;
				$config['maintain_ratio'] = TRUE;
				$config['master_dim']= 'width';
				$config['width']= 480;
				$config['height'] = 480;
	
				$this->load->library('image_lib', $config);
				$this->image_lib->resize();
				$data['script']="
				<script>
					parent.cargar_imagen('imagenes/bebe_hincha/galeria/".$data['file_name']."');
					$('#imagen').val('imagenes/bebe_hincha/galeria/".$data['file_name']."');					
				</script>";
			}
		}	
		$this->load->view($this->folder.'/tab_image',$data);
	}
	
	
		
	function verificarCompartidos($id_user,$pageid){
		$totalCompartido=$this->modelo->totalCompartidos($id_user,$pageid);
		if(($totalCompartido->compartido>=10)||($totalCompartido->compartido==0))
			$this->vista_pantalla();
		else
			$this->vista_compartir($id_user,$pageid);
	}
	
	/*function vista_compartir($id_user,$pageid){
		$data['totalCompartido']=$this->modelo->totalCompartidos($id_user,$pageid);
		if($data['totalCompartido']->compartido<10){
			$data['img_fondo']=$this->img_path.'mensaje_ganaste.jpg?fbrefresh=12341252777';
			$this->load->view( $this->folder.'/compartir',$data);
		}else{
			$this->vista_pantalla();
		}
	}*/
	
	function vista_pantalla(){
		$data['mascara']=$this->img_path.'mascara.png';
		$data['img_path']=$this->img_path;
		$data['album']=$this->img_path.'album.jpg?fbrefresh=1234145874';
		$this->load->view( $this->folder.'/pantalla', $data );
	}
		
	
	function vista_intento(){
		$data['img_fondo']=$this->img_path.'txt_fallo.jpg?fbrefresh=1234125800';
		$this->load->view( $this->folder.'/intento',$data);
	}
	
	function gracias(){
		$data['img_fondo']=$this->img_path.'gracias.png';
		$this->load->view( $this->folder.'/gracias' ,$data);
	}
	
	public function registrar_amigo(){
		$data_get = $_GET['data'];
		$user = json_decode( $_GET['user'] );
		$page_data = json_decode( $_GET['fb_page'] );
		$user_db = $this->usuario->getUserFbid( $user->id );
		if ( $this->usuario->alreadyRegistrer( 'mobile_palco', array( 'id_user' => $user_db->id, 'posicion' => $data_get['posicion'], 'fb_page' => $page_data->page->id ) ) ){
			$this->db->delete( 'mobile_palco', array( 'id_user' => $user_db->id, 'posicion' => $data_get['posicion'], 'fb_page' => $page_data->page->id ) );
		}
		$insert_palco = array( 'id_user' => $user_db->id, 'fid_invitado' => $data_get['id'], 'posicion' => $data_get['posicion'], 'nombre_invitado' => $data_get['name'], 'nombre_inscrito' => $user_db->completo, 'fb_page' => $page_data->page->id );
		$this->db->insert( 'mobile_palco', $insert_palco );
		$this->db->select( 'count(*) as num' );
		$this->db->where( array( 'id_user' => $user_db->id, 'fb_page' => $page_data->page->id ) );
		$result = current( $this->db->get( 'mobile_palco' )->result() )->num;
		echo $result;
	}
		
	function getGaleria($grupo="",$bebe=""){
		$data['id_bebe']=$bebe;
		if ($grupo>0)
			$data["anterior"]=$grupo-4;
		else
			$data["anterior"]=$grupo;
		
		$data['galeria']=$this->modelo->galeriaBebe($grupo);
		$data["siguiente"]=$grupo+4;
		$this->load->view( $this->folder.'/tab_seg_galeria' ,$data);
	}
	
	function informacionBebe($id){		
		$data['registro']=$this->modelo->obtenerBebe($id);
		$this->load->view( $this->folder.'/pop_galeria' ,$data);
	}
	
	
	
	/***********************************/
	
	
}