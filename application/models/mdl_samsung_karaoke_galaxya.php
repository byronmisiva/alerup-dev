<?php
class Mdl_samsung_karaoke_galaxya extends CI_Model{
    var $registro;
    var $galeria;
    var $baul;
    var $usuarios;

    function __construct(){
        parent::__construct();
        $this->registro = 'galeria_galaxy';
        $this->galeria = 'galeria_galaxy';
        $this->baul = 'galeria_galaxy';
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

  function listarusuariosregistrados(){
    	$query = $this->db->query("SELECT reg.id_user,usu.completo, usu.cedula, usu.ciudad, usu.telefono, usu.mail, reg.fnacimiento, reg.compartidos, (select count(*) 
					   			   from samsung_galeria_galaxy gal  
                                   where reg.id_user = gal.id_user)total 
                                   FROM samsung_registro_galaxy reg, samsung_usuarios usu 
                                   where reg.id_user = usu.id
order by total desc" );
    		if ($query->num_rows() > 0)
    			return $query->result();
    }
    
    function listargaleriaregistrados($id){
    	$this->db->select('*');
    	$this->db->from("galeria_galaxy");
    	$this->db->where('id_user', $id);
    	$consulta = $this->db->get();
    	if ($consulta->num_rows() > 0)
    		return $consulta->result();
    }

}