<?php
class Mdl_galaxy_mini extends CI_Model{
    var $registro;
    var $galeria;
    var $baul;
    var $usuarios;

    function __construct(){
        parent::__construct();
        $this->registro = 'registro_galaxy_mini';        
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
   
    function buscarUser($id){
    	$this->db->select('*');
    	$this->db->from($this->registro);
    	$this->db->where('id_user', $id);
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
    	$this->db->select('compartido, puntos');
    	$this->db->from($this->registro);
    	$this->db->where('id_user', $id);
    	$consulta = $this->db->get();
    	return current($consulta->result());
    }    

}	

















