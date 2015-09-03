<?php
class Samsung_mama extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->nombre2 = 'mama_doble';
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
	
	function reporteApp(){		
		$consulta = $this->db->query("
		select *, (select count(*)
				from samsung_lanzamiento_portadas as b
				where a.id_user=b.id_user)generado
				from samsung_s5 as a , samsung_usuarios as u
				where a.id_user = u.id");
		return $consulta->result();
	} 
	
	function getReporte(){
		$consulta = $this->db->query("
				select *, (select count(*)
				from samsung_lanzamiento_portadas as b
				where a.id_user=b.id_user)generado
				from samsung_s5 as a , samsung_usuarios as u
				where a.id_user = u.id");
		return $consulta->result();		
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
		$this->db->select('compartido');
		$this->db->from($this->nombre2);
		$this->db->where('id_user', $id);
		$consulta = $this->db->get();
		return current($consulta->result());
	}	
	
	
}






