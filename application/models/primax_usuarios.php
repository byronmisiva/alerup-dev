<?php
class Primax_Usuarios extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->database('appspr');
	}
	
	function newRegister($data){
		$this->db->close();
		$this->db1=$this->load->database('appspr',true);
		$user=$this->getUserFbid($data['fbid']);
		if($user == false){
			$usuario['nombre']=$data['nombre'];
			$usuario['apellido']=$data['apellido'];
			$usuario['completo']=$data['completo'];
			$usuario['mail']=$data['mail'];
			$usuario['fbid']=$data['fbid'];
			$usuario['genero']=$data['genero'];
			$usuario['ultima_app']=$data['ultima_app'];			
			return $this->db->insert('usuarios',$usuario);
		}
		else{			
			$this->db1->set('ultima_app',$data['ultima_app']);
			$this->db1->set('ultima','NOW()',FALSE);
			$this->db1->where('id',$user->id);
			return $this->db1->update('usuarios');
		}	
		
		$this->db1->close();
		$this->db=$this->load->database('samsung',true);
	}
	
	function getUserFbid($fbid){		
		$this->db->close();
		$this->db1=$this->load->database('appspr',true);
		$this->db1->where('fbid',$fbid);
		$user=$this->db1->get('usuarios');
		if ($user->num_rows()>0)		
			return current($user->result());
		else 
			return "0";
		$this->db1->close();
		$this->db=$this->load->database('samsung',true);
	}

	function alreadyRegistrer( $table, $where ){
		$this->db->close();
		$this->db1=$this->load->database('appspr',true);
		$this->db1->where( $where );
		$result = $this->db1->get( $table )->result();		
		return  ( count( $result ) > 0  ) ? TRUE : FALSE;
		$this->db1->close();
		$this->db=$this->load->database('samsung',true);
	}
	
	function getDatos($id){
		$this->db->close();
		$this->db1=$this->load->database('appspr',true);
			$this->db1->where('id',$id);
			$user=$this->db1->get('usuarios')->result();
			return current($user);
		$this->db1->close();
		$this->db=$this->load->database('samsung',true);
	}
	
}
























