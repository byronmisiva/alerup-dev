<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Samsung_entradas extends CI_Controller{
	
	public $data;
	var $folderView;
	var $controlador;
	var $idApp;
	var $secretApp;
	var $condiciones;
	var $dispositivo;
		
	public function __construct(){
		parent::__construct();		
		$this->load->model('samsung_usuario','usuario_samsung');
		$this->load->model('mdl_entradas_trivia','modelo');
		$this->load->helper('form');
		$this->folderView="entradas_trivia";				
		$this->data['controlador']="Samsung_entradas";
		$this->data['idApp']="901115983301244";
		$this->data['secretApp']="356b3c087ca5332c9054277f7331b731";		
		$this->data['condiciones'] = "<a href='".base_url()."archivos/entradas/REGLAMENTO-DE-TERMINOS-Y-CONDICIONES-PARA-EL-CONCURSO-Escuela-Samsung.pdf' target='_blank' >T&eacute;rminos y Condiciones</a>";	
	}
	
	function index(){
			$this->data['dispositivo']="normal";
			$this->load->view($this->folderView.'/index', array( 'data' =>$this->data) );
	}
		
	function movil(){
		$this->data['dispositivo']="movil";
		$this->load->view($this->folderView.'/movil', array( 'data' =>$this->data) );
	}
	
	function register(){
		$resp="0";
		if( isset( $_POST['nombre'] ) ){						
			if( $this->usuario_samsung->alreadyRegistrer( 'usuarios', array( 'fbid' => $_POST['fbid'] ) )=="y" ){
				$updateUser = array(
						'nombre' => $_POST['nombre'],'apellido' => $_POST['apellido'],
						'completo' => $_POST['nombre']." ".$_POST['apellido'],
						'mail' => $_POST['mail'],'ultima_app' => "galaxy-a",
						'ciudad' => $_POST['ciudad'],'cedula' => $_POST['cedula'],
						'telefono' => $_POST['telefono']
				);				
				$this->db->update( 'usuarios', $updateUser, array( 'fbid' => $_POST['fbid'] ));
				$participante=$this->usuario_samsung->getUserFbid($_POST['fbid']);
				$id = $participante->id;
				$this->db->insert("entradas_juanes",array(
															"id_user"=>$id,
															"fbid"=>$_POST['fbid']));				
				$resp="1";
			}
			else{
				$insertUser = array(
						'nombre' => $_POST['nombre'],'apellido' => $_POST['apellido'],
						'completo' => $_POST['nombre']." ".$_POST['apellido'],
						'mail' => $_POST['mail'],'ultima_app' => "galaxy-a",
						'ciudad' => $_POST['ciudad'],'cedula' => $_POST['cedula'],
						'telefono' => $_POST['telefono'],'fbid' => $_POST['fbid']
				);				
				$this->db->insert( 'usuarios', $insertUser );
				$id = $this->db->insert_id();
				$this->db->insert("entradas_juanes",array(
															"id_user"=>$id,															
															"fbid"=>$_POST['fbid']));				
				$resp="1";
			}
			echo $resp;
		}	
	}
	/**************************************************************/
	
	function verificarParticipante($id){
		$participante=$this->usuario_samsung->getUserFbid($id);		
		
		if($participante == "0"){
			echo "F";
		}else{
			$registro=$this->modelo->buscarUser($participante->id);
			if ( $registro == FALSE )
				echo "F";
			else{
				$participante=$this->modelo->buscarUserFbid($id);
				$actividades=(int)$participante->actividades;
				$actividades=$actividades+1;
				$this->db->where("fbid",$participante->id);
				$this->db->update("entradas_juanes",array("actividades"=>$actividades));
				echo "A";
			}
		}
	}
	
	function ingresoActividad($sw="0"){
		$this->data["dispositivo"]=$sw;
		$data["preguntas"]=$this->modelo->getPreguntas();
		$data["respuestas"]=[];
		foreach ($data["preguntas"] as $row){
			$auxiliar=$this->modelo->getRespuesta($row->id);
			foreach ($auxiliar as $row2){
				array_push($data["respuestas"], $row2);	
			}
		}
		$this->load->view( $this->folderView.'/actividad', $data);
	}
			
	function sumarCompartida($id){
		$participante=$this->modelo->buscarUserFbid($id);
		$compartidos=(int)$participante->compartidos;		
		$compartidos=$compartidos+1;		
		$this->db->where("id",$participante->id);
		$this->db->update("escuela_registro",array("compartidos"=>$compartidos));
	}
	
	function sumarCompartidaPosteo($id){
		$participante=$this->modelo->buscarUserFbid($id);
		$compartidos=(int)$participante->posteos;
		$compartidos=$compartidos+1;
		$this->db->where("fbid",$id);
		$this->db->update("escuela_registro",array("posteos"=>$compartidos));
	}
	
	function registrarInvitados($id){
		$arreglo = json_decode($_POST['data']);
		$total   = count($arreglo);
		$dato    = $this->modelo->obtenerCampartidos($id);
		$data['user']= $this->modelo->obtenerRegistro($id);
	
		$val_nuevo = $total + (int)$dato->compartido;
		$data_update = array(
				"compartido" => (string)$val_nuevo);
		$this->db->where(array('id_user' => $id));
		$this->db->update('halloween', $data_update);
		if ($val_nuevo%5==0 || $total > 5)
			echo "C-0";
		else
			echo "I-".($val_nuevo%5);
	}

}


