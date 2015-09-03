<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Samsung_baterias6 extends CI_Controller
{

    public $data;
    var $folderView;
    var $controlador;
    var $idApp;
    var $secretApp;
    var $condiciones;
    var $dispositivo;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('samsung_usuario', 'usuario_samsung');
        $this->load->model('mdl_samsung_baterias6', 'modelo');
        $this->load->helper('form');
        $this->folderView = "samsung_baterias6";
        $this->data['controlador'] = "samsung_baterias6";
        $this->data['idApp'] = "1380785488887714";
        $this->data['secretApp'] = "4c374f5dc04e4b142bcd50e2ca927490";
$this->data['condiciones'] = "<a href='".base_url()."archivos/carga-rapida/REGLAMENTO-DE-TERMINOS-Y-CONDICIONES-PARA-EL-CONCURSO-CARGA-RAPIDA.pdf' target='_blank' >T&eacute;rminos y Condiciones</a>";
    }

    function index()
    {
        $this->load->library('user_agent');
        $mobiles = array('Apple iPhone', 'Apple iPod Touch', 'Android', 'Apple iPad');
        /*if ( $this->agent->is_mobile() )
            $this->movil();
        else{
            $this->data['dispositivo']="normal";
            $this->load->view($this->folderView.'/index', array( 'data' =>$this->data) );
        }*/
        $this->data['dispositivo'] = "normal";
        $this->load->view($this->folderView . '/index', array('data' => $this->data));
    }

    function cargaCrop($nombre)
    {
        $data['imagen'] = $nombre;
        $this->load->view($this->folderView . '/view-crop', $data);
    }


    function getUserList()
    {
        $data['registros'] = $this->modelo->listarusuariosregistrados();
        $this->load->view($this->folderView . '/view-listado', $data);
    }

    function cargaGaleria()
    {
        $data['galeria'] = $this->modelo->listargaleriaregistrados($_POST["iduser"]);
        $this->load->view($this->folderView . '/view-galeria-registros', $data);

    }

    function imagenPieza()
    {
        $data['script'] = "";
        if (isset($_FILES['image'])) {
            $config['upload_path'] = './galaxy-a/uploads/';
            $config['allowed_types'] = 'jpeg|gif|jpg|png';
            $config['max_size'] = '20000';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('image')) {
                $error = array('error' => $this->upload->display_errors());
                $data['script'] = "<script>alert('Error... Al cargar imagen');parent.$('#carga').hide();</script> ";
            } else {
                $upload = $this->upload->data();
                $nombre = $upload["file_name"];
                $data['script'] = "
				<script>					
					parent.$('#archivo1').val('" . $nombre . "');
					parent.$('.espera').hide();
					parent.llamarCrop();
				</script>";
            }
        }
        $this->load->view($this->folderView . '/cargaimagen', $data);
    }

    function imagenMobile()
    {
        $data['script'] = "";
        if (isset($_FILES['image'])) {
            $config['upload_path'] = './galaxy-a/uploads/';
            $config['allowed_types'] = 'jpeg|gif|jpg|png';
            $config['max_size'] = '20000';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('image')) {
                $error = array('error' => $this->upload->display_errors());
                $data['script'] = "<script>alert('Error... Al cargar imagen');parent.$('#carga').hide();</script> ";
            } else {
                $upload = $this->upload->data();
                $nombre = $upload["file_name"];
                $data['script'] = "
				<script>
					parent.$('#archivo1').val('" . $nombre . "');
					parent.$('.espera').hide();
					parent.llamarCrop();
				</script>";
            }
        }
        $this->load->view($this->folderView . '/cargaimagenMobile', $data);
    }

    function movil()
    {
        $this->data['dispositivo'] = "movil";
        $this->load->view($this->folderView . '/movil', array('data' => $this->data));
    }


    function verificarParticipante($id)
    {
        $participante = $this->usuario_samsung->getUserFbid($id);
        if ($participante == "0") {
            echo "F";
        } else {
            $registro = $this->modelo->buscarUser($id);
            if ($registro == FALSE)
                echo "F";
            else {
                $participante = $this->modelo->buscarUserFbid($id);
                $compartidos = (int)$participante->concurencia;
                $compartidos = $compartidos + 1;
                $this->db->where("fbid", $id);
                $this->db->update("bateria_registro", array("concurencia" => $compartidos));
                echo "A";
            }
        }
    }

    function savePuntage()
    {
        if (isset($_POST['puntos'])) {
            //recupero puntaje anterior
            $participante = $_POST['participante'];

            $data = $this->db->query("SELECT puntos FROM samsung_bateria_registro WHERE fbid = '" . $participante . "'")->result();
            $puntosOld = $data[0]->puntos;

            if ($_POST['puntos'] > $puntosOld) {
                $this->db->where("fbid", $participante);
                $this->db->update("bateria_registro", array("puntos" => $_POST['puntos'], "tiempo" => $_POST['tiempo']));
            }
        }
    }

    function getRanking()
    {
        $data['registros'] = $this->modelo->getRanking();
        $this->load->view($this->folderView . '/ranking', $data);
    }

    function register()
    {
        $resp = "0";
        if (isset($_POST['nombre'])) {
            if ($this->usuario_samsung->alreadyRegistrer('usuarios', array('fbid' => $_POST['fbid'])) == "y") {
                $updateUser = array(
                    'nombre' => $_POST['nombre'], 'apellido' => $_POST['apellido'],
                    'completo' => $_POST['nombre'] . " " . $_POST['apellido'],
                    'mail' => $_POST['mail'], 'ultima_app' => "carga-rapida",
                    'ciudad' => $_POST['ciudad'], 'cedula' => $_POST['cedula'],
                    'telefono' => $_POST['telefono']
                );
                $this->db->update('usuarios', $updateUser, array('fbid' => $_POST['fbid']));
                $participante = $this->usuario_samsung->getUserFbid($_POST['fbid']);
                $id = $participante->id;
                $this->db->insert("bateria_registro", array(
                    "id_user" => $id,
                    "fbid" => $_POST['fbid']));
                $resp = "1";
            } else {
                $insertUser = array(
                    'nombre' => $_POST['nombre'], 'apellido' => $_POST['apellido'],
                    'completo' => $_POST['nombre'] . " " . $_POST['apellido'],
                    'mail' => $_POST['mail'], 'ultima_app' => "carga-rapida",
                    'ciudad' => $_POST['ciudad'], 'cedula' => $_POST['cedula'],
                    'telefono' => $_POST['telefono'], 'fbid' => $_POST['fbid']
                );
                $this->db->insert('usuarios', $insertUser);
                $id = $this->db->insert_id();
                $this->db->insert("bateria_registro", array(
                    "id_user" => $id,
                    "fbid" => $_POST['fbid']));
                $resp = "1";
            }
            echo $resp;
        }
    }

    /**************************************************************/

    function ingresoActividad($sw = "0")
    {
        $this->data["dispositivo"] = $sw;
        $this->load->view($this->folderView . '/actividad');
    }


    function sumarCompartida($id)
    {
        $participante = $this->modelo->buscarUserFbid($id);
        $compartidos = (int)$participante->compartidos;
        $compartidos = $compartidos + 1;
        $this->db->where("id", $participante->id);
        $this->db->update("bateria_registro", array("compartidos" => $compartidos));
    }

    function sumarMensaje($id)
    {
        $participante = $this->modelo->buscarUserFbid($id);
        $compartidos = (int)$participante->mensaje;
        $compartidos = $compartidos + 1;
        $this->db->where("fbid", $id);
        $this->db->update("bateria_registro", array("mensaje" => $compartidos));
    }

    function agradecimiento()
    {
        $this->load->view($this->$folderView . '/agradecimiento');
    }


}




























