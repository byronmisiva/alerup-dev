<?php
class Mdl_samsung_baterias6 extends CI_Model{
    var $registro;
    var $galeria;
    var $baul;
    var $usuarios;

    function __construct(){
        parent::__construct();
        $this->registro = 'bateria_registro';
        $this->usuarios = 'usuarios';
        $this->load->database('samsung');
    }
     
    
    function verificarUser($id){
    	$this->db->select('*');
    	$this->db->from($this->registro);
    	$this->db->where('id_user', $id);
    	$consulta = $this->db->get();
    	if ($consulta->num_rows() > 0)
    		return TRUE;
    	else
    		return FALSE;
    }
            
    
    function buscarUser($id){
    	$this->db->select('*');
    	$this->db->from($this->registro);
    	$this->db->where('fbid', $id);
    	$consulta = $this->db->get();
    	if ($consulta->num_rows() > 0)
    		return current($consulta->result());
    	else    		
    		return FALSE;
    	}
    	
    function buscarUserFbid($id){
    		$this->db->select('*');
    		$this->db->from($this->registro);
    		$this->db->where('fbid', $id);
    		$consulta = $this->db->get();
    		if ($consulta->num_rows() > 0)
    			return current($consulta->result());
    		else
    			return false;
    	}	
        
    function getUser($id){
    	$this->db->select('*');
    	$this->db->from($this->registro);
    	$this->db->where('id', $id);
    	$consulta = $this->db->get();
    	if ($consulta->num_rows() > 0)
    		return current($consulta->result());
    }   
    
    function obtenerRegistro($id){
    	$this->db->select('*');
    	$this->db->from($this->registro);
    	$this->db->where('id_user', $id);
    	$consulta = $this->db->get();
    	return current($consulta->result());
    }
    
    // recupera cuantos ya a compartido el usuario
    function obtenerCampartidos($id)    {
    	$this->db->select('compartidos');
    	$this->db->from($this->registro);
    	$this->db->where('id_user', $id);
    	$consulta = $this->db->get();
    	return current($consulta->result());
    }    
    
    function getRanking(){
    	$this->db->select('samsung_bateria_registro.id_user,samsung_bateria_registro.tiempo as tiempo,samsung_usuarios.completo,samsung_bateria_registro.puntos as puntos,');
    	$this->db->distinct("samsung_bateria_registro.id_user");
    	$this->db->where("samsung_bateria_registro.puntos <=","2240");
    	$this->db->from($this->registro);
    	$this->db->join('samsung_usuarios', 'samsung_usuarios.id = samsung_bateria_registro.id_user');
    	$this->db->order_by("puntos", "desc");
    	$this->db->limit(5);
    	$query = $this->db->get();
    	return $query->result();
    }

}	

















