<?php
class Samsung_kpop_modelo extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->nombre2 = 'kpop';
		$this->load->database('samsung');
	}
	
	function getAll(){
		
		
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
	
}