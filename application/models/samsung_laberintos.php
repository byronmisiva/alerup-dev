<?php
class Samsung_laberintos extends  CI_Model {
	var $nombre2;
	
	function __construct(){
		// Call the Model constructor
		parent::__construct();
			$this->nombre2='laberinto';
			$this->load->database('samsung');
	}
	
	function busquedaRegistro($id){
		$this->db->select('*');
		$this->db->from($this->nombre2);
		$this->db->where('id_user',$id);		
		$consulta=$this->db->get();	
		if($consulta->num_rows()>0)
			return 1;
		else
			return 0;
	}
	
	function obtenerPuntosTotales(){
		$consulta=$this->db->get('laberinto');		
		return $consulta->result();
	}
	
	function obtenerAmigosPuntos($id){
		$this->db->select('distinct(id_user)');
		$this->db->where('id_amigo',$id);
		$this->db->from('laberinto_compartido');
		$consulta=$this->db->get();
		if($consulta->num_rows()>0)
			return $consulta->result();
		else
			return FALSE; 
	}
	
	function obtenerfbid($id){		
		$this->db->where('id',$id);
		$this->db->from('usuarios');
		$consulta=$this->db->get();
		$consulta=current($consulta->result());		
		 $this->puntos2($consulta->fbid);
		 echo "codigo: ".$this->db->last_query()."<br/>";
		
	}
	
	function puntos2($fbid){
		$this->db->select('distinct(id_user)');
		$this->db->where('id_amigo',$fbid);
		$this->db->from('laberinto_compartido');
		$consulta=$this->db->get()->result();
		foreach($consulta as $row2)
			$this->puntajes($row2->id_user);		
	}
	
	function puntajes($id){
		$this->db->where('id_user',$id);
		$this->db->from($this->nombre2);
		$consulta=$this->db->get();
		$consulta=current($consulta->result());
		
		$puntos=(int)$consulta->nuevoscompartidos;
		$puntos++;
		$total=array('nuevoscompartidos' =>	$puntos);
		$this->db->where('id_user',$id);
		$this->db->update($this->nombre2,$total);
	}
		
	function sumarPuntaje($id){
		$this->db->where('id_user',$id);
		$this->db->from($this->nombre2);
		$consulta=$this->db->get();
		$consulta=current($consulta->result());
		
		$puntos=(int)$consulta->nuevoscompartidos;
		$puntos++;
		$total=array('nuevoscompartidos' =>	$puntos);
		$this->db->where('id_user',$id);
		$this->db->update($this->nombre2,$total);
	}
	
	function busquedaTipo($id){
		$this->db->select('*');
		$this->db->from($this->nombre2);
		$this->db->where('id_user',$id);		
		$consulta=$this->db->get();
		if($consulta->num_rows()>0)
			return 1;
		else
			return 0;
	}
		
	function busquedaJugador($id,$pageid){
		$this->db->select("*");
		$this->db->from($this->nombre2);
		$this->db->where("id_user",$id);
		$this->db->where("pageid",$pageid);
		$query=$this->db->get();
		
		if($query->num_rows()>0)			
				return TRUE;
			else			
				RETURN FALSE;	
	}
	
	function getJugadores(){		
		$consulta=$this->db->get('samsung_jugadores');
		$opcion=array(''=>'Escoger Jugador');
		foreach ($consulta->result() as $row)
			$opcion[$row->id]=$row->nombres;
		return $opcion;		
	}

	function obtJugadores(){
		$consulta=$this->db->get('samsung_jugadores');		
		return $consulta->result();
	}
			
	function verificarCompartidos($id,$pageid){
		$this->db->select("*");
		$this->db->from($this->nombre2);
		$this->db->where("id_user",$id);
		$this->db->where("id_page",$pageid);
		$query=$this->db->get();		
		if($query->num_rows()>0){
			$consulta=current($query->result());
				if( ( $consulta->compartidos == "0" ) && ($consulta->activo == "1" )) {
					return TRUE;
				}else if ( ( (int)$consulta->compartidos%10 != 0 ) && ($consulta->activo == "1" ) ) {
						return TRUE;
				}else{ 
						return FALSE;	
				}								
		}		
	}
	
	function totalCompartidos($id){		
		$this->db->select("compartido");
		$this->db->from($this->nombre2);
		$this->db->where("id_user",$id);
		$query=$this->db->get();		
		if($query->num_rows()>0)
			return 	current($query->result());
	}
		
	function obtenerBloqueo($id){
		$this->db->select('bloqueo');
		$this->db->from($this->nombre2);
		$this->db->where('id_user',$id);		
		$consulta=$this->db->get();
		$compartir=current($consulta->result());
		$valor= (int)$compartir->bloqueo;
		return $valor;		
	}
	
	function obtenerCampartidos($id){
		$this->db->select('compartido');
		$this->db->from($this->nombre2);
		$this->db->where('id_user',$id);		
		$consulta=$this->db->get();
		return current($consulta->result());
	}
	

	
	function actualizar($tiempo,$movimiento,$user){
		$this->db->select("time_to_sec(tiempo)tiempo1, movimientos, time_to_sec('".$tiempo."')tiempo2");
		$this->db->where("id_user",$user);
		$this->db->from('laberinto');
		$query=current($this->db->get()->result());
		$tiempo1=(int)$query->tiempo1;
		$tiempo2=(int)$query->tiempo2;
		
		$movi1=(int)$query->movimientos;
		$movi2=(int)$movimiento;
		if ($tiempo1 ==0){
			$dato=array("tiempo"=>$tiempo2);		
			$this->db->where("id_user",$user);
			$this->db->update('laberinto',$dato);
		}elseif($tiempo2<$tiempo1 && $tiempo1 !=0 ){
			$dato=array("tiempo"=>$tiempo2);
			$this->db->where("id_user",$user);
			$this->db->update('laberinto',$dato);			
		}
		if ($movi1 ==0){
			$dato=array("movimientos"=>$movi2  );
			$this->db->where("id_user",$user);
			$this->db->update('laberinto',$dato);
		}elseif ($movi2<$movi1 ){
			$dato=array("movimientos"=>$movi2  );
			$this->db->where("id_user",$user);
			$this->db->update('laberinto',$dato);
		}		
	}
	
	
	function verAmigos($id){
		$this->db->select("id_amigo");
		$this->db->from("laberinto_compartido");
		$this->db->where("DATE_FORMAT(creado,'%d')","DATE_FORMAT(NOW(),'%d')",FALSE);
		$this->db->where("id_user",$id);		
		$consulta=$this->db->get()->result();
		return $consulta;
	}

	function get_Laberinto_Puntaje($id){
		$this->db->select('puntaje');
		$this->db->where('id_user',$id);
		$this->db->from('laberinto_resultados');	
		$consulta = $this->db->get();		
		return current($consulta->result());
	}

	function getTop2(){
		$this->db->select('*');
		$this->db->from('laberinto_resultados');
		$this->db->limit(5);
		$query = $this->db->get();
		if($query->num_rows()>0)
			return $query->result();
		else
			return FALSE;
	}
}	