<?php
class Samsung_modo_futbols extends CI_Model
{
    var $nombre2;
    var $nombre1;

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->nombre2 = 'modo_futbol';
        $this->nombre1 = 'modo_futbol_compartido';
        $this->load->database('samsung');
    }

    function busquedaRegistro($id)
    {
        $this->db->select('*');
        $this->db->from($this->nombre2);
        $this->db->where('id_user', $id);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0)
            return 1;
        else
            return 0;
    }

   function obtenerUserEtapa(){
    	$this->db->where("etapa","1");
    	$this->db->from("modo_futbol");
    	$consulta = $this->db->get();
    	return $consulta->result();
    }

    function obtenerPuntosTotales()
    {
        $consulta = $this->db->get('modo_futbol');
        return $consulta->result();
    }

    function obtenerAmigosPuntos($id)
    {
        $this->db->select('distinct(id_user)');
        $this->db->where('id_amigo', $id);
        $this->db->from('modo_futbol_compartido');
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0)
            return $consulta->result();
        else
            return FALSE;
    }

    function obtenerfbid($id)
    {
        $this->db->where('id', $id);
        $this->db->from('usuarios');
        $consulta = $this->db->get();
        $consulta = current($consulta->result());
        $this->puntos2($consulta->fbid);
        echo "codigo: " . $this->db->last_query() . "<br/>";

    }

    function puntos2($fbid)
    {
        $this->db->select('distinct(id_user)');
        $this->db->where('id_amigo', $fbid);
        $this->db->from('modo_futbol_compartido');
        $consulta = $this->db->get()->result();
        foreach ($consulta as $row2)
            $this->puntajes($row2->id_user);
    }

    function puntajes($id)
    {
        $this->db->where('id_user', $id);
        $this->db->from($this->nombre2);
        $consulta = $this->db->get();
        $consulta = current($consulta->result());

        $puntos = (int)$consulta->compartidoefectivo;
        $puntos++;
        $total = array('compartidoefectivo' => $puntos);
        $this->db->where('id_user', $id);
        $this->db->update($this->nombre2, $total);
    }

    function sumarPuntaje($id)
    {
        $this->db->where('id_user', $id);
        $this->db->from($this->nombre2);
        $consulta = $this->db->get();
        $consulta = current($consulta->result());

        if (isset($consulta->compartidoefectivo)) {
            $puntos = (int)$consulta->compartidoefectivo;
            $puntos++;
            $total = array('compartidoefectivo' => $puntos);
            $this->db->where('id_user', $id);
            $this->db->update($this->nombre2, $total);                        
            if ($puntos == 5){            
                return TRUE;
            }else{
                return FALSE;
            }
        }
    }

    function obtenerDatosUser($id)
    {
        $this->db->select('completo,mail');
        $this->db->where('id', $id);
        $this->db->from("usuarios");
        $consulta = $this->db->get();
        return current($consulta->result());
    }

    function busquedaTipo($id)
    {
        $this->db->select('*');
        $this->db->from($this->nombre2);
        $this->db->where('id_user', $id);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0)
            return 1;
        else
            return 0;
    }

    function busquedaJugador($id, $pageid)
    {
        $this->db->select("*");
        $this->db->from($this->nombre2);
        $this->db->where("id_user", $id);
        $this->db->where("pageid", $pageid);
        $query = $this->db->get();

        if ($query->num_rows() > 0)
            return TRUE;
        else
            RETURN FALSE;
    }

    function getJugadores()
    {
        $consulta = $this->db->get('samsung_jugadores');
        $opcion = array('' => 'Escoger Jugador');
        foreach ($consulta->result() as $row)
            $opcion[$row->id] = $row->nombres;
        return $opcion;
    }

    function obtJugadores()
    {
        $consulta = $this->db->get('samsung_jugadores');
        return $consulta->result();
    }

    function verificarCompartidos($id, $pageid)
    {
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

    function totalCompartidos($id)
    {
        $this->db->select("compartido, compartidoefectivo");
        $this->db->from($this->nombre2);
        $this->db->where("id_user", $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
            return current($query->result());
    }

    function getRespuestavideo($id)
    {
        $this->db->select("respuestavideo");
        $this->db->from($this->nombre2);
        $this->db->where("id_user", $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
            return current($query->result());
    }

    function getCalificacionconcurso($id)
    {
        $this->db->select("calificacionconcurso");
        $this->db->from($this->nombre2);
        $this->db->where("id_user", $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
            return current($query->result());
    }

    function obtenerBloqueo($id)
    {
        $this->db->select('bloqueo');
        $this->db->from($this->nombre2);
        $this->db->where('id_user', $id);
        $consulta = $this->db->get();
        $compartir = current($consulta->result());
        $valor = (int)$compartir->bloqueo;
        return $valor;
    }

    // recupera cuantos ya a compartido el usuario
    function obtenerCampartidos($id)
    {
        $this->db->select('compartido');
        $this->db->from($this->nombre2);
        $this->db->where('id_user', $id);
        $consulta = $this->db->get();
        return current($consulta->result());
    }


    function actualizar($tiempo, $movimiento, $user)
    {
        $this->db->select("time_to_sec(tiempo)tiempo1, movimientos, time_to_sec('" . $tiempo . "')tiempo2");
        $this->db->where("id_user", $user);
        $this->db->from('modo_futbol');
        $query = current($this->db->get()->result());
        $tiempo1 = (int)$query->tiempo1;
        $tiempo2 = (int)$query->tiempo2;

        $movi1 = (int)$query->movimientos;
        $movi2 = (int)$movimiento;
        if ($tiempo1 == 0) {
            $dato = array("tiempo" => $tiempo2);
            $this->db->where("id_user", $user);
            $this->db->update('modo_futbol', $dato);
        } elseif ($tiempo2 < $tiempo1 && $tiempo1 != 0) {
            $dato = array("tiempo" => $tiempo2);
            $this->db->where("id_user", $user);
            $this->db->update('modo_futbol', $dato);
        }
        if ($movi1 == 0) {
            $dato = array("movimientos" => $movi2);
            $this->db->where("id_user", $user);
            $this->db->update('modo_futbol', $dato);
        } elseif ($movi2 < $movi1) {
            $dato = array("movimientos" => $movi2);
            $this->db->where("id_user", $user);
            $this->db->update('modo_futbol', $dato);
        }
    }


    function verAmigos($id)
    {
        $this->db->select("id_amigo");
        $this->db->from("modo_futbol_compartido");
        $this->db->where("DATE_FORMAT(creado,'%d')", "DATE_FORMAT(NOW(),'%d')", FALSE);
        $this->db->where("id_user", $id);
        $consulta = $this->db->get()->result();
        return $consulta;
    }

    function get_modo_futbol_Puntaje($id)
    {
        $this->db->select('puntaje');
        $this->db->where('id_user', $id);
        $this->db->from('modo_futbol_resultados');
        $consulta = $this->db->get();
        return current($consulta->result());
    }

    function getTop2()
    {
        $this->db->select('*');
        $this->db->from('modo_futbol_resultados');
        $this->db->limit(5);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return FALSE;
    }

    //nuevas funciones
    function setEtapaUser($id, $etapa)
    {
        $etapaUpdate = array('etapa' => $etapa);
        $this->db->where('id_user', $id);
        $this->db->update($this->nombre2, $etapaUpdate);
        return 1;
    }

    function setPuntosEtapa2($id, $puntos)
    {
        $etapaUpdate = array('respuestavideo' => $puntos);
        $this->db->where('id_user', $id);
        $this->db->update($this->nombre2, $etapaUpdate);
        return 1;
    }

    function setPuntosEtapa3($id,  $declaracion, $concurso)
    {
        $etapaUpdate = array('declaracion' => $declaracion,'concurso' => $concurso );
        $this->db->where('id_user', $id);
        $this->db->update($this->nombre2, $etapaUpdate);
        return 1;
    }

    function getEtapaUser($id)
    {
        $this->db->select('etapa');
        $this->db->where('id_user', $id);
        $this->db->from($this->nombre2);
        $consulta = $this->db->get();
        return current($consulta->result());
    }

    function puntajesUser($id)
    {
        $this->db->where('id_user', $id);
        $this->db->from($this->nombre2);
        $consulta = $this->db->get();
        $consulta = current($consulta->result());

        $puntos = (int)$consulta->compartidoefectivo;
        $puntos++;
        $total = array('compartidoefectivo' => $puntos);
        $this->db->where('id_user', $id);
        $this->db->update($this->nombre2, $total);
    }
}	
