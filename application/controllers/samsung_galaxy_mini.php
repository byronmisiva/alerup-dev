<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Samsung_galaxy_mini extends CI_Controller{
	
	public $data;
	var $folderView;
	var $controlador;
	var $idApp;
	var $secretApp;
	var $condiciones;
		
	public function __construct(){
		parent::__construct();		
		$this->load->model('samsung_usuario','usuario_samsung');
		$this->load->model('mdl_galaxy_mini','modelo');
		$this->load->helper('form');
		$this->folderView="galaxy_mini";
		$this->data['controlador']="samsung_galaxy_mini";
		$this->data['idApp']="1110493112299009";
		$this->data['secretApp']="8ac054ef49ef9ef3af7d94dc0a0b4c3a";		
		$this->data['condiciones'] = "<a href='".base_url()."archivos/galaxy-mini/REGLAMENTO-DE-TERMINOS-Y-CONDICIONES-PARA-EL-CONCURSO-S5-Mini.pdf' target='_blank' >T&eacute;rminos y Condiciones aquí</a>";	
	}

	
	function index(){
		$this->load->library('user_agent');
		$mobiles=array('Apple iPhone','Apple iPod Touch','Android','Apple iPad');
		if ($this->agent->is_mobile())
			$this->movil();
		else
			$this->load->view($this->folderView.'/index', array( 'data' =>$this->data) );
	}
	
	function movil(){
		$this->load->view($this->folderView.'/movil', array( 'data' =>$this->data) );
	}
	
	function verificarParticipante($id){
		$participante=$this->usuario_samsung->getUserFbid($id);		
		if($participante == "0"){
			echo "0";			
		}else{		
			$registro=$this->modelo->buscarUser($participante->id);		
			if ($registro==FALSE)
				echo "0";
			else
				echo "1";
		}
	}	
	
	function register(){
		$resp="0";
		if( isset( $_POST['nombre'] ) ){
			$this->data['user'] = json_decode( $_POST['user'] );		
			
				$updateUser = array(
						'nombre' => $_POST['nombre'],
						'apellido' => $_POST['apellido'],						
						'mail' => $_POST['mail'],
						'ciudad' => $_POST['ciudad'],
						'cedula' => $_POST['cedula'],
						'telefono' => $_POST['telefono'],
						'fbid' => $_POST['fbid'],
						'local' => $_POST['local'],
						'factura' => $_POST['factura']
				);				
				$this->db->insert( 'registro_galaxy_mini', $updateUser);								
				$resp="1";		
			echo $resp;
		}	
	}
	/**************************************************************/
	

	


}




























