<?php
class Samsung_chato extends  CI_Model {
	var $nombre2;
	
	function __construct(){
		// Call the Model constructor
		parent::__construct();
			$this->nombre2='chaton';
			$this->load->database('samsung');
	}
	
	function busquedaRegistro($id){
		$this->db->select('*');
		$this->db->from($this->nombre2);
		$this->db->where('id_user',$id);		
		$consulta=$this->db->get();	
		if($consulta->num_rows()==0)
			return FALSE;			
		else
			return current($consulta->result());
	}
	function numero(){
		$query=$this->db->query("SELECT id,numero,ganador
									FROM samsung_numero_chato");
		return current($query->result());
				
	}
	function busquedaTipo($id){
		$this->db->select('*');
		$this->db->from($this->nombre2);
		$this->db->wºhere('id_user',$id);		
		$consulta=$this->db->get();
		if($consulta->num_rows()>0)
			return current($consulta->result());
		else
			return 0;
	}
	
	function bloquearJuego($id){
		$this->db->where("id_user",$id);
		$dato=array("bloqueado"=>"1");
		$this->db->update($this->nombre2,$dato);
	}
		
	function busquedaJugador($id,$pageid){
		$this->db->select("*");
		$this->db->from($this->nombre2);
		$this->db->where("id_user",$id);
		$this->db->where("pageid",$pageid);
		$query=$this->db->get();
		
		if($query->num_rows()>0)			
				return TRUE;
			else			
				RETURN FALSE;	
	}
	
	function getJugadores(){		
		$consulta=$this->db->get('samsung_jugadores');
		$opcion=array(''=>'Escoger Jugador');
		foreach ($consulta->result() as $row)
			$opcion[$row->id]=$row->nombres;
		return $opcion;		
	}

	function obtJugadores(){
		$consulta=$this->db->get('samsung_jugadores');		
		return $consulta->result();
	}
			
	function verificarCompartidos($id,$pageid){
		$this->db->select("*");
		$this->db->from($this->nombre2);
		$this->db->where("id_user",$id);
		$this->db->where("id_page",$pageid);
		$query=$this->db->get();		
		if($query->num_rows()>0){
			$consulta=current($query->result());
				if( ( $consulta->compartidos == "0" ) && ($consulta->activo == "1" )) {
					return TRUE;
				}else if ( ( (int)$consulta->compartidos%10 != 0 ) && ($consulta->activo == "1" ) ) {
						return TRUE;
				}else{ 
						return FALSE;	
				}								
		}		
	}
	
	function totalCompartidos($id){		
		$this->db->select("compartido");
		$this->db->from($this->nombre2);
		$this->db->where("id_user",$id);		
		$query=$this->db->get();		
		if($query->num_rows()>0)
			return 	current($query->result());
	}
		
	function obtenerCampartidos($id){
		$this->db->select('compartido');
		$this->db->from($this->nombre2);
		$this->db->where('id_user',$id);		
		$consulta=$this->db->get();
		return current($consulta->result());		
	}
		
	function verAmigos($id){
		$this->db->select("id_amigo");
		$this->db->from("chaton_compartido");
		$this->db->where("DATE_FORMAT(creado,'%d')","DATE_FORMAT(NOW(),'%d')",FALSE);
		$this->db->where("id_user",$id);		
		$consulta=$this->db->get()->result();
		return $consulta;
	}	
		
}	