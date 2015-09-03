<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Contacto_rapido extends CI_Controller{
	
	public $data;
	var $folderView;
	var $controlador;
	var $idApp;
	var $secretApp;
	var $condiciones;
	var $dispositivo;
		
	public function __construct(){
		parent::__construct();		
		$this->folderView="contacto_rapido";				
		$this->data['controlador']="Contacto_rapido";
		$this->data['idApp']="1586877921587387";
	}
	
	function index(){
		$this->load->library('user_agent');
		$mobiles=array('Apple iPhone','Apple iPod Touch','Android','Apple iPad');
		if ( $this->agent->is_mobile() )
			$this->movil();
		else{
			$this->data['dispositivo']="normal";
			$this->load->view($this->folderView.'/index', array( 'data' =>$this->data) );
		}
	}
	
	function movil(){
		$this->data['dispositivo']="movil";
		$this->load->view($this->folderView.'/movil', array( 'data' =>$this->data) );
	}
	
}




























