<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Samsung_pdf extends CI_Controller {	
	
	var $folder;
	var $app_url;	
	var $condiciones;
	var $iconos;
	var $img_path;
	var $vista_principal;
	var $app_name;	
	var $cache;
	
	
	public function __construct(){
		parent::__construct();
		$this->config->load('fb_config_samsung_pdf');
		$this->load->model( 'samsung_usuario','usuario' );		
		$this->load->helper( array( 'url','form' ) );	
		$this->folder = 'samsung_pdf';
		$this->app_name = 'samsung_pdf'; //Nombre de la aplicacion para desarrollo
		//$this->cache=rand(1,10000);
		$this->cache="654654898464";
		$this->img_path = base_url().'imagenes/'.$this->folder.'/'; //Path para direccionar las imagenes
				
		$this->condiciones="Esta promoci&oacute;n no guarda ning&uacute;n tipo de relaci&oacute;n directa o indirecta con Facebook, Compa&ntilde;ias Afiliadas, Filiales de la misma o Subsidiarias. 
		De la misma manera, Facebook no patrocina, endosa o administra directa o indirectamente esta promoci&oacute;n. Todas las preguntas concernientes a la misma deber&aacute;n 
		ser remitidas directamente a Samsung Electronics Ecuador y NO a Facebook. La informaci&oacute;n recolectada a trav&eacute;s de esta promoci&oacute;n se realiza de manera 
		independiente a Facebook con fines exclusivos de identificaci&oacute;n, al entregarla, el usuario acepta los t&eacute;rminos y condiciones establecidos para la misma. 
		Todas las marcas registradas son propiedad de sus respectivos due&ntilde;os.";
	}

	public function index(){
		$data['appId'] = $this->config->item('facebook_api_id');
		$data['signedRequest'] = ( isset($_REQUEST['signed_request'] ) ) ? $_REQUEST['signed_request'] : '' ;
		$data['base'] = base_url();
		$data['controler'] = 'samsung_pdf';
		$data['namespace'] = $this->config->item('namespacepruebas');
		$data['permission'] = $this->config->item('facebook_permissions');
		$data['debug'] = json_encode( array( 'develop' => false, 'like' => true ) );
		$data['tabLiker'] =  'liker';
		$data['tabNoLiker'] = 'noLiker';
		$data['doesNtCare'] = false;
		$data['content'] = 'content';
		$data['isPageTab'] = true;
		$data['data'] = "";
		$data['cache']=$this->cache;
		$data['fondo'] = $this->img_path."fondo.jpg?fb_refresh=".$this->cache;		
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
		$pageName = ( $this->uri->segment(3) != 'undefined') ? $this->uri->segment(3) : $this->config->item('facebook_page');
		$appId = ( $this->uri->segment(4) != '' ) ? $this->uri->segment(4) : $this->config->item('facebook_api_id');
		$namespace = ( $this->uri->segment(5) != '' ) ? $this->uri->segment(5) : $this->config->item('namespacepruebas');		
		echo "<script>parent.location.href='https://www.facebook.com/".$pageName."?v=app_".$appId."&app_data=".$namespace."';</script>";
	}
	
	function  pageApp(){		
		$namespace = ( $this->uri->segment(3) != '' ) ? $this->uri->segment(3) : $this->config->item('namespacepruebas');		
		echo "<script>parent.location.href='https://apps.facebook.com/".$namespace."';</script>";
	}
	
	/*****************************/
	function noLiker(){
		$data['api_id'] = $this->config->item('facebook_api_id');				
		$data['nofan']= $this->img_path.'nofan.png?fbrefresh='.$this->cache;		
		$this->load->view( $this->folder.'/tab_noliker', $data );		
	}
	
	function liker(){
		//$data['user'] = json_decode( $_POST['user'] );
		//$data['fb_page'] = json_decode( $_POST['fb_page'] );			
		$this->vista_completo( );		      
	}
	
		
			
	public function vista_completo(  ){		
		$data['img_path'] = $this->img_path;
		$data['archivo1']="window.open('".base_url().'pdf/ATIV_Book 9.pdf'."');";
		$data['archivo2']="window.open('".base_url().'pdf/ATIV_One 7.pdf'."');";
		$data['archivo3']="window.open('".base_url().'pdf/ATIV_TAB 7.pdf'."');";
		$data['info']=$this->img_path.'info2.png?fbrefresh=321321321';
		$this->load->view( $this->folder.'/tab_completo', $data );		
	}
	
	function vistaJuego($tipo){
		$data['jugadores']=$this->modelo->getJugadores();
		if($tipo==0){
			$data['jugadores']=$this->modelo->getJugadores();
			$data['vista']=$this->img_path.'vista1.png?fbrefresh='.$this->cache;			
			$this->load->view( $this->folder.'/vista1', $data );
		}else{			
			$data['vista']=$this->img_path.'vista2.png?fbrefresh='.$this->cache;			
			$this->load->view( $this->folder.'/vista2', $data );
		}		
	}
	
	function insertarDatos($tipo,$eq1,$eq2,$id){
		$_POST["equipo1"]=$eq1;
		$_POST["equipo2"]=$eq2;
		$_POST["tipo"]=$tipo;
		
		if($tipo==1){
			$this->db->where('id_user',$id);						
			$this->db->update('futbol_time',$_POST);
		}else{
			$this->db->where('id_user',$id);
			$this->db->update('futbol_time',$_POST);
		}
	}
	
	function vista_compartido($user,$page){
		$dato=$this->modelo->obtenerCampartidos($user,$page);
		
		if 	($dato->compartido<5){
			$data['compartido']=5-$dato->compartido;
			$data['id_user']=$user;
			$data['page']=$page;
			$data['compartir']=$this->img_path.'compartir.png?fbrefresh='.$this->cache;
			$data['boton1']=$this->img_path.'compartir1.png?fbrefresh='.$this->cache;
			$data['boton2']=$this->img_path.'compartir2.png?fbrefresh='.$this->cache;		
			$this->load->view( $this->folder.'/tab_compartir', $data );
		}else{
			$data['compartir']=$this->img_path.'graciasporparticipar.png?fbrefresh='.$this->cache;			
			$this->load->view( $this->folder.'/tab_gracias', $data );
		}
	}
	
	function compartidos($id,$pageid){
		$totalCompartido=$this->modelo->totalCompartidos($id,$pageid);
		$resultado=5-$totalCompartido->compartido;
		echo $resultado;
	}
	
	
	
	
	
	
}