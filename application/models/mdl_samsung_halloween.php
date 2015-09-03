<?php
class Mdl_samsung_halloween extends CI_Model{    
    var $nombre2;
    
    function __construct(){
        parent::__construct();    
        $this->nombre2 = "samsung_halloween";
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
	
	function getRegistradoAmigos($id){		
		$this->db->select('*');
		$this->db->from("halloween_invitados");
		$this->db->where('id_user', $id);
		$this->db->where('estado', "1");		
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0){
			return $consulta->num_rows();
		}else
			return FALSE;		
	}
	
	function getRanking(){
		$this->db->select('samsung_halloween.id_user,samsung_usuarios.completo,samsung_halloween.puntos,');
		$this->db->where("samsung_halloween.puntos <","300");	
		$this->db->from($this->nombre2);
		$this->db->join('samsung_usuarios', 'samsung_usuarios.id = samsung_halloween.id_user');
		$this->db->order_by("samsung_halloween.puntos", "desc");
		$this->db->limit(5);
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
		else{
			/*$this->db->insert($this->nombre2,array("id_user"=>$id));
			$this->db->select('*');
			$this->db->from($this->nombre2);
			$this->db->where('id_user', $id);
			$consulta = $this->db->get();*/
			return false;
		}			
	}
	
	
	function getUser($id){
		$this->db->select('*');
		$this->db->from($this->nombre2);
		$this->db->where('id', $id);
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0)
			return current($consulta->result());		
	}
	
	function actualizarPuntaje($idUser,$totalPuntaje){
		$this->db->select("puntos,juegos");
		$this->db->from($this->nombre2);
		$this->db->where('id_user', $idUser);
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0){
			$consulta=current($consulta->result());
			if((int)$consulta->puntos<(int)$totalPuntaje){
				$juegos=(int)$consulta->juegos;
				$juegos=$juegos+1;					
				$this->db->where('id_user', $idUser);
				$this->db->update($this->nombre2,array("puntos"=>$totalPuntaje,"juegos"=>$juegos));
			}
			$juegos=(int)$consulta->juegos;
			$juegos=$juegos+1;
			$this->db->where('id_user', $idUser);
			$this->db->update($this->nombre2,array("juegos"=>$juegos));			
		}
			
		
			
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
		$this->db->select('compartido, puntos');
		$this->db->from($this->nombre2);
		$this->db->where('id_user', $id);
		$consulta = $this->db->get();
		return current($consulta->result());
	}	
	   
    
}	






