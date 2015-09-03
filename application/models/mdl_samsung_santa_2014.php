<?php
class Mdl_samsung_santa_2014 extends CI_Model{    
    var $nombre2;
    function __construct(){
        parent::__construct();    
        $this->nombre2 = "flappy_santa";
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
		$this->db->from("flappy_santa_invitados");
		$this->db->where('id_user', $id);
		$this->db->where('estado', "1");		
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0){
			return $consulta->num_rows();
		}else
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
			return false;					
	}
	
	
	function getUser($id){
		$this->db->select('*');
		$this->db->from($this->nombre2);
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
	
	function verAmigos($id){
		$this->db->select("fbid_amigo");
		$this->db->from("flappy_santa_invitado");
		$this->db->where("DATE_FORMAT(creado,'%d')","DATE_FORMAT(NOW(),'%d')",FALSE);
		$this->db->where("fbid_user",$id);
		$consulta=$this->db->get()->result();
		return $consulta;
	}
	
	function getRanking(){		
		$this->db->select('samsung_flappy_santa.id_user,samsung_usuarios.completo,( samsung_flappy_santa.compartidos+ samsung_flappy_santa.invitar+ samsung_flappy_santa.puntos) as total,');		
		$this->db->from($this->nombre2);
		$this->db->join('samsung_usuarios', 'samsung_usuarios.id = samsung_flappy_santa.id_user');
		$this->db->order_by("total", "desc");
		$this->db->limit(10);
		$query = $this->db->get();
		return $query->result();
	}
	
	function getPosicion($id){
		$query = $this->db->query("select count(*)posicion
								   From (SELECT id, (compartidos + invitar + puntos) as total
  	                                     FROM samsung_flappy_santa
	                                     Order by total desc ) as ranking ,
	                                     (SELECT id, (compartidos + invitar + puntos) as total2
  	                                     FROM samsung_flappy_santa
	                                     where id_user = ".$id.")	as participante
                                         where participante.total2 <= ranking.total" );
		return current($query->result());
	}
	
	function getRegistro($id){
		$this->db->where("fbid",$id);
		$this->db->from("usuarios");
		return current($this->db->get()->result());
	}
	   
    
}	














