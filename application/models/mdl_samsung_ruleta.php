<?php
class Mdl_samsung_ruleta extends CI_Model{
    var $nombre2;
    
    function __construct(){
        parent::__construct();    
        $this->nombre2 = "samsung_ruleta";
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
	function verificarUserOportinidad($id){


        $this->db->select('*');
        $this->db->from($this->nombre2);
        $this->db->where('id_user', $id);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0) {
            $compartido = current($consulta->result());
            if ($compartido->oportunidades>=2){
                return false;
            } else {
                return true;
            }

        } else {
            return true;
        }

	}

	function cuentacompartidos($id){

        $this->db->select('*');
        $this->db->from($this->nombre2);
        $this->db->where('id_user', $id);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0) {
            $compartido = current($consulta->result());
            return  $compartido->compartidos;

        } else {
            return 0;
        }

	}
    function listadoinventario($tipo){
		$this->db->select('*');
		$this->db->from("samsung_ruleta_premios");
		$this->db->where('tipo', $tipo);
		$this->db->order_by('id', "asc");
        $array = array('stock >' => 0);
        $this->db->where($array);
		$this->db->where('tipo', $tipo);
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0)
            return $consulta->result();
		else
			return FALSE;
	}



    function buscarNoRegistrado($id){
        $this->db->select('*');
        $this->db->from("samsung_usuarios");
        $this->db->where('id', $id);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0)
            return true;
        else
            return FALSE;
    }

    function buscarYaGano($iduser) {
        $this->db->select('*');
        $this->db->from("samsung_ruleta");
        $this->db->where('id_user', $iduser);
        $this->db->where('resultado IS NOT NULL', null);
        $consulta = $this->db->get();
        $test = $this->db->last_query();

        if ($consulta->num_rows() > 0){
            return TRUE;
        }else
            return FALSE;
    }

    function buscarSorteoMediaHora() {

        $this->db->select('*');
        $this->db->from("samsung_ruleta");
        $this->db->where('creado >', "ADDTIME(NOW(), -100)", false);


        $this->db->where('resultado IS NOT NULL', null);


        $consulta = $this->db->get();

        $test = $this->db->last_query();
        if ($consulta->num_rows() > 0){
            return TRUE;
        }else
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


    function buscarUser($id)
    {
        $this->db->select('*');
        $this->db->from($this->nombre2);
        $this->db->where('id_user', $id);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0) {
            $registro = current($consulta->result());
            if ($registro->oportunidades > 3) {
                return false;
            } else {
                return $registro;
            }
        } else {
            $this->db->insert($this->nombre2, array("id_user" => $id));
            $this->db->select('*');
            $this->db->from($this->nombre2);
            $this->db->where('id_user', $id);
            $this->db->get();
            return false;
        }
    }
	function buscarNombrePremio($id){
		$this->db->select('*');
		$this->db->from("samsung_ruleta_premios");
		$this->db->where('id', $id);
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0)
			return current($consulta->result());

	}

	
	function getUser($id){
		$this->db->select('*');
		$this->db->from($this->nombre2);
		$this->db->where('id', $id);
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0)
			return current($consulta->result());		
	}


	
	function actualizarPuntaje($idUser,$totalPuntaje){
		$this->db->select("puntos,juegos");
		$this->db->from($this->nombre2);
		$this->db->where('id_user', $idUser);
		$consulta = $this->db->get();
		if ($consulta->num_rows() > 0){
			$consulta=current($consulta->result());
			if((int)$consulta->puntos<(int)$totalPuntaje){
				$juegos=(int)$consulta->juegos;
				$juegos=$juegos+1;					
				$this->db->where('id_user', $idUser);
				$this->db->update($this->nombre2,array("puntos"=>$totalPuntaje,"juegos"=>$juegos));
			}
			$juegos=(int)$consulta->juegos;
			$juegos=$juegos+1;
			$this->db->where('id_user', $idUser);
			$this->db->update($this->nombre2,array("juegos"=>$juegos));			
		}



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
	   
    
}	






