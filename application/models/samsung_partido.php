<?php
class Samsung_partido extends  CI_Model {
	var $nombre2;
	
	function __construct(){
		// Call the Model constructor
		parent::__construct();
			$this->nombre2='partido';
			$this->load->database('samsung');
	}
	
	function obtenerPartido(){
		$this->db->select("*");
		$this->db->from($this->nombre2);
		$this->db->where("DATE_FORMAT(fecha_fin,'%d')>=","DATE_FORMAT(curdate(),'%d')", false);
		$this->db->where("DATE_FORMAT(fecha_inicio,'%d')<=","DATE_FORMAT(curdate(),'%d')", false);
		$consulta=$this->db->get();
		if ($consulta->num_rows()>0)
			return current($consulta->result());
		ELSE
			return FALSE;
	}
	
	function obtenerCampartidos($id){
		$this->db->select('compartidos');
		$this->db->from('pronostico');
		$this->db->where('id_user',$id);
		$consulta=$this->db->get();
		return current($consulta->result());
		
	}
	
	function insertarJuego($datos){		
		if ($this->verificarRegistro($datos->id_user)==FALSE) 
			$this->db->insert("pronostico",$datos);		
	}
	
	function verifcarAmigo($id){
		$this->db->select("id_amigo");
		$this->db->from("pronostico_compartido");
		$this->db->where("DATE_FORMAT(creado,'%d')","DATE_FORMAT(NOW(),'%d')",FALSE);
		$this->db->where("id_user",$id);
		$consulta=$this->db->get()->result();
		return $consulta;
	}
	
	
	
	function obtenerPronosticos(){
		$this->db->select("count(*) as cantidad, resultado_local, resultado_visitante");
		$this->db->from("pronostico");
		$this->db->limit(5);
		$this->db->group_by("resultado_local, resultado_visitante");
		$this->db->order_by("count(*) >1","desc");
		$consulta=$this->db->get()->result();
		return $consulta;
		
	}
	
	function verificarRegistro($id){
		$this->db->where("id_user",$id);
		$query=$this->db->get("pronostico");		
		if ($query->num_rows()>0)
			return TRUE;
		ELSE
			return FALSE;		
	}
	
	
	function get($id){
		$this->db->select('*');
		$this->db->from($this->nombre2);
		$this->db->where("id",$id);
		$consulta=$this->db->get();		
		$datos='';
		$cont=0;
		if($consulta->num_rows()>0){
			$row=$consulta->row();
			$datos->id=$row->id;
			$datos->local=$row->local;
			$datos->visitante=$row->visitante;
			$datos->resultado_local=$row->resultado_local;
			$datos->resultado_visitante=$row->resultado_visitante;
			$datos->imagen_local=$row->imagen_local;
			$datos->imagen_visitante=$row->imagen_visitante;
			$datos->resultado_local=$row->resultado_local;
			$datos->resultado_visitante=$row->resultado_visitante;
			$datos->fondo=$row->fondo;
			$datos->fecha_inicio=$row->fecha_inicio;
			$datos->fecha_fin=$row->fecha_fin;
			return $datos;
		}
		else
			return FALSE;
	}
	
	function busquedaUser($id){
		$query=$this->db->query('Select *
				From movistar_jugador Where id='.$id);
		if($query->num_rows()>0)
			return TRUE;
		else
			RETURN FALSE;
		
	}
	
	function getTop(){		
		$this->db->select('*');
		$this->db->from('jugador');
		$this->db->join('usuarios', 'usuarios.id = jugador.id_user');
		$this->db->order_by("jugador.top_puntaje", "desc");
		$this->db->limit(20);
		$query = $this->db->get();
			
		if($query->num_rows()>0)
			return $query->result();
		else
			return FALSE;
	}
	
	function getJuegos(){
		return $this->db->count_all_results('juego');
	}
	
	function getTop2(){
		$this->db->select('*');
		$this->db->from('jugador');
		$this->db->join('usuarios', 'usuarios.id = jugador.id_user');
		$this->db->order_by("jugador.top_puntaje", "desc");
		$this->db->limit(6);
		$query = $this->db->get();
	
		if($query->num_rows()>0)
			return $query->result();
		else
			return FALSE;	
	}
	
	
	function getUsuarios($id){
		$this->db->select('*');
		$this->db->from('usuarios');
		$this->db->where('fbid',$id);
		return $query=current($this->db->get());		
	}
	
	
	function getRegistros(){
		return $dato=$this->db->count_all_results('jugador');		
	}
	
	function actualizar_puntaje($id,$datos){			
		$this->db->select('*');
		$this->db->from('jugador');
		$this->db->where('id_user',$id);
		$query=$this->db->get();		
		if($query->num_rows()>0){
			$this->db->where('id_user',$id);
			$this->db->update('jugador',$datos);
		}
		else
			$this->db->insert('jugador',$datos);		
	}
		
	function actualizarCreditos($id,$datos){
		$this->db->where('id_user',$id);
		$this->db->update('jugador',$datos);
	}
	
	function actualizar_tiempo($id,$puntos){
		$this->db->where('id',$id);
		$this->db->set('puntaje',$puntos);
		$this->db->set('fecha_fin', 'NOW()', FALSE);
		$this->db->update('juego');
	}
	
	function indices(){
	   $this->db->select('id');
  	   $this->db->from($this->nombre2);
	   $consulta=$this->db->get();          
	   return $consulta->result();
	}
	
	function getAllByTipo($inicio,$fin){
		if(!$inicio && !$fin){
			$limite='';
		}
		else{
			if(!$inicio)
				$limite=' LIMIT '.$fin;
			else
				$limite=' LIMIT '.$inicio.','.$fin;
		}
		$this->db->get($this->nombre2, $limite);
		$this->db->order_by('nombre');
		$query=$this->db->get();
		$datos='';
		$cont=0;
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$datos[$row->id]->id=$row->id;
				$datos[$row->id]->nombre=$row->nombre;
				$datos[$row->id]->imagen=$row->imagen;
				$datos[$row->id]->thumb=$row->thumb;
				$cont++;	
			}
			return $datos;
		}
		else
			return FALSE;
	}
	
	function obtener(){
	   $this->db->select('*');
	   $this->db->from($this->nombre2);
	   $this->db->limit(10);
	   $this->db->order_by("id","random");
	   $listar=$this->db->get();
	   return $listar->result();
	}
	
	
	function getAll($inicio,$fin){	
		if(!$inicio && !$fin){
			$limite='';
		}
		else{
			if(!$inicio){
				$limite=' LIMIT '.$fin;
			}else
				$limite=' LIMIT '.$inicio.','.$fin;
		}
		
		$query=$this->db->query('Select *
				From samsung_'.$this->nombre2.$limite);
		$datos='';
		$cont=0;
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$datos[$row->id]->id=$row->id;
				$datos[$row->id]->local=$row->local;
				$datos[$row->id]->visitante=$row->visitante;
				$datos[$row->id]->creado=$row->creado;
				$datos[$row->id]->fecha_inicio=$row->fecha_inicio;
				$datos[$row->id]->fecha_fin=$row->fecha_fin;
				$cont++;
			}
			return $datos;
		}
		else
			return FALSE;
	}
	
	function countAll(){
		$this->db->select("count(*) as max");
		$this->db->from($this->nombre2);
		$query=$this->db->get();		
		if($query->num_rows()>0){
			$resultado=$query->row();
			return $resultado->max;
		}
		else
			return FALSE;
	}
	
	function insert($datos){
		return $this->db->insert($this->nombre2,$datos);
	} 
	
	function update($datos){
		$this->db->where('id',$datos['id']);
		return $this->db->update($this->nombre2,$datos);
	} 
	
	function delete($datos){
		$this->db->where('id',$datos['id']);
		return $this->db->delete($this->nombre2,$datos);
	}
	
}	