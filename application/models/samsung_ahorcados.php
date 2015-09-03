<?php
class Samsung_ahorcados extends CI_Model{
    var $nombre2;
    var $nombre1;

    function __construct(){
        // Call the Model constructor
        parent::__construct();
        $this->nombre2 = 'ahorcado';
        $this->nombre1 = 'ahorcado_compartido';
        $this->load->database('samsung');
    }

    function busquedaRegistro($id){
        $this->db->select('*');
        $this->db->from($this->nombre2);
        $this->db->where('id_user', $id);
        $consulta = $this->db->get();    
        if ($consulta->num_rows() > 0)
            return 1;
        else
            return 0;
    }
    
    function obtenerPosicion(){
    	$this->db->select('count(posicion)as posicion');
    	$this->db->where('posicion !=','0');
    	$this->db->from($this->nombre2);    	
    	$consulta = $this->db->get();
    	return current($consulta->result());    	
    }
    
    function obtenerRegistro($id){
    	$this->db->select('*');
    	$this->db->from($this->nombre2);
    	$this->db->where('id_user', $id);
    	$consulta = $this->db->get();    	
    	return current($consulta->result());
    }
    
    function frase($semana){
    	$this->db->where("semana",$semana);
    	$this->db->from("ahorcado_frase");
    	$consulta = $this->db->get();
    	return current($consulta->result());
    }
    
    function verificarLetra($letra,$semana,$dia){
    	$dias=array("1"=>"dia1","2"=>"dia2","3"=>"dia3","4"=>"dia4","5"=>"dia5");
    	if($dia>5)
    		$dia=5;
    	
    	$this->db->where("UPPER(".$dias[$dia].")",strtoupper($letra));    	    	
    	$this->db->where("semana",$semana);
    	$this->db->from("ahorcado_frase");
    	$consulta = $this->db->get();
    	if ($consulta->num_rows() > 0)
    		return 1;
    	else 	
    		return 0;
    	
    }
    
    function verificarLetraBono($letra,$semana,$dia){    	
    	$bonos=array("1"=>"bon1","2"=>"bon2","3"=>"bon3","4"=>"bon4","5"=>"bon5");
    	if($dia>5)
    		$dia=5;
    	$this->db->where("UPPER(".$bonos[$dia].")",strtoupper($letra));
    	$this->db->where("semana",$semana);
    	$this->db->from("ahorcado_frase");
    	$consulta = $this->db->get();
    	if ($consulta->num_rows() > 0)
    		return 1;
    	else
    		return 0;
    }
    
    function verificarLetradeldia($letra,$semana,$dia){
    	$dias=array("1"=>"dia1","2"=>"dia2","3"=>"dia3","4"=>"dia4","5"=>"dia5");
    	if($dia>5)
    		$dia=5;
    	$this->db->where("semana",$semana);
    	$this->db->where("UPPER(".$dias[$dia].")",strtoupper($letra));    	
    	$this->db->from("ahorcado_frase");
    	$consulta = $this->db->get();
    	if ($consulta->num_rows() > 0)
    		return 1;
    	else
    		return 0;  

    	
    	
    }
    
    function verificarbono($bono,$semana,$dia){    	
    	$bonos=array("1"=>"bon1","2"=>"bon2","3"=>"bon3","4"=>"bon4","5"=>"bon5");
    	if($dia>5)
    		$dia=5;
    	$this->db->where("semana",$semana);
    	$this->db->where("UPPER(".$bonos[$dia].")",strtoupper($bono));
    	$this->db->from("ahorcado_frase");
    	$consulta = $this->db->get();
    	if ($consulta->num_rows() > 0)
    		return 1;
    	else
    		return 0;
    }
    
    function obtenerBono($semana,$dia){
    	$dias=array("1"=>"bon1","2"=>"bon2",
    			    "3"=>"bon3","4"=>"bon4",
    			    "5"=>"bon5");
    	
    	if ($dia>5)
    		$dia=5;
    	$this->db->select($dias[$dia]." as letra");
    	$this->db->where("semana",$semana);    	
    	$this->db->from("ahorcado_frase");
    	$consulta = $this->db->get();
    	if ($consulta->num_rows() > 0)
    		return current($consulta->result());
    	    	
    }
  

    function obtenerfbid($id)    {
        $this->db->where('id', $id);
        $this->db->from('usuarios');
        $consulta = $this->db->get();
        $consulta = current($consulta->result());
        $this->puntos2($consulta->fbid);
        echo "codigo: " . $this->db->last_query() . "<br/>";

    }
  

    function obtenerDatosUser($id)    {
        $this->db->select('completo,mail');
        $this->db->where('id', $id);
        $this->db->from("usuarios");
        $consulta = $this->db->get();
        return current($consulta->result());
    }

    function busquedaTipo($id)    {
        $this->db->select('*');
        $this->db->from($this->nombre2);
        $this->db->where('id_user', $id);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0)
            return 1;
        else
            return 0;
    }

    function busquedaJugador($id)    {
        $this->db->select("*");
        $this->db->from($this->nombre2);
        $this->db->where("id_user", $id);       
        $query = $this->db->get();

        if ($query->num_rows() > 0)
            return TRUE;
        else
            RETURN FALSE;
    }
   

    function verificarCompartidos($id, $pageid)    {
        $this->db->select("*");
        $this->db->from($this->nombre2);
        $this->db->where("id_user", $id);
        $this->db->where("id_page", $pageid);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $consulta = current($query->result());
            if (($consulta->compartidos == "0") && ($consulta->activo == "1")) {
                return TRUE;
            } else if (((int)$consulta->compartidos % 10 != 0) && ($consulta->activo == "1")) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    function totalCompartidos($id)    {
        $this->db->select("compartido, compartidoefectivo");
        $this->db->from($this->nombre2);
        $this->db->where("id_user", $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
            return current($query->result());
    }

   
    // recupera cuantos ya a compartido el usuario
    function obtenerCampartidos($id)    {
        $this->db->select('compartido');
        $this->db->from($this->nombre2);
        $this->db->where('id_user', $id);
        $consulta = $this->db->get();
        return current($consulta->result());
    }
 


    function verAmigos($id)    {
        $this->db->select("id_amigo");
        $this->db->from("modo_futbol_compartido");
        $this->db->where("DATE_FORMAT(creado,'%d')", "DATE_FORMAT(NOW(),'%d')", FALSE);
        $this->db->where("id_user", $id);
        $consulta = $this->db->get()->result();
        return $consulta;
    }

}	
