<?php
class Samsung_penal extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->nombre2 = 'penal';
		$this->load->database('samsung');
	}
			
	function verificarUser($id){
		$this->db->select('*');
		$this->db->from($this->nombre2);
		$this->db->where('id_user', $id);
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0)
			return TRUE;
		else
			return FALSE;	
	}
	
	function actualizarDatos($puntos,$user){
		$this->db->select("*");
		$this->db->where("id_user",$user);
		$consulta=$this->db->get($this->nombre2);
		$consulta=current($consulta->result());
		$valor=(int)$consulta->ingresos;
		$golesnew=(int)$consulta->goles;
		$golesold=(int)$puntos;
		if ($golesnew>$golesold)
			$goles=$golesnew;
		else 
			$goles=$golesold;
		$valor=$valor+1;
		$this->db->where("id_user",$user);
		$this->db->update($this->nombre2,array("ingresos"=>$valor,"goles"=>$goles));				
	}
	
	function getRanking(){
		$this->db->select('penal.id_user,usuarios.completo');
		$this->db->from($this->nombre2);
		$this->db->join('usuarios', 'usuarios.id = penal.id_user');
		$this->db->order_by("penal.goles", "desc");
		$this->db->limit(10);
		$query = $this->db->get();
				
		if($query->num_rows()>0)
			return $query->result();
		else
			return FALSE;
	}
	
	
	
	function buscarUser($id){
		$this->db->select('*');
		$this->db->from($this->nombre2);
		$this->db->where('id_user', $id);
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0)
			return current($consulta->result());
		else
			return FALSE;
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








































