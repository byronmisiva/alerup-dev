<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Festival_el_sol extends CI_Controller {	
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$data = array();
		$this->load->view( 'festival_el_sol/festival', $data );		
	}
	
 
}

 