<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Samsung_karaoke_galaxya extends CI_Controller
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
        $this->load->model('mdl_samsung_karaoke_galaxya', 'modelo');
        $this->load->helper('form');
        $this->folderView = "samsung_karaoke_galaxya";
        $this->data['controlador'] = "samsung_karaoke_galaxya";


        $this->data['idApp'] = "1028780807152819";
        $this->data['secretApp'] = "c6c9e1eadedcedad6a4e8bca4827253a";
        $this->data['condiciones'] = "<a href='" . base_url() . "archivos/REGLAMENTO-DE-TERMINOS-Y-CONDICIONES-PARA-EL-CONCURSO-Karaoke.pdf' target='_blank' >T�rminos y condiciones</a>";
    }

    function index()
    {
        $this->load->library('user_agent');
        $mobiles = array('Apple iPhone', 'Apple iPod Touch', 'Android', 'Apple iPad');
        if ($this->agent->is_mobile())
            $this->movil();
        else {
            $this->data['dispositivo'] = "normal";
            $this->load->view($this->folderView . '/index', array('data' => $this->data));
        }
    }

    function vervideo()
    {
        $this->data['vervideo'] = $this->uri->segment(3);

        // recuepramos el nombre del usuario
        $this->db->select('nombre');
        $this->db->where('id', $this->data['vervideo']);
        $this->db->from("karaoke_galaxya");
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0) {
            $nombrelistado = $consulta->result();
            $this->data['nombrevideo'] = $nombrelistado[0]->nombre;
        }

        $this->load->library('user_agent');
        $mobiles = array('Apple iPhone', 'Apple iPod Touch', 'Android', 'Apple iPad');
        if ($this->agent->is_mobile())
            $this->movil();
        else {
            $this->data['dispositivo'] = "normal";
            $this->load->view($this->folderView . '/index', array('data' => $this->data));
        }
    }

    //listado Videos
    function listadojson()
    {
        $this->db->select('id, filename, filenameimage, nombre');
        $this->db->where('aprobado', '1');

        $this->db->from("karaoke_galaxya");
        $this->db->order_by("creado", "desc");
        //en caso que se defina filtro
        if (isset($_POST['filtro']))
            $this->db->like('nombre', $_POST['filtro']);

        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0) {
            $data['videos'] = $consulta->result();
            echo json_encode($data['videos']);
        } else
            return FALSE;

    }

    function uploadvideo()
    {
        $fechaHora = date('mdhis');
        $error = "";
        //$target_dir = base_url() . "videos/";
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/videos/";
        //$target_dir = $_SERVER['DOCUMENT_ROOT'] . "/appss/videos/";
        $target_file = $target_dir . $fechaHora . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
        /* if (isset($_POST["submit"])) {
             $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
             if ($check !== false) {
                 echo "" . $check["mime"] . ".";
                 $uploadOk = 1;
             } else {
                 echo "File is not an image.";
                 $uploadOk = 0;
             }
         }*/
// Check if file already exists
        if (file_exists($target_file)) {
            $error .= "Sorry, file already exists;";
            $uploadOk = 0;
        }
// Check file size
        // tamaño maximo 5 megas
        if ($_FILES["fileToUpload"]["size"] > 20000000) {
            $error .= "Archivo muy pesado; ";
            $uploadOk = 0;
        }
// Allow certain file formats
        if ($imageFileType != "mp4" && $imageFileType != "mov" && $imageFileType != "mpg"
            && $imageFileType != "MP4" && $imageFileType != "MOV" && $imageFileType != "MPG"
        ) {
            $error .= "Solamente archivos .mp4, mpg, ,mov.; ";
            $uploadOk = 0;
        }
// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $error .= "Archivo no subido; ";
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo $fechaHora . basename($_FILES["fileToUpload"]["name"]);
            } else {
                echo "false:" . $error;
            }
        }
    }

    function uploadimagen()
    {
        if (array_key_exists('imageData', $_POST)) {
            $imgData = base64_decode($_REQUEST['imageData']);
            $nombreArchivoSubido = $_REQUEST['nombreArchivoSubido'];

            $nombreOriginal = $nombreArchivoSubido;

            $nombreArchivoSubido = str_replace(".mp4", ".png", $nombreArchivoSubido);
            $nombreArchivoSubido = str_replace(".mpg", ".png", $nombreArchivoSubido);
            $nombreArchivoSubido = str_replace(".mov", ".png", $nombreArchivoSubido);
            $nombreArchivoSubido = str_replace(".MOV", ".png", $nombreArchivoSubido);
            $nombreArchivoSubido = str_replace(".MP4", ".png", $nombreArchivoSubido);
            $nombreArchivoSubido = str_replace(".MPG", ".png", $nombreArchivoSubido);

            $nombreArchivoSubido = str_replace(".3gp", ".png", $nombreArchivoSubido);
            $nombreArchivoSubido = str_replace(".3GP", ".png", $nombreArchivoSubido);


            // Path where the image is going to be saved
            // $filePath =  $_SERVER['DOCUMENT_ROOT'] . '/appss/videos/' . $nombreArchivoSubido;
            $filePath = $_SERVER['DOCUMENT_ROOT'] . '/videos/' . $nombreArchivoSubido;

            // Delete previously uploaded image
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Write $imgData into the image file
            $file = fopen($filePath, 'w');
            fwrite($file, $imgData);
            fclose($file);
            echo '{"video":"' . $nombreOriginal . '", "imagen":"' . $nombreArchivoSubido . '"}';
            sleep(2);
            $imageObject = imagecreatefrompng($filePath);
            imagegif($imageObject, str_replace(".png", ".gif", $filePath));

            //validar que la imagen se genero correctamente


            $numeroazar = rand(1, 3);
            if ($this->esImagen($filePath)) {
                if (filesize(str_replace(".png", ".gif", $filePath))< 500)
                {
                    $fichero = $_SERVER['DOCUMENT_ROOT'] . '/videos/galeria_'.$numeroazar.'.gif'  ;
                    $nuevo_fichero = str_replace(".png", ".gif", $filePath);
                    copy($fichero, $nuevo_fichero);

                    $fichero = $_SERVER['DOCUMENT_ROOT'] . '/videos/galeria_'.$numeroazar.'.png'  ;
                    $nuevo_fichero = $filePath;
                    copy($fichero, $nuevo_fichero);
                }
                //echo "es imagen ";
            } else {
                $fichero = $_SERVER['DOCUMENT_ROOT'] . '/videos/galeria_'.$numeroazar.'.gif'  ;
                $nuevo_fichero = str_replace(".png", ".gif", $filePath);
                copy($fichero, $nuevo_fichero);

                $fichero = $_SERVER['DOCUMENT_ROOT'] . '/videos/galeria_'.$numeroazar.'.png'  ;
                $nuevo_fichero = $filePath;
                copy($fichero, $nuevo_fichero);
            }
        }
    }

    function esImagen($path)
    {
        $imageSizeArray = getimagesize($path);
        $imageTypeArray = $imageSizeArray[2];
        return (bool)(in_array($imageTypeArray, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP)));
    }

//samsung_karaoke_galaxya/grabavideo
    function grabavideo()
    {
        $nombrevideo = $_POST['filename'];

        if (isset($nombrevideo)) {

            $this->db->select('id');
            $this->db->where('fbid', $_POST['fbid']);
            $this->db->from("usuarios");
            $id_user = "000";
            $consulta = $this->db->get();
            if ($consulta->num_rows() > 0) {
                $nombrelistado = $consulta->result();
                $id_user = $nombrelistado[0]->id;
            }


            $this->db->insert("karaoke_galaxya", array(
                "filename" => $nombrevideo,
                "filenameimage" => $_POST['filenameimage'],
                "id_user" => $id_user,
                "fbid" => $_POST['fbid'],
                "nombre" => $_POST['nombre']));
        }
    }

    function video()
    {
        $data['video'] = $this->uri->segment(3);
        $data['id'] = $this->uri->segment(4);

        $this->db->select('nombre');
        $this->db->where('id', $data['id']);
        $this->db->from("karaoke_galaxya");
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0) {
            $nombrelistado = $consulta->result();
            $data['nombrevideo'] = $nombrelistado[0]->nombre;
        }

        $this->load->view($this->folderView . '/video', $data);
    }

    function votar()
    {
        $id = $_POST['id'];
        $fbid = $_POST['fbid'];
        // 1 validar si el usuario ya voto
        $this->db->select('*');
        $this->db->where('id_video', $id);
        $this->db->where('fbid', $fbid);

        $this->db->from("karaoke_galaxya_votos");
        $consulta = $this->db->get();
        $resultado = $consulta->result();
        if ($consulta->num_rows() > 0) {
            echo "Solo puede realizar un voto por video";
        } else {
            $this->db->where('id', $id);
            $this->db->set('votos', 'votos+1', FALSE);
            $this->db->update('karaoke_galaxya');
            $test = $this->db->last_query();

            //insertamos registro en votos

            $data = array(
                'fbid' => $fbid,
                'id_video' => $id
            );
            $this->db->insert('karaoke_galaxya_votos', $data);
            echo "Voto realizado con éxito";

        }
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
        if ($this->uri->segment(3) != false)
            $data['vervideo'] = $this->uri->segment(3);
        $this->data['dispositivo'] = "movil";
        $this->load->view($this->folderView . '/movil', array('data' => $this->data));
    }

    function getVideo()
    {
        $participante = $_POST['participante'];
        $origen = "/var/www/vhosts/misiva.com.ec/subdominios/appss/galaxy-a/generados/";
        $destino = "/var/www/vhosts/misiva.com.ec/subdominios/appss/galaxy-a/videos/";
        $numero = rand(0, 99999);
        exec("ffmpeg -r 1/5 -i " . $origen . $participante . "%d.jpg -r 30 " . $destino . $participante . "-" . $numero . "-" . date("h-m") . ".mp4");
        echo $participante . "-" . $numero . "-" . date("h-m") . ".mp4";
    }

    function getCountGaleria()
    {
        $registro = $this->modelo->buscarUserFbid($_POST["participante"]);
        $this->db->where('id_user', $registro->id_user);
        $this->db->from('galeria_galaxy');
        echo $this->db->count_all_results();
    }

    function verificarParticipante()
    {
        $id = $_POST["idParticipante"];
        $participante = $this->usuario_samsung->getUserFbid($id);
        if ($participante == "0") {
            echo "F";
        } else {
            echo json_encode($participante);
        }
    }

    function savePuntage()
    {
        if (isset($_POST['puntos'])) {
            $this->db->where("fbid", $_POST['participante']);
            $this->db->update("karaoke_galaxya_registro", array("puntaje" => $_POST['puntos']));

            $this->db->select('edad');
            $this->db->where("fbid", $_POST['participante']);
            $this->db->from("karaoke_galaxya_registro");
            $consulta = $this->db->get();
            $registro = current($consulta->result());
            echo $registro->edad;

        }
    }

    function register()
    {
        $resp = "0";
        if (isset($_POST['nombre'])) {
            if ($this->usuario_samsung->alreadyRegistrer('usuarios', array('fbid' => $_POST['fbid'])) == "y") {
                $updateUser = array(
                    'nombre' => $_POST['nombre'], 'apellido' => $_POST['apellido'],
                    'completo' => $_POST['nombre'] . " " . $_POST['apellido'],
                    'mail' => $_POST['mail'], 'ultima_app' => "galaxy-a",
                    'ciudad' => $_POST['ciudad'], 'cedula' => $_POST['cedula'],
                    'telefono' => $_POST['telefono']
                );
                $this->db->update('usuarios', $updateUser, array('fbid' => $_POST['fbid']));
                $resp = "1";
            } else {
                $insertUser = array(
                    'nombre' => $_POST['nombre'], 'apellido' => $_POST['apellido'],
                    'completo' => $_POST['nombre'] . " " . $_POST['apellido'],
                    'mail' => $_POST['mail'], 'ultima_app' => "karaoke-galaxy-a",
                    'ciudad' => $_POST['ciudad'], 'cedula' => $_POST['cedula'],
                    'telefono' => $_POST['telefono'], 'fbid' => $_POST['fbid']
                );
                $this->db->insert('usuarios', $insertUser);
                $this->db->insert_id();
                $resp = "1";
            }
            $this->load->library('user_agent');
            $mobiles = array('Apple iPhone', 'Apple iPod Touch', 'Android', 'Apple iPad');
            if ($this->agent->is_mobile())
                $this->index();

            echo $resp;
        }


    }

    /**************************************************************/

    function ingresoActividad($sw = "0")
    {
        $this->data["dispositivo"] = $sw;
        $this->load->view($this->folderView . '/actividad');
    }

    function test1()
    {
        $data["hojas"] = array("impresora", "tablet", "impresora", "pantalla", "microondas", "curved tv", "lavadora", "audifonos");
        $this->load->view($this->folderView . '/test1', $data);

    }


    function cargaEfectos()
    {
        $this->load->view($this->folderView . '/efectos');
    }

    function saveGenerado()
    {
        $participante = $_POST["participante"];
        $registro = $this->modelo->buscarUserFbid($_POST["participante"]);
        exec("chmod 777 /var/www/vhosts/misiva.com.ec/subdominios/appss/galaxy-a/generados/" . $_POST["imagenselfie"]);
        $this->modelo->guardarGaleria($registro->id_user, $_POST["imagenselfie"]);
    }


    function ingresoManifiesto()
    {
        if (isset($_POST["texto"])) {
            $registro = $this->modelo->buscarUserFbid($_POST["participante"]);
            $insertarMensaje = array("mensaje" => $_POST["texto"],
                "fbid" => $_POST["participante"],
                "id_user" => $registro->id);
            $this->db->insert("manifiesto_galaxy", $insertarMensaje);
        }
    }

    function obtenerManifiesto()
    {
        $this->data["manifiesto"] = $this->modelo->getManifiesto();
        $this->load->view($this->folderView . '/js-manifiesto', array('data' => $this->data));
    }

    function obtenergaleria($fbid)
    {
        $registro = $this->modelo->buscarUserFbid($fbid);
        $data["galeria"] = $this->modelo->getGaleria($registro->id_user);
        $this->load->view($this->folderView . '/js-galeria', $data);
    }

    function sumarCompartida($id)
    {
        $participante = $this->modelo->buscarUserFbid($id);
        $compartidos = (int)$participante->compartidos;
        $compartidos = $compartidos + 1;
        $this->db->where("id", $participante->id);
        $this->db->update("karaoke_galaxya_registro", array("compartidos" => $compartidos));
    }

    function sumarCompartidaPosteo($id)
    {
        $participante = $this->modelo->buscarUserFbid($id);
        $compartidos = (int)$participante->posteos;
        $compartidos = $compartidos + 1;
        $this->db->where("fbid", $id);
        $this->db->update("karaoke_galaxya_registro", array("posteos" => $compartidos));
    }

    function registrarInvitados($id)
    {
        $arreglo = json_decode($_POST['data']);
        $total = count($arreglo);
        $dato = $this->modelo->obtenerCampartidos($id);
        $data['user'] = $this->modelo->obtenerRegistro($id);

        $val_nuevo = $total + (int)$dato->compartido;
        $data_update = array(
            "compartido" => (string)$val_nuevo);
        $this->db->where(array('id_user' => $id));
        $this->db->update('halloween', $data_update);
        if ($val_nuevo % 5 == 0 || $total > 5)
            echo "C-0";
        else
            echo "I-" . ($val_nuevo % 5);
    }

    function agradecimiento()
    {
        $this->load->view($this->folderView . '/agradecimiento');
    }


}




























