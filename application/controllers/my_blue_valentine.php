<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
    class My_blue_valentine extends CI_Controller{
	
	public $data;
	var $folderView;
	var $controlador;
	var $idApp;
	var $secretApp;
	var $condiciones;
		
	public function __construct(){
		parent::__construct();		
		$this->load->model('samsung_usuario','usuario_samsung');
		$this->load->model('mdl_my_blue_valentine','modelo');
		$this->load->helper('form');
		$this->folderView="my_blue_valentine";
		$this->data['controlador']="my_blue_valentine";
		$this->data['idApp']="676146535844553";
		$this->data['secretApp']="8b9b2f32dc06e319b4c0d9d107249324";
		$this->data['condiciones'] = "<a href='".base_url()."archivos/REGLAMENTO-DE-TERMINOS-Y-CONDICIONES-PARA-EL-CONCURSO-PRIMAX.pdf' target='_blank' >T&eacute;rminos y Condiciones:</a>";	
	}

	
	function index(){
		$this->load->view($this->folderView.'/index', array( 'data' =>$this->data) );
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
			if( $this->usuario_samsung->alreadyRegistrer( 'usuarios', array( 'fbid' => $_POST['fbid'] ) ) ){
				$updateUser = array(
						'nombre' => $_POST['nombre'],'apellido' => $_POST['apellido'],
						'completo' => $_POST['nombre']." ".$_POST['apellido'],
						'mail' => $_POST['mail'],'ultima_app' => "my_blue_valentine",
                        'genero' => $_POST['genero'],
						'ciudad' => $_POST['ciudad'],'cedula' => $_POST['cedula'],
						'telefono' => $_POST['telefono']
				);				
				$this->db->update( 'usuarios', $updateUser, array( 'fbid' => $_POST['fbid'] ));
				$participante=$this->usuario_samsung->getUserFbid($_POST['fbid']);
				$id = $participante->id;
				$this->db->insert("my_blue_valentine",array(
															"id_user"=>$id, 
															"fb_id"=>$_POST['fbid'],
															"nombre"=>$_POST['nombre']." ".$_POST['apellido'],
															"ciudad"=>$_POST['ciudad'],
															"factura"=>$_POST['factura'],
															"local"=>$_POST['local']));

				$resp="1";
			}
			else{
				$insertUser = array(
						'nombre' => $_POST['nombre'],
                        'apellido' => $_POST['apellido'],
						'completo' => $_POST['nombre']." ".$_POST['apellido'],
						'mail' => $_POST['mail'],'ultima_app' => "my_blue_valentine",
						'ciudad' => $_POST['ciudad'],'cedula' => $_POST['cedula'],
						'genero' => $_POST['genero'],
						'telefono' => $_POST['telefono'],'fbid' => $_POST['fbid']
				);				
				$this->db->insert( 'samsung_usuarios', $insertUser );
				$id = $this->db->insert_id();
                $this->db->insert("my_blue_valentine",array(
                    "id_user"=>$id,
                    "fb_id"=>$_POST['fbid'],
                    "nombre"=>$_POST['nombre']." ".$_POST['apellido'],
                    "ciudad"=>$_POST['ciudad'],
                    "factura"=>$_POST['factura'],
                    "local"=>$_POST['local']));
				$resp="1";
			}
			echo $resp;
		}	
	}
	/**************************************************************/
	
	function ingresoActividad($sw="0"){

		$this->data["participanteRegistrado"]=$sw;
		$this->load->view( $this->folderView.'/actividad', array( 'data' => $this->data ) );
	}
			
	function compartir($id){
		$reg    = $this->modelo->obtenerCampartidos($id);
		$dato['compartido']=$reg->compartido;
		$dato['puntos']=$reg->puntos;
		
		$dato["id_user"]=$id;
		$this->load->view($this->$folderView.'/compartir',$dato);
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
	
	function agradecimiento(){
		$this->load->view($this->$folderView.'/agradecimiento');
	}

}




























