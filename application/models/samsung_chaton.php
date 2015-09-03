<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Samsung_chaton extends Facebook_Controller
{

    public $data;
    public $config_file = 'fb_config_ruleta';
    public $_rules = array(
        'nombre' => array('field' => 'nombre', 'label' => 'Nombre', 'rules' => 'trim|required|xss_clean'),
        'ciudad' => array('field' => 'ciudad', 'label' => 'Ciudad', 'rules' => 'trim|required|xss_clean'),
        'mail' => array('field' => 'mail', 'label' => 'Email', 'rules' => 'trim|required|valid_email|xss_clean'),
        'telefono' => array('field' => 'telefono', 'label' => 'Teléfono', 'rules' => 'trim|required|xss_clean|numeric'),
        'cedula' => array('field' => 'cedula', 'label' => 'Cédula', 'rules' => 'trim|required|xss_clean|numeric'));

    public function __construct()
    {
        parent::__construct();
        $this->load->model('samsung_usuario', 'usuario_samsung');
        $this->load->model('mdl_samsung_ruleta', 'modelo');
        $this->load->helper('form');
        $this->config->load($this->config_file);
        $this->data['appId'] = $this->config->item('facebook_api_id');  // Api Id proporcionado por Facebook
        $this->data['facebook_page'] = $this->config->item('facebook_page');  // Api Id proporcionado por Facebook
        $this->data['signedRequest'] = (isset($_REQUEST['signed_request'])) ? $_REQUEST['signed_request'] : ''; //Signed Request en el caso de estar en una Fan Page
        $this->data['base'] = base_url();
        $this->data['controler'] = strtolower(get_class($this)); // Nombre del controlador ( El ombre de la clase de este mismo archivo)
        $this->data['namespace'] = $this->config->item('facebook_namespace'); // Namespace proporcionado por Facebook
        $this->data['permission'] = $this->config->item('facebook_permissions'); // String con los permisos para acceder a la informacion del usuario
        $this->data['debug'] = json_encode(array('develop' => false, 'like' => true)); // Array para configurar el modo Debug y simular "LIKE" y "NO LIKE" del usuario
        $this->data['tabLiker'] = 'liker'; // nombre del metodo que crga la vista de "LIKER"
        $this->data['tabNoLiker'] = 'noLiker'; // nombre del metodo que crga la vista de "NO LIKER"
        $this->data['doesNtCare'] = false; // parametro de configuracion en caso de q la Tab no requiera q el usuario sea o no Fan de la Fan page
        $this->data['content'] = 'content'; // id del div que se actualiza con las vistas de "LIKER", "NO LIKER" , etc
        $this->data['isPageTab'] = true; // parametro de configuracion para setear q la App es una Tab dentro de una Fan Page
        $this->data['data'] = ($this->uri->segment(3) != "") ? $this->uri->segment(3) : "undefined";
        $this->data['fondo'] = base_url() . strtolower(get_class($this)) . "/01_fondo.jpg";
        $this->data['img_path'] = base_url() . "imagenes/samsung_ruleta/";
        $this->data['app_name'] = strtolower(get_class($this));
        $this->data['folder'] = strtolower(get_class($this));
        $this->data['condiciones'] = "<a href='" . base_url() . "archivos/reglamento-de-terminos-y-condiciones-samsung-ruleta-2014.pdf' target='_blank' >Términos y Condiciones:</a>";
    }


    function index()
    {
        $this->load->view('samsung_ruleta/index', $this->data);
    }

    function noLiker()
    {
        $this->load->view('samsung_ruleta/no_liker', array('data' => $this->data));
    }

    function liker()
    {
        $this->data['user'] = json_decode($_POST['user']);
        $this->data['fb_page'] = json_decode($_POST['fb_page']);
        if ($this->usuario_samsung->alreadyRegistrer('usuarios', array('fbid' => $this->data['user']->id))) {
            $user = $this->usuario_samsung->getUserFbid($this->data['user']->id);
            //$dato=$this->modelo->verificarUser($user->id);
            //if($dato==FALSE)
            $this->ingresoActividad("0");
            //else
            //$this->ingresoActividad("1");
        } else {
            $this->ingresoActividad();
        }
    }

    function register()
    {
        if (isset($_POST['nombre'])) {
            $this->data['user'] = json_decode($_POST['user']);
            $this->data['fb_page'] = json_decode($_POST['fb_page']);
            if ($this->usuario_samsung->alreadyRegistrer('usuarios', array('fbid' => $this->data['user']->id))) {
                $updateUser = array(
                    'nombre' => $_POST['nombre'],
                    //'apellido' => $_POST['apellido'],
                    'completo' => $_POST['nombre'] . " " . $_POST['apellido'],
                    'mail' => $_POST['mail'],
                    'ultima_app' => $this->data['app_name'],
                    'ciudad' => $_POST['ciudad'],
                    'cedula' => $_POST['cedula'],
                    'telefono' => $_POST['telefono']
                );
                $this->db->update('usuarios', $updateUser, array('fbid' => $this->data['user']->id));
                $participante = $this->usuario_samsung->getUserFbid($this->data['user']->id);
                $id = $participante->id;

                $premio = $_POST['dispSeleccionado'];
                $premio = trim($premio, " \n");

                //se actualiza el inventario
                $query = $this->db->query("update samsung_ruleta_premios set stock=stock -1  where id='$premio'");

                $query = $this->modelo->buscarNombrePremio($premio);
                $premioNombre = $query->id . " - " . $query->dispositivo . "," . $query->premio;
                $this->db->query("update samsung_ruleta  set id_partido='1',compartidos='3', resultado='$premioNombre' where id_user='$id'");
                //  $this->db->query("update samsung_ruleta  set id_partido='1', resultado='$premioNombre' where id_user='$id'");

                $resp = "1";
            } else {
                $insertUser = array(
                    'nombre' => $_POST['nombre'],
                    //'apellido' => $_POST['apellido'],
                    'completo' => $_POST['nombre'] . " " . $_POST['apellido'],
                    'mail' => $_POST['mail'],
                    'ultima_app' => $this->data['app_name'],
                    'ciudad' => $_POST['ciudad'],
                    'cedula' => $_POST['cedula'],
                    'telefono' => $_POST['telefono'],
                    'fbid' => $this->data['user']->id
                    //'genero' => ( isset($this->data['user']->gender) ) ? $this->data['user']->gender : 'ND'
                );
                $this->db->insert('usuarios', $insertUser);
                $id = $this->db->insert_id();
                //  $this->db->insert("samsung_ruleta", array("id_user" => $id));
                $this->db->query("update samsung_ruleta  set id_partido='1',   where id_user='$id'");

                /////
                $premio = $_POST['dispSeleccionado'];
                $premio = trim($premio, " \n");

                //se actualiza el inventario
                $query = $this->db->query("update samsung_ruleta_premios set stock=stock -1  where id='$premio'");

                $query = $this->modelo->buscarNombrePremio($premio) . ",";
                $premioNombre = $query->id . " - " . $query->dispositivo . "," . $query->premio;
                $this->db->query("update samsung_ruleta  set id_partido='1',compartidos='3', resultado='$premioNombre' where id_user='$id'");

                ///7

                $resp = "1";
            }
            echo $resp;
        } else {
            $this->data['errors'] = array(
                'nombre' => form_error('nombre') || FALSE,
                'ciudad' => form_error('ciudad') || FALSE,
                'mail' => form_error('mail') || FALSE,
                'telefono' => form_error('telefono') || FALSE,
                'cedula' => form_error('cedula') || FALSE
            );

            $this->data['fb_page'] = json_decode($_POST['fb_page']);
            $this->data['user'] = json_decode($_POST['user']);
            $user = $this->usuario_samsung->getUserFbid($this->data['user']->id);
            $this->data['user']->telefono = (isset($user->telefono)) ? $user->telefono : '';
            $this->data['user']->cedula = (isset($user->cedula)) ? $user->cedula : '';
            $this->data['user']->dispSeleccionado = $_POST['dispSeleccionado'];

            $this->load->view('samsung_ruleta/register', array('data' => $this->data));
        }
    }

    /**************************************************************/

    function ingresoActividad($sw = "0")
    {
        $this->data['user'] = json_decode($_POST['user']);
        $this->data["participante"] = $this->usuario_samsung->getUserFbid($this->data["user"]->id);
        $this->data["participanteRegistrado"] = $sw;
        //  $this->data["participanteGana"]="pierde";
        $this->data["participanteGana"] = $this->calculaGanador($this->data['participante']->id);
        $this->data["cuentacompartidos"] = $this->modelo->cuentacompartidos($this->data['participante']->id);
        $this->data['amigos'] = "3";

        $this->load->view('samsung_ruleta/actividad', array('data' => $this->data));
    }


    function verificarParticipante($id)
    {
        $registro = $this->modelo->buscarUser($id);
        if ($registro == FALSE)
            //echo "F";
            echo "0";

        else
            echo "0";
    }

    function calculaGanador($iduser)
    {
        if (!$this->modelo->verificarUserOportinidad($iduser)) {
            return "fin";
        }
        //VERIFICAMOS QUE EL USUARIO NO HAYA GANADO ANTERIORMENTE
        if ($this->modelo->buscarYaGano($iduser))
            return "pierde";

        //VERIFICAMOS QUE NO SE HAYA SORTEADO LA ULTIMA MEDIA HORA
        if ($this->modelo->buscarSorteoMediaHora())
            return "pierde";
        // se deben repartir 2  premios por hora
        $randomNumero =  rand(1, 2);
        if ($randomNumero == 1) {
            return "gana";
        } else {
            return "pierde";

        }
    }

    function actualizarPuntaje($idUser, $totalPuntaje)
    {
        $this->modelo->actualizarPuntaje($idUser, $totalPuntaje);
    }

    function getDataProducto()
    {
        $tipo = $_POST ['tipo'];
        $this->data["premios"] = $this->modelo->listadoinventario($tipo);
        $this->load->view('samsung_ruleta/listadoinventario', array('data' => $this->data));
    }

    function registrarInvitados()
    {
        $idParticipante = $_POST ['idParticipante'];
        $query = $this->db->query("update samsung_ruleta set compartidos=compartidos + 1  where id_user='$idParticipante'");

        if ($this->modelo->verificarUserOportinidad($idParticipante)) {
            $calcula = $this->calculaGanador($idParticipante);
            $cuenta = $this->modelo->cuentacompartidos($idParticipante);
            echo '{"tipoganador": "' . $calcula . '","cuentacompartidos": ' . $cuenta . '}';
        } else {
            $cuenta = $this->modelo->cuentacompartidos($idParticipante);
            echo '{"tipoganador": "fin","cuentacompartidos": ' . $cuenta . '}';
        }

    }

    function registrarInvitados2()
    {
        $idParticipante = $_POST ['idParticipante'];


        if ($this->modelo->verificarUserOportinidad($idParticipante)) {
            echo $this->calculaGanador($idParticipante);
        } else {
            echo "fin";
        }

    }

}