<?php
class Mdl_usuario_corazon extends CI_Model{    
    var $nombre1;
    function __construct(){
        // Call the Model constructor
        parent::__construct();    
        $this->nombre1 = "registros_corazon";
        $this->load->database('samsung');
    }
   function insertarParticipante($datos){ 
    	$this->db->close();
    	$this->db1=$this->load->database('samsung',true);
    	$this->db1->insert($this->nombre1,$datos);
    	return $this->db1->insert_id();    		
    	$this->db1->close();
    	$this->db=$this->load->database('default',true);
   }
   
   function getParticipante($id){
	   	$this->db->close();
	   	$this->db1=$this->load->database('samsung',true);
	   	$this->db1->where("id",$id);
	   	$this->db1->from($this->nombre1);
	   	$consulta=$this->db1->get();
	   	return current($consulta->result());
	   	$this->db1->close();
	   	$this->db=$this->load->database('default',true);
   }
   
   function actualizarParticipante($datos){
   	$this->db->close();
   	$this->db1=$this->load->database('samsung',true);   	
   	$this->db1->where("id",$datos["id"]);
   	$this->db1->update($this->nombre1 , array("tipo"=>$datos["tipo"],"pulsos"=>$datos["pulsos"]));
   	return TRUE;
   	$this->db1->close();
   	$this->db=$this->load->database('default',true);
   }
   
   function reporteApp(){   	
   	$this->db->close();
   		$this->db1=$this->load->database('samsung',true);
   		$this->db1->select("*");
   		$this->db1->from($this->nombre1);
   		$consulta=$this->db1->get();
   		return $consulta->result();   	
   	$this->db1->close();
   	$this->db=$this->load->database('default',true);
   }
   
   
   function getReporteFiltrado(){
	   	$this->db->close();
	   	$this->db1=$this->load->database('samsung',true);
	   	$this->db1->select("*");
	   	$this->db1->where("tipo","4");
	   	$this->db1->from($this->nombre1);	   	
	   	$consulta=$this->db1->get();
	   	return $consulta->result();
	   	$this->db1->close();
	   	$this->db=$this->load->database('default',true);   	
   }   
   
    
}	














