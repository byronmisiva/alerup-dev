<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Samsung_corazon extends CI_Controller {	
	
	var $folder;	
	
	public function __construct(){
		parent::__construct();		
		$this->load->model( 'mdl_usuario_corazon','modelo' );
		$this->load->helper( array( 'url','form' ) );		
		$this->folder="samsung_corazon";
	}

	public function index(){		
		$data['title'] ="Corazon Sano";
		$this->load->view( $this->folder.'/index', $data );
	}
	
	function consultaDatos($pulsos,$id){
		$data=$this->modelo->getParticipante($id);
		$genero=$data->genero;
		$edad=(int)$data->edad;
		$pulsos=(int)$pulsos;
		$respuesta=0;
		 
		
		switch ($genero){
			case "m":
				if( ($edad>=18) && ($edad<=25) ){
					if( ($pulsos>=70) && ($pulsos<=110) ){
						$respuesta=1;
					}
					else if( ($pulsos>=111) && ($pulsos<=130) ){
						$respuesta=2;
					}
					else if( ($pulsos>=131) && ($pulsos<=155) ){
						$respuesta=3;
					}
					else if( $pulsos>155 ){
						$respuesta=4;
					}
				}else if( ($edad>=26) && ($edad<=35) ){
					 if( ($pulsos>=71) && ($pulsos<=108) ){
						$respuesta=1;
					}
					else if(($pulsos>=109) && ($pulsos<=130)){
						$respuesta=2;
					}
					else if(($pulsos>=131) && ($pulsos<=154)){
						$respuesta=3;
					}
					else if($pulsos>155 ){
						$respuesta=4;
					}
					
				}else if( ($edad>=36) && ($edad<=45) ){
					 if( ($pulsos>=71) && ($pulsos<=105) ){
						$respuesta=1;
					}
					else if(($pulsos>=106) && ($pulsos<=125)){
						$respuesta=2;
					}					
					else if(($pulsos>=126) && ($pulsos<=145)){
						echo "aqui";
						$respuesta=3;
					}
					else if($pulsos>146 ){
						$respuesta=4;
					}
					
				}else if( ($edad>=46) && ($edad<=55) ){
					 if( ($pulsos>=72) && ($pulsos<=100) ){
						$respuesta=1;
					}
					else if(($pulsos>=101) && ($pulsos<=123)){
						$respuesta=2;
					}
					else if(($pulsos>=124) && ($pulsos<=140)){
						$respuesta=3;
					}
					else if($pulsos>141 ){
						$respuesta=4;
					}
					
				}else if( ($edad>=56) && ($edad<=65) ){
					 if( ($pulsos>=72) && ($pulsos<=96) ){
						$respuesta=1;
					}
					
					else if(($pulsos>=97) && ($pulsos<=120)){
						$respuesta=2;
					}
					else if(($pulsos>=121) && ($pulsos<=137)){
						$respuesta=3;
					}
					else if($pulsos>137 ){
						$respuesta=4;
					}					
				}else if ($edad>65) {										
					if( ($pulsos>=70) && ($pulsos<=95) ){
						$respuesta=1;
					}else if(($pulsos>=96) && ($pulsos<=118)){
						$respuesta=2;
					}
					else if(($pulsos>=119) && ($pulsos<=134)){
						$respuesta=5;
					}
					else if($pulsos>134 ){
						$respuesta=4;
					}
				}				
				break;
				
			case "f":
				if( ($edad>=18) && ($edad<=25) ){
					 if( ($pulsos>=74) && ($pulsos<=108) ){
						$respuesta=1;
					}
					else if( ($pulsos>=109) && ($pulsos<=128) ){
						$respuesta=2;
					}
					else if( ($pulsos>=129) && ($pulsos<=154) ){
						$respuesta=3;
					}
					else if( $pulsos>=154  ){
						$respuesta=4;
					}
				}else if( ($edad>=26) && ($edad<=35) ){
					 if( ($pulsos>=73) && ($pulsos<=107) ){
						$respuesta=1;
					}
					else if(($pulsos>=108) && ($pulsos<=128)){
						$respuesta=2;
					}
					else if(($pulsos>=129) && ($pulsos<=152)){
						$respuesta=3;
					}
					else if($pulsos>152 ){
						$respuesta=4;
					}
						
				}else if( ($edad>=36) && ($edad<=45) ){
					if( ($pulsos>=74) && ($pulsos<=100) ){
						$respuesta=1;
					}
					else if(($pulsos>=101) && ($pulsos<=120)){
						$respuesta=2;
					}
					else if(($pulsos>=121) && ($pulsos<=140)){						
						$respuesta=4;
					}
					else if($pulsos>140 ){						
						$respuesta=4;
					}
						
				}else if( ($edad>=46) && ($edad<=55) ){
					 if( ($pulsos>=74) && ($pulsos<=100) ){
						$respuesta=1;
					}
					else if(($pulsos>=101) && ($pulsos<=119)){
						$respuesta=2;
					}
					else if(($pulsos>=120) && ($pulsos<=140)){
						$respuesta=3;
					}
					else if($pulsos>141 ){
						$respuesta=4;
					}
						
				}else if( ($edad>=56) && ($edad<=65) ){
					 if( ($pulsos>=72) && ($pulsos<=94) ){
						$respuesta=1;
					}
						
					else if(($pulsos>=95) && ($pulsos<=118)){
						$respuesta=2;
					}
					else if(($pulsos>=119) && ($pulsos<=135)){
						$respuesta=3;
					}
					else if($pulsos>135 ){
						$respuesta=4;
					}
				}else if ($edad>65) {					
					if( ($pulsos>=73) && ($pulsos<=93) ){
						$respuesta=1;
					}else if(($pulsos>=94) && ($pulsos<=115)){
						$respuesta=2;
					}
					else if(($pulsos>=116) && ($pulsos<=132)){
						$respuesta=3;
					}
					else if($pulsos>132 ){
						$respuesta=4;
					}
				}
				break;
		}		
		$genero=$data->genero;
		$edad=(int)$data->edad;
		$pulsos=(int)$pulsos;
		$datos=array(
			"id"=>$id,
			"pulsos"=>$pulsos,
			"tipo"=>$respuesta
	);
	
		$RES=$this->modelo->actualizarParticipante($datos);		
		$dato["pulsos"]=$pulsos;
		$dato["participante"]=$id;
		$dato["resultado"]=$respuesta;
		
		echo $respuesta;
	}
	
	function registro(){
		$id=$this->modelo->insertarParticipante($_POST);
		echo $id;
	}	
	
	function generarReporte(){		
		$dato['registro']=$this->modelo->reporteApp();		
		$this->load->view( $this->folder.'/reportes', $dato );
	}
	
	function crearReporteTotal(){
		$tipo=array("0"=>"Promedio","1"=>"Zen","2"=>"Bronce","3"=>"Oro","4"=>"Hierro");
		$genero=array("m"=>"Masculino","f"=>"Femenino",);
		$registro=$this->modelo->reporteApp();
		header('Content-type: application/vnd.ms-excel;charset=utf-8');
		header("Content-Disposition: attachment; filename=reporte-base-completa.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		$print = "<table>";
		$print .= "<tr>
				<th>Nombre</th><th>Apellido</th>
				<th>Edad</th><th>Cédula</th>
				<th>Ciudad</th><th>Teléfono</th>
				<th>Género</th><th>Tipo</th>
				<th>Pulsos</th>";
		$print .= "</tr>";
		foreach($registro as $row){
			$print .= "<tr>
			<td>".$row->nombre."</td><td>".$row->apellido."</td>
			<td>".$row->edad."</td><td>".$row->cedula."</td>
			<td>".$row->ciudad."</td><td>".$row->telefono."</td>
			<td>".$genero[$row->genero]."</td><td>".$tipo[$row->tipo]."</td>
			<td>".$row->pulsos."</td>";
			$print .= "<tr>";
		}
		$print .= "</table>";
		echo $print;
	}
	
	
	function crearReporteFiltrado(){
		$tipo=array("0"=>"Promedio","1"=>"Zen","2"=>"Bronce","3"=>"Oro","4"=>"Hierro");
		$genero=array("m"=>"Masculino","f"=>"Femenino",);
		$registro=$this->modelo->getReporteFiltrado();
		header('Content-type: application/vnd.ms-excel;charset=utf-8');
		header("Content-Disposition: attachment; filename=reporte-base-para-sorteo.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		$print = "<table>";
		$print .= "<tr>
			<th>Nombre</th><th>Apellido</th>
				<th>Edad</th><th>Cédula</th>
				<th>Ciudad</th><th>Teléfono</th>
				<th>Género</th><th>Tipo</th>
				<th>Pulsos</th>";
		$print .= "</tr>";
		foreach($registro as $row){
			$print .= "<tr>
			<td>".$row->nombre."</td><td>".$row->apellido."</td>
			<td>".$row->edad."</td><td>".$row->cedula."</td>
			<td>".$row->ciudad."</td><td>".$row->telefono."</td>
			<td>".$genero[$row->genero]."</td><td>".$tipo[$row->tipo]."</td>
			<td>".$row->pulsos."</td>";
			$print .= "<tr>";
		}
		$print .= "</table>";
		echo $print;
	
	}
	

}



















/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
