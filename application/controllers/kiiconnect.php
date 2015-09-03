<?php 
if (isset($_SERVER['HTTP_ORIGIN'])) {  
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");  
    header('Access-Control-Allow-Credentials: true');  
    header('Access-Control-Max-Age: 86400');   
}  
  
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {  
  
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))  
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");  
  
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))  
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");  
}  

class Kiiconnect extends CI_Controller {	
		
	public function __construct(){
		parent::__construct();
		$this->folder = 'kiiconnect';	
		$this->load->model('mdl_kiiconnect','modelo');
	}

	public function index(){		
		echo "Hola KiiConnect";
	}
	
	function registroApp(){
		if($this->modelo->buscarUser($_POST["token"])=="1"){			
			$this->modelo->actualizaruser($_POST);
		}else
			$this->modelo->insertaruser($_POST);
	}

	function registrotags(){
		$this->modelo->actualizarTag($_POST["tokeUser"],$_POST["tags"]);
         /*echo "Hola";
	 $this->db->where('token', $_POST["tokeUser"]);
	 $this->db->update('kiiconnect_registro', array("tags"=> $_POST["tags"]) );*/
	}
}




















