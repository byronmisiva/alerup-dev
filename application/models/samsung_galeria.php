<?php
class Samsung_galeria extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->nombre2 = 'unete_galeria';
		$this->load->database('samsung');
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
	
	function getAll(){
		$this->db->select('*');
		$this->db->from("galeria");
		$this->db->where("activo ","1");
		$this->db->order_by("creado asc");
		$this->db->limit(12);
		$consulta=$this->db->get();
		if ($consulta->num_rows() > 0)
			return $consulta->result();
		else
			return FALSE;
		
	}
	
	function galeriaPaginacion($inicio,$fin){
		$this->db->select('*');
		$this->db->from("galeria");
		$this->db->where("activo ","1");
		$this->db->order_by("creado asc");
		$this->db->limit((int)$fin,(int)$inicio);
		$consulta=$this->db->get();
		
		if ($consulta->num_rows() > 0)
			return $consulta->result();
		else
			return FALSE;
	}

	function getAllaprodos(){
		$query=$this->db->query("SELECT distinct(su.completo),sp.id_user,su.ciudad,su.cedula,su.telefono,su.mail,sp.compartidos, sg.activo,sg.imagen   
				FROM samsung_unete_galeria sp,samsung_galeria sg , samsung_usuarios_galeria su
				where sp.id_user=su.id and sp.id_user=sg.id_user and sg.activo ='1' limit 1000;");
		$consulta=$query->result();
		
		return $consulta;		
	}
	
	function getImagenAprobar(){
		$this->db->select('*');
		$this->db->from("galeria");		
		$this->db->where("activo <=",'1');
		$this->db->order_by("creado asc");
		$consulta=$this->db->get();
		if ($consulta->num_rows() > 0)
			return $consulta->result();
		else
			return FALSE;
	}
	
	function actualizaRegistro($datos){
		$datosInsertar=array(
			"id_user"=>$datos['id_user'],
			"imagen"=>$datos['archivo'],
			"thumb"=>$datos['thumb']
		);		
		$this->db->insert("galeria",$datosInsertar);	
	}
	
	function actualizarDatos($puntos,$user){
		$this->db->select("*");
		$this->db->where("id_user",$user);
		$consulta=$this->db->get($this->nombre2);
		$consulta=current($consulta->result());
		$valor=(int)$consulta->ingresos;
		$golesnew=(int)$consulta->goles;
		$golesold=(int)$puntos;
		if ($golesnew>$golesold)
			$goles=$golesnew;
		else 
			$goles=$golesold;
		$valor=$valor+1;
		$this->db->where("id_user",$user);
		$this->db->update($this->nombre2,array("ingresos"=>$valor,"goles"=>$goles));				
	}
	
	
	function buscarUser($id){
		$this->db->select('*');
		$this->db->from($this->nombre2);
		$this->db->where('id_user', $id);
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0)
			return current($consulta->result());
		else
			return FALSE;
	}
	
	function getUser($id){
		$this->db->select('*');
		$this->db->from($this->nombre2);
		$this->db->where('id', $id);
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0)
			return current($consulta->result());		
	}
	
	
	function obtenerRegistro($id){
		$this->db->select('*');
		$this->db->from($this->nombre2);
		$this->db->where('id_user', $id);
		$consulta = $this->db->get();
		return current($consulta->result());
	}
	
	// recupera cuantos ya a compartido el usuario
	function obtenerCampartidos($id)    {
		$this->db->select('compartidos');
		$this->db->from($this->nombre2);
		$this->db->where('id_user', $id);
		$consulta = $this->db->get();
		return current($consulta->result());
	}	
	
	function agradecimiento(){
		echo "Gracias";
	}
	
	function registrarInvitados($id){
		$arreglo = json_decode($_POST['data']);
		$total   = count($arreglo);
		if($total>0){
			$dato    = $this->modelo->obtenerCampartidos($id);
			$data['user']= $this->modelo->obtenerRegistro($id);
	
			$val_nuevo = $total + (int)$dato->compartido;
			$data_update = array(
					"compartidos" => (string)$val_nuevo);
			$this->db->where(array('id_user' => $id));
			$this->db->update($this->nombre2, $data_update);
			if ($val_nuevo%5==0 || $total > 5)
				echo "C-0";
			else
				echo "I-".($val_nuevo%5);
		}
	}
	
	
	function verAmigos($id){
		$this->db->select("fbid");
		$this->db->from("unete_galeria_invitados");
		$this->db->where("DATE_FORMAT(creado,'%d')","DATE_FORMAT(NOW(),'%d')",FALSE);
		$this->db->where("id_user",$id);
		$consulta=$this->db->get()->result();
		return $consulta;
	}
	
	
}








































