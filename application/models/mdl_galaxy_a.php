<?php
class Mdl_galaxy_a extends CI_Model{
    var $registro;
    var $galeria;
    var $baul;
    var $usuarios;

    function __construct(){
        parent::__construct();
        $this->registro = 'registro_galaxy';
        $this->galeria = 'galeria_galaxy';
        $this->baul = 'baul_galaxy';
        $this->usuarios = 'usuarios';
        $this->load->database('samsung');
    }
    
    function guardarGaleria($id,$imagen){
    	$registro=array("id_user"=>$id,"imagen"=>$imagen);
    	$this->db->insert($this->galeria,$registro);
    }

    function listarusuariosregistrados(){
    	$query = $this->db->query("SELECT distinct(usu.fbid),reg.id_user, usu.completo, usu.cedula, usu.ciudad, usu.telefono, usu.mail, reg.fnacimiento, reg.compartidos, (select count(*) 
					   			   from samsung_galeria_galaxy gal  
                                   where reg.id_user = gal.id_user)total 
                                   FROM samsung_registro_galaxy reg, samsung_usuarios usu 
                                   where reg.id_user = usu.id
group by reg.id_user
order by total desc" );
    		if ($query->num_rows() > 0)
    			return $query->result();
    }
    
     function listargaleriaregistrados($id){
    	$this->db->select('*');
    	$this->db->from("registro_galaxy");
    	$this->db->where('id_user', $id);
    	$consulta = $this->db->get();
    	if ($consulta->num_rows() > 0)
    		return $consulta->result();
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
    
    function getGaleria($id){
    	$this->db->select('*');
    	$this->db->from("galeria_galaxy");
    	$this->db->where('id_user', $id);
    	$this->db->order_by("creado", "desc"); 
    	$consulta = $this->db->get();
    	if ($consulta->num_rows() > 0)
    		return $consulta->result();
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
    function getManifiesto(){
    		$query = $this->db->query("select id_user,completo, mensaje from samsung_manifiesto_galaxy as reg, samsung_usuarios as usu
		 								where reg.fbid = usu.fbid group by reg.creado asc" );
    		if ($query->num_rows() > 0)
    			return $query->result();
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

















