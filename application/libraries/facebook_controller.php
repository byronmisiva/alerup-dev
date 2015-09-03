<?php
class Facebook_Controller extends CI_Controller{
	
	public $data;
	
	public function __construct(){		
		parent::__construct();
		//$this->load->model('samsung_usuario','usuario');
		$this->load->model('primax_usuarios','usuario');
		$this->load->helper('url');
		$this->load->library('form_validation');
	}	
	//Este es el metodo que se configura en los settings de la Tab (Pestaña de la página) y carga la libreria de Facebook y envia los parametros de configuracion
	public function index(){		
		$this->load->view( $this->data['folder'].'/index', $this->data );		
	}			
	//Metodo que redirecciona despues de pedir permisos a la pagina de la Tab
	function  pageTab(){		
		$data = ( $this->uri->segment(3) != 'undefined') ? $this->uri->segment(3) : '';
		$pageName = ( $this->uri->segment(4) != 'undefined') ? $this->uri->segment(4) : $this->data['facebook_page'];
		$appId = ( $this->uri->segment(5) != '' ) ? $this->uri->segment(5) : $this->data['appId'];
		$namespace = ( $this->uri->segment(6) != '' ) ? $this->uri->segment(6) : $this->data['namespace'];		
		echo "<script>parent.location.href='https://www.facebook.com/".$pageName."?v=app_".$appId."&app_data=".$data."';</script>";
	}
	//Metodo que redirecciona despues de pedir permisos a la pagina de la App
	function  pageApp(){
		$namespace = ( $this->uri->segment(3) != '' ) ? $this->uri->segment(3) : $this->config->item('facebook_namespace');
		echo "<script>parent.location.href='https://apps.facebook.com/".$namespace."';</script>";
	}
	//Estos metodos son los pproporcionados po facebook para parsear el Siged Request
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
}
