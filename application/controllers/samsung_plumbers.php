<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Samsung_plumbers extends CI_Controller {	
	
	var $folder;		
	var $condiciones;	
	var $img_path;	
	var $app_name;	
	var $alfabeto;
	var $orientacion;
	var $words;
	var $imageWords;
	
	public function __construct(){
		parent::__construct();
		$this->config->load('fb_config_plumbers');
		$this->load->model( 'samsung_usuario','usuario' );
		$this->load->helper( array( 'url' ) );	
		$this->folder = 'samsung_plumbers';
		$this->app_name = 'samsung_plumbres'; // Nombre de la aplicacion		
		$this->img_path = base_url().'imagenes/'.$this->folder.'/'; // Path para direccionar las imagenes
		$this->condiciones="Esta promoci&oacute;n no guarda ning&uacute;n tipo de relaci&oacute;n directa o indirecta con Facebook, Compa&ntilde;ias Afiliadas, Filiales de la misma o Subsidiarias. 
		De la misma manera, Facebook no patrocina, endosa o administra directa o indirectamente esta promoci&oacute;n. Todas las preguntas concernientes a la misma deber&aacute;n 
		ser remitidas directamente a Samsung Electronics Ecuador y NO a Facebook. La informaci&oacute;n recolectada a trav&eacute;s de esta promoci&oacute;n se realiza de manera 
		independiente a Facebook con fines exclusivos de identificaci&oacute;n, al entregarla, el usuario acepta los t&eacute;rminos y condiciones establecidos para la misma. 
		Todas las marcas registradas son propiedad de sus respectivos due&ntilde;os. <a href='".base_url()."archivos/REGLAMENTO-DE-TERMINOS-Y-CONDICIONES-PARA-EL-CONCURSO-SOPA-LETRAS.pdf' target='_blank' >aqu&iacute;</a";
		$this->alfabeto = array( 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V','W', 'X', 'Y', 'Z' );
		$this->orientacion =  array( 'vertical_down', 'vertical_up', 'horizontal_left', 'horizotal_right', 'inclined_left_down', 'inclined_left_up', 'inclined_right_down' );
		//$this->orientacion =  array( 'horizontal_left' );
		$this->words = array( "GOLAZO", "MODOFUTBOL", "SMARTTV", "BLUEFRIDAY", "JEFFMONTERO", "SAMSUNG");
		/*$this->img_path."elegante.png", $this->img_path."dualcamera.png", $this->img_path."dramashot.png", 
		$this->img_path."grouplay.png", $this->img_path."chatON.png", $this->img_path."airview.png", $this->img_path."airgesture.png", $this->img_path."smartpause.png" );*/
		$this->imageWords = array( $this->img_path."golazo.png", $this->img_path."modofutbol.png", 
								   $this->img_path."smarttv.png", $this->img_path."bluefriday.png", 
								   $this->img_path."jeffmontero.png",$this->img_path."samsung.png");
	}
	
	//Este es el metodo que se configura en los settings de la Tab (Pestaña de la página) y carga la libreria de Facebook y envia los parametros de configuracion
	public function index(){
		$data['appId'] = $this->config->item('facebook_api_id');  // Api Id proporcionado por Facebook
		$data['signedRequest'] = ( isset($_REQUEST['signed_request'] ) ) ? $_REQUEST['signed_request'] : '' ; //Signed Request en el caso de estar en una Fan Page
		$data['base'] = base_url();
		$data['controler'] = 'samsung_plumbers'; // Nombre del controlador ( El ombre de la clase de este mismo archivo)
		$data['namespace'] = $this->config->item('namespacepruebas'); // Namespace proporcionado por Facebook
		$data['permission'] = $this->config->item('facebook_permissions'); // String con los permisos para acceder a la informacion del usuario
		$data['debug'] = json_encode( array( 'develop' => false, 'like' => TRUE ) ); // Array para configurar el modo Debug y simular "LIKE" y "NO LIKE" del usuario 
		$data['tabLiker'] =  'liker'; // nombre del metodo que crga la vista de "LIKER"
		$data['tabNoLiker'] = 'noLiker'; // nombre del metodo que crga la vista de "NO LIKER"
		$data['doesNtCare'] = false; // parametro de configuracion en caso de q la Tab no requiera q el usuario sea o no Fan de la Fan page
		$data['content'] = 'content'; // id del div que se actualiza con las vistas de "LIKER", "NO LIKER" , etc
		$data['isPageTab'] = true; // parametro de configuracion para setear q la App es una Tab dentro de una Fan Page
		$data['fondo'] = $this->img_path."background.png?frefresh=1354654979988";
		$data['img_path'] = $this->img_path;
		$data['condiciones'] = $this->condiciones;
		$this->load->view( $this->folder.'/index', $data );
	}
	//Metodo que carga la vista cuando el usuario es "NO LIKER" de la "FAN PAGE" y recibe los datos de la Fan Page por POST
	function noLiker(){
		$data['api_id'] = $this->config->item('facebook_api_id');
		$data['fb_page'] = json_decode( $_POST['fb_page'] );
		$data['img_path'] = $this->img_path;
		$data['no_fan'] = $this->img_path.'nofan.png?fbrefresh=1234567896545564656465564';			
		$this->load->view( $this->folder.'/tab_noliker', $data );
	}
	//Metodo que carga la vista cuando el usuario es "LIKER" de la "FAN PAGE" y recibe los datos del usuario y fan page por POST
	function liker(){
		$this->load->view( $this->folder.'/fin',  $data );
		
		/*
		$data['api_id'] = $this->config->item('facebook_api_id');
		$data['img_path'] = $this->img_path;
		$data['user'] = json_decode( $_POST['user'] );		
		$data['fb_page'] = json_decode( $_POST['fb_page'] );
				
		$data['fondo'] = $this->img_path."background.jpg";
		$array_insert = array(
				'fbid' => $data['user']->id,
				'nombre' => $data['user']->first_name,
				'apellido' => $data['user']->last_name,
				'completo' => $data['user']->name,
				'mail' => $data['user']->email,
				'genero' => $data['user']->gender,
				'ultima_app' => $this->app_name);
		$this->usuario->newRegister( $array_insert );
		
		
		$this->db->select( 'count(*) as numero' );
		$this->db->where( array( 'fbid_user' => $data['user']->id ) );
		$invitados = current( $this->db->get( 'sopa_de_letras_invitaciones' )->result() )->numero;
		
		
				
		if( $this->usuario->alreadyRegistrer2( 'usuarios', array( 'fbid' => $data['user']->id, 'cedula !=' => '', 'telefono !=' => '', 'ciudad !=' => '' ) )=="n" ){			
			$this->vista_registro( $data['user'], $data['fb_page'] );
		}		
		elseif ( $invitados  < 5 && $this->usuario->alreadyRegistrer2( 'sopa_de_letras', array( 'fbid' => $data['user']->id ) )=="y" ){						
			$this->game_finish( $data['user'], $data['fb_page'] );			
		}		
		else{
			$user = $this->usuario->getUserFbid( $data['user']->id );
			$game_matrix = array();
			for ( $i = 0; $i < 15; $i++ ){
				$aux = array();
				for ( $j = 0; $j < 15; $j++ ){
					array_push($aux, array(
							'x' => ( $i * 30 ),
							'y' => ( $j * 30 ),
							'width' => 30,
							'height' => 30,
							'color' => "#ffffff",
							'colorLight' => '#2F6EC7',
							'letter' => $this->alfabeto[ array_rand( $this->alfabeto ) ],
							'fill' => FALSE
					) );
				}
				array_push( $game_matrix, $aux);
			}
			$posWord = array_rand( $this->words, 6 );
			$wordsGame = array( $this->words[$posWord[0]], $this->words[$posWord[1]], $this->words[$posWord[2]], $this->words[$posWord[3]], $this->words[$posWord[4]], $this->words[$posWord[5]] );
			$posWord = array_rand( $this->words, 6 );
			$wordsGame = array( $this->words[$posWord[0]], $this->words[$posWord[1]], $this->words[$posWord[2]], $this->words[$posWord[3]], $this->words[$posWord[4]], $this->words[$posWord[5]] );
			$wordsReverseGame = array( strrev( $this->words[$posWord[0]] ), strrev( $this->words[$posWord[1]] ), strrev( $this->words[$posWord[2]] ), strrev( $this->words[$posWord[3]]), strrev( $this->words[$posWord[4]] ), strrev( $this->words[$posWord[5]] ) );
			$wordsImage = array( $this->imageWords[$posWord[0]], $this->imageWords[$posWord[1]], $this->imageWords[$posWord[2]], $this->imageWords[$posWord[3]], $this->imageWords[$posWord[4]], $this->imageWords[$posWord[5]] );
			foreach ( $wordsGame as $word ){
				do{
					$i = rand(0, 14);
					$j = rand(0, 14);
					$orientacion = array_rand( $this->orientacion );
				}
				while( $this->checkDisponible( $i, $j, $orientacion, $word, $game_matrix) );
				switch ( $orientacion ){
					case 0:
						for ( $k = 0; $k < strlen( $word ); $k++ ){
							$game_matrix[$i][$j+$k]['letter'] = $word[$k];
							$game_matrix[$i][$j+$k]['fill'] = TRUE;
							//$game_matrix[$i][$j+$k]['color'] = "red";
						}
						break;
					case 1:
						for ( $k = 0; $k < strlen( $word ); $k++ ){
							$game_matrix[$i][$j-$k]['letter'] = $word[$k];
							$game_matrix[$i][$j-$k]['fill'] = TRUE;
							//$game_matrix[$i][$j-$k]['color'] = "red";
						}
						break;
					case 2:
						for ( $k = 0; $k < strlen( $word ); $k++ ){
							$game_matrix[$i+$k][$j]['letter'] = $word[$k];
							$game_matrix[$i+$k][$j]['fill'] = TRUE;
							//$game_matrix[$i+$k][$j]['color'] = "red";
						}
						break;
					case 3:
						for ( $k = 0; $k < strlen( $word ); $k++ ){
							$game_matrix[$i-$k][$j]['letter'] = $word[$k];
							$game_matrix[$i-$k][$j]['fill'] = TRUE;
							//$game_matrix[$i-$k][$j]['color'] = "red";
						}
						break;
					case 4:
						for ( $k = 0; $k < strlen( $word ); $k++ ){
							$game_matrix[$i+$k][$j+$k]['letter'] = $word[$k];
							$game_matrix[$i+$k][$j+$k]['fill'] = TRUE;
							//$game_matrix[$i+$k][$j+$k]['color'] = "red";
						}
						break;
					case 5:
						for ( $k = 0; $k < strlen( $word ); $k++ ){
							$game_matrix[$i-$k][$j-$k]['letter'] = $word[$k];
							$game_matrix[$i-$k][$j-$k]['fill'] = TRUE;
							//$game_matrix[$i-$k][$j-$k]['color'] = "red";
						}
						break;
					case 6:
						for ( $k = 0; $k < strlen( $word ); $k++ ){
							$game_matrix[$i-$k][$j+$k]['letter'] = $word[$k];
							$game_matrix[$i-$k][$j+$k]['fill'] = TRUE;
							//$game_matrix[$i-$k][$j+$k]['color'] = "red";
						}
						break;
					case 7:
						for ( $k = 0; $k < strlen( $word ); $k++ ){
							$game_matrix[$i-$k][$j-$k]['letter'] = $word[$k];
							$game_matrix[$i-$k][$j-$k]['fill'] = TRUE;
							//$game_matrix[$i-$k][$j-$k]['color'] = "red";
						}
						break;		
				}	
			}
			$data['game'] = json_encode( array( 'words' => $wordsGame, 
												'game_matrix' => $game_matrix, 
												'wordsReverse' => $wordsReverseGame, 
												'imageWords' => $wordsImage, 
												'imgPath' => $this->img_path, 
												'baseUrl' => base_url() ) );
			$this->load->view( $this->folder.'/tab_liker',  $data );
		}*/
	}
	
	function amigos_bloqueados(){
		$user = json_decode( $_POST['user'] );
		$this->db->select('fbid_invitado');
		$this->db->where( array( 'fbid_user' => $user->id ) );		
		$invitados = array();
		$aux = $this->db->get( 'sopa_de_letras_invitaciones' )->result();
		foreach ( $aux as $value ) {
			array_push( $invitados, $value->fbid_invitado );
		}
		echo  json_encode( $invitados );		
	}
	
	function registrarInvitacion(){
		$invitaciones = json_decode( $_POST['data'] );
		$user = json_decode( $_POST['user'] );
		foreach ( $invitaciones as $invitacion ){
			$this->db->insert( 'sopa_de_letras_invitaciones', array( 'fbid_user' => $user->id, 'fbid_invitado' => $invitacion ) );			
		}
		$this->db->select( 'count(*) as numero' );
		$this->db->where( array( 'fbid_user' => $user->id ) );
		echo current($this->db->get( 'sopa_de_letras_invitaciones' )->result())->numero;		
	}
	
	function game_finish( $data_user = FALSE, $data_page =FALSE ){		
		$data['api_id'] = $this->config->item('facebook_api_id');
		$fb_page = ( $data_page != FALSE ) ? $data_page :json_decode( $_POST['fb_page'] );
		$user = ( $data_user != FALSE ) ? $this->usuario->getUserFbid( $data_user->id ) : $this->usuario->getUserFbid( $_POST['fbid'] );
		if( isset( $_POST['score'] ) ){
			$this->db->insert( 'sopa_de_letras', array( 'user_id' => $user->id, 'fbid' => $_POST['fbid'], 'puntaje' => $_POST['score'], 'page_id' => $fb_page->page->id, 'nombre' => $user->completo, 'time' => $_POST['time'], 'dejaver' => $_POST['dejaver'] ) );
		}
		
		$data['img_path'] = $this->img_path;
		$data['ranking'] = $this->img_path.'rankingCORRECTION.png?fbrefresh=98995g965465465';	
		$this->db->select(  'fbid,nombre, max(puntaje) as puntaje' , FALSE );
		$this->db->from('sopa_de_letras');
		$this->db->where('DATE(creado)','CURDATE()',false);
		$this->db->group_by('fbid');
		$this->db->order_by( 'puntaje', 'desc' );
		$this->db->order_by( 'creado', 'desc' );		
		$this->db->limit(5);
		$data['topfive'] =  $this->db->get()->result();
		$this->db->select( 'puntaje as score' );		
		$this->db->where( array( 'fbid' => ( ( $data_user != FALSE ) ? $data_user->id : $_POST['fbid']  ) ) );
		$this->db->order_by( 'creado', 'desc' );
		$this->db->limit(1);
		$data['mi_score'] = current($this->db->get('sopa_de_letras' )->result())->score;
		$this->db->select( 'count(*) as numero' );
		$this->db->where( array( 'fbid_user' => ( ( $data_user != FALSE ) ? $data_user->id : $_POST['fbid']  ) ) );
		$data['numero'] = current($this->db->get( 'sopa_de_letras_invitaciones' )->result())->numero;
		$this->load->view( $this->folder.'/ranking', $data );		
	}
	
	function vista_registro( $user = FALSE, $fbpage = FALSE ){			
		$data['img_path'] = $this->img_path;
		$data['registro'] = $this->img_path.'registrojpg.png?fbrefresh=123456789';
		if ( isset( $_POST['nombre'] ) ){		
			$this->db->update( 'usuarios', $_POST, array( 'fbid' => $_POST['fbid'] ) );
		}
		else{
			$data['user'] = $user;
			$data['fb_page'] = $fbpage;
			$this->load->view( $this->folder.'/registro', $data );
		}	
	}
	
	function checkDisponible( $i, $j, $orientacion, $word, $matrix ){
		switch ( $orientacion ){
			case 0:
				if( ( $j + strlen( $word ) ) > 14 ){
					return TRUE;
				}
				else{
					for( $k = 0; $k < strlen( $word ); $k++ ){
						if( $matrix[$i][$j+$k]['fill'] && $matrix[$i][$j+$k]['letter'] != $word[$k] ){
							return TRUE;
						}
					}
				}
				break;
			case 1:
				if( ( $j - strlen( $word ) ) < 0 ){
					return TRUE;
				}
				else{
					for( $k = 0; $k < strlen( $word ); $k++ ){
						if( $matrix[$i][$j-$k]['fill'] && $matrix[$i][$j-$k]['letter'] != $word[$k] ){
							return TRUE;
						}
					}
				}
				break;
			case 2:
				if( ( $i + strlen( $word ) ) > 14 ){
					return TRUE;
				}
				else{
					for( $k = 0; $k < strlen( $word ); $k++ ){
						if( $matrix[$i+$k][$j]['fill'] && $matrix[$i+$k][$j]['letter'] != $word[$k] ){
							return TRUE;
						}
					}
				}
				break;
			case 3:
				if( ( $i - strlen( $word ) ) < 0 ){
					return TRUE;
				}
				else{
					for( $k = 0; $k < strlen( $word ); $k++ ){
						if( $matrix[$i-$k][$j]['fill'] && $matrix[$i-$k][$j]['letter'] != $word[$k] ){
							return TRUE;
						}
					}
				}
				break;
			case 4:
				if( ( $i + strlen( $word ) ) > 14 || ( $j + strlen( $word ) ) > 14 ){
					return TRUE;
				}
				else{
					for( $k = 0; $k < strlen( $word ); $k++ ){
						if( $matrix[$i+$k][$j+$k]['fill'] && $matrix[$i+$k][$j+$k]['letter'] != $word[$k] ){
							return TRUE;
						}
					}
				}
				break;
			case 5:
				if( ( $i - strlen( $word ) ) < 0 || ( $j - strlen( $word ) ) < 0 ){
					return TRUE;
				}
				else{
					for( $k = 0; $k < strlen( $word ); $k++ ){
						if( $matrix[$i-$k][$j-$k]['fill'] && $matrix[$i-$k][$j-$k]['letter'] != $word[$k] ){
							return TRUE;
						}
					}
				}
				break;
			case 6:
				if( ( $i - strlen( $word ) ) < 0 || ( $j + strlen( $word ) ) > 14 ){
					return TRUE;
				}
				else{
					for( $k = 0; $k < strlen( $word ); $k++ ){
						if( $matrix[$i-$k][$j+$k]['fill'] && $matrix[$i-$k][$j+$k]['letter'] != $word[$k] ){
							return TRUE;
						}
					}
				}
				break;
			case 7:
				if( ( $i - strlen( $word ) ) < 0 || ( $j + strlen( $word ) ) < 0 ){
					return TRUE;
				}
				else{
					for( $k = 0; $k < strlen( $word ); $k++ ){
						if( $matrix[$i-$k][$j+$k]['fill'] && $matrix[$i-$k][$j+$k]['letter'] != $word[$k] ){
							return TRUE;
						}
					}
				}
				break;
		}
		
	}
	//Metodo que redirecciona despues de pedir permisos a la pagina de la Tab
	function  pageTab(){
		$pageName = ( $this->uri->segment(3) != 'undefined') ? $this->uri->segment(3) : $this->config->item('facebook_page');
		$appId = ( $this->uri->segment(4) != '' ) ? $this->uri->segment(4) : $this->config->item('facebook_api_id');
		$namespace = ( $this->uri->segment(5) != '' ) ? $this->uri->segment(5) : $this->config->item('namespacepruebas');
		echo "<script>parent.location.href='https://www.facebook.com/".$pageName."?v=app_".$appId."&app_data=".$namespace."';</script>";
	}
	//Metodo que redirecciona despues de pedir permisos a la pagina de la App
	function  pageApp(){
		$namespace = ( $this->uri->segment(3) != '' ) ? $this->uri->segment(3) : $this->config->item('namespacepruebas');
		echo "<script>parent.location.href='https://apps.facebook.com/".$namespace."';</script>";
	}	
	//Estos metodos son los pproporcionados po facebook para parsear el Siged Request 
	public function getGeneral(){		
		echo json_encode( $this->parse_signed_request( $_POST["signedRequest"], $this->config->item('facebook_secret_key') ) );
	}
	
	function parse_signed_request($signed_request, $secret) {
		list($encoded_sig,$payload)=explode('.',$signed_request,2);
		$sig=$this->base64_url_decode($encoded_sig);
		$data=json_decode($this->base64_url_decode($payload),true);
		if(strtoupper($data['algorithm'])!=='HMAC-SHA256'){
			error_log('Unknown algorithm. Expected HMAC-SHA256');
			return null;
		}
		$expected_sig=hash_hmac('sha256',$payload,$secret,$raw=true);
		if($sig!==$expected_sig) {
			error_log('Bad Signed JSON signature!');
			return null;
		}
		return $data;
	}		

	function base64_url_decode($input){
		return base64_decode(strtr($input,'-_','+/'));
	}	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
