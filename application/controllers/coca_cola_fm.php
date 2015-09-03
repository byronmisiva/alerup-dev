<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Coca_cola_fm extends CI_Controller{
	
	public $data;
	var $folderView;
	var $controlador;
	var $idApp;
	var $secretApp;
	var $condiciones;
	var $dispositivo;
		
	public function __construct(){
		parent::__construct();		
		$this->folderView="coca-cola-radio";				
		$this->data['controlador']="coca_cola_fm";
		$this->data['idApp']="379919285525134";
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




























