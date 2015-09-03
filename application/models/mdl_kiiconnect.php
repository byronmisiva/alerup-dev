<?php
class Mdl_kiiconnect extends CI_Model{
    function __construct(){
        parent::__construct();     
        $this->load->database('samsung');
    }    
    
	function buscarUser($token){	
		$this->db->select('*');
		$this->db->from("kiiconnect_registro");
		$this->db->where('token', $token);
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0)
			return "1";
		else
			return "0";				
	}
	
	function insertaruser($datos){
		var_dump($datos);
		$this->db->insert('kiiconnect_registro', $_POST);
	}
	
	function actualizaruser($datos){
		$this->db->where('token', $datos["token"]);
		$this->db->update('kiiconnect_registro', $_POST);
	}

	function actualizarTag($token,$tags){
		$this->db->where('token', $token);
		$this->db->update('kiiconnect_registro', array("tags" => $tags));		
	}
            
    
    

}	

















