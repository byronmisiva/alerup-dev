<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Compartir extends CI_Controller{
	
	public $data;
	var $folderView;
	var $controlador;
	var $idApp;
	var $secretApp;
	var $condiciones;
	var $dispositivo;
		
	public function __construct(){
		parent::__construct();		
		$this->folderView="app_compartir";				
		$this->data['controlador']="compartir";
		$this->data['idApp']="1516374015275895";
		$this->data['secretApp']="4c374f5dc04e4b142bcd50e2ca927490";	
	}
	
	function index(){
		$this->data['enlace'] = $_GET["link"];
		$this->load->view($this->folderView.'/index', array( 'data' =>$this->data) );
	}
	
	function carga(){
		$this->load->view($this->folderView.'/compartir', array( 'data' =>$this->data) );
	}
	
}