<?php
class Samsung_usuarios_galeria extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->database('samsung');
	}

	function newRegister($data){
		$user=$this->getUserFbid($data['fbid']);
		if($user == false){
			$usuario['nombre']=$data['nombre'];
			$usuario['apellido']=$data['apellido'];
			$usuario['completo']=$data['completo'];
			$usuario['mail']=$data['mail'];
			$usuario['fbid']=$data['fbid'];
			$usuario['genero']=$data['genero'];
			$usuario['ultima_app']=$data['ultima_app'];
			return $this->db->insert('usuarios_galeria',$usuario);
		}
		else{
			$this->db->set('ultima_app',$data['ultima_app']);
			$this->db->set('ultima','NOW()',FALSE);
			$this->db->where('id',$user->id);
			return $this->db->update('usuarios_galeria');
		}
	}

	function getUserFbid($fbid){
		$this->db->where('fbid',$fbid);
		$user=$this->db->get('usuarios_galeria');
		if ($user->num_rows()>0)
			return current($user->result());
		else
			return "0";
	}

	function alreadyRegistrer( $table, $where ){
		$this->db->where( $where );
		$result = $this->db->get( $table )->result();

		if( count($result) > 0)
			return "y";
		else
			return "n";
		/*;die;
		 return  ( count( $result ) > 0  ) ? TRUE : FALSE;*/
	}

	function alreadyRegistrer2( $table, $where ){
		$this->db->where( $where );
		$result = $this->db->get( $table )->result();

		if( count($result) > 0)
			return "y";
		else
			return "n";
		/*;die;
			return  ( count( $result ) > 0  ) ? TRUE : FALSE;*/
	}

	function getDatos($id){
		$this->db->where('id',$id);
		$user=$this->db->get('usuarios_galeria')->result();
		return current($user);
	}

}