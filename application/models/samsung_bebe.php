<?php
class Samsung_bebe extends  CI_Model {
	var $tabla2;
	
	function __construct(){
		// Call the Model constructor
		parent::__construct();
			$this->tabla2='bebe_hincha';
			$this->load->database('samsung');
	}
		
	function insertBebe($datos){				
		$insertar=array(
				"id_user"=>$datos['id'],
				"id_page"=>$datos['id_page'],
				"imagen"=>$datos['imagen']
				);		
		$this->db->insert($this->tabla2,$insertar);	
		return $this->db->insert_id();	
	}
	
	function galeriaBebe($grupo){
		$this->db->select('*');
		$this->db->from($this->tabla2);
		$this->db->limit(4,$grupo);
		$consulta=$this->db->get();		
		return $consulta->result();
	}
	
	function votosTotales($id){
		$this->db->select('puntos');
		$this->db->from($this->tabla2);
		$this->db->where("id",$id);
		$consulta=$this->db->get();
		$arreglo=current($consulta->result());
		$votos=$arreglo->puntos;
		return $votos;
	}
	
	function verificarBebe($id){
		$this->db->select('*');
		$this->db->from($this->tabla2);
		$this->db->where("id_user",$id);
		$consulta=$this->db->get();
		if ($consulta->num_rows()>0)
			return true;
		else
			return false;
	}
	
	function obtenerVoto($id_user,$id){
		if ($this->verificarVotoUsuario($id_user,$id)==false){
			$this->db->select('count(*)votos');
			$this->db->from("bebe_votos");
			$this->db->where("id_voto",$id_user);
			$this->db->where("id_bebe",$id);
			$consulta=$this->db->get();
			$arreglo=current($consulta->result());
			$votos=$arreglo->votos;
			if ($consulta->num_rows()>0)		 
				return $votos;
			else
				return false;
		}else{
			return true;
		} 		
	}
		
	function verificarVotoUsuario($user,$bebe){
		$this->db->select('count(*)votos');
		$this->db->from("bebe_votos");
		$this->db->where("id_voto",$user);
		$this->db->where("id_bebe",$bebe);
		$consulta=$this->db->get();		
		$arreglo=current($consulta->result());
		$votos=$arreglo->votos;		 				
		if ($votos!=0)
			return true;	
		else
			return false;	
	}
	
	function obtenerBebe($id){
		$this->db->select('*');
		$this->db->from($this->tabla2);
		$this->db->where("id",$id);
		$consulta=$this->db->get();
		return current($consulta->result());		
	}
	
}

