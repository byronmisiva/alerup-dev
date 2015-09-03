<?php
class Mdl_bigpromo extends CI_Model{

function __construct(){
		parent::__construct();
		$this->nombre2 = 'bigpromo';
		$this->load->database('appspr');
	}
			
	function verificarUser($id){
		$this->db->close();
		$this->db1=$this->load->database('appspr',true);
		$this->db1->select('*');
		$this->db1->from($this->nombre2);
		$this->db1->where('id_user', $id);
		$consulta = $this->db1->get();
		if ($consulta->num_rows() > 0)
			return TRUE;
		else
			return FALSE;
		$this->db1->close();
		$this->db=$this->load->database('samsung',true);
		
	}
	
	function getRegistradoAmigos($id){
		$this->db->close();
		$this->db1=$this->load->database('appspr',true);
		$this->db1->select('*');
		$this->db1->from("bigpromo_invitados");
		$this->db1->where('id_user', $id);
		$this->db1->where('estado', "1");		
		$consulta = $this->db1->get();
		if ($consulta->num_rows() > 0){
			return $consulta->num_rows();
		}else
			return FALSE;
		$this->db1->close();
		$this->db=$this->load->database('samsung',true);
	}
	
	
	function buscarUser($id){
		$this->db->close();
		$this->db1=$this->load->database('appspr',true);
		$this->db1->select('*');
		$this->db1->from($this->nombre2);
		$this->db1->where('id_user', $id);
		$consulta = $this->db1->get();
		if ($consulta->num_rows() > 0)
			return current($consulta->result());
		else{
			/*$this->db->insert($this->nombre2,array("id_user"=>$id));
			$this->db->select('*');
			$this->db->from($this->nombre2);
			$this->db->where('id_user', $id);
			$consulta = $this->db->get();*/
			return false;
		}
		$this->db1->close();
		$this->db=$this->load->database('samsung',true);
			
	}
	
	
	
	
	function getUser($id){
		$this->db->select('*');
		$this->db->from($this->nombre2);
		$this->db->where('id', $id);
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0)
			return current($consulta->result());		
	}
	
	function getparametrosImage($id){
		$this->db->select('*');
		$this->db->from("lanzamiento_portadas");
		$this->db->where('id', $id);
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0)
			return current($consulta->result());
		
	}
	
	function obtenerRegistro($id){
		$this->db->select('*');
		$this->db->from($this->nombre2);
		$this->db->where('id_user', $id);
		$consulta = $this->db->get();
		return current($consulta->result());
	}
	
	// recupera cuantos ya a compartido el usuario
	function obtenerCampartidos($id)    {
		$this->db->select('compartidos');
		$this->db->from($this->nombre2);
		$this->db->where('id_user', $id);
		$consulta = $this->db->get();
		return current($consulta->result());
	}	
	
	function agradecimiento(){
		echo "Gracias";
	}
	
	function registrarInvitados($id){
		$arreglo = json_decode($_POST['data']);
		$total   = count($arreglo);
		if($total>0){
			$dato    = $this->modelo->obtenerCampartidos($id);
			$data['user']= $this->modelo->obtenerRegistro($id);
	
			$val_nuevo = $total + (int)$dato->compartido;
			$data_update = array(
					"compartidos" => (string)$val_nuevo);
			$this->db->where(array('id_user' => $id));
			$this->db->update($this->nombre2, $data_update);
			if ($val_nuevo%5==0 || $total > 5)
				echo "C-0";
			else
				echo "I-".($val_nuevo%5);
		}
	}
	
	
	function verAmigos($id){
		$this->db->select("fbid");
		$this->db->from("penales_invitados");
		$this->db->where("DATE_FORMAT(creado,'%d')","DATE_FORMAT(NOW(),'%d')",FALSE);
		$this->db->where("id_user",$id);
		$consulta=$this->db->get()->result();
		return $consulta;
	}
}