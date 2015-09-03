<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Myfacebook{

	private $CI;
	var	$api_id;
	var $secret_key;
	var $redirect;
	var $fbindex;
	var $permissions;
	
	public function __construct($parametros){
		$this->CI=&get_instance();
		$this->CI->load->config($parametros['file']);
		$this->api_id=$this->CI->config->item('facebook_api_id');
		$this->namespace=$this->CI->config->item('facebook_namespace');
		$this->secret_key=$this->CI->config->item('facebook_secret_key');
		$this->redirect=$this->CI->config->item('facebook_redirect');
		$this->fbindex=$this->CI->config->item('facebook_fbindex');
		if($this->CI->config->item('facebook_permissions'))
			$this->permissions=$this->CI->config->item('facebook_permissions');
		else
			$this->permissions="email,read_stream,publish_stream,user_likes,user_birthday";
	}
	
	public function permission($data='value',$page=0){
		$pagina='?fbpg='.$page;
		$data=$pagina.'&data='.$data;
		//
		echo '<script>parent.location.href="https://www.facebook.com/dialog/oauth?client_id='.$this->api_id.'&redirect_uri='.urlencode(base_url().$this->redirect.$data).'&scope='.$this->permissions.'";</script>';
	
	}
	
	public function getToken($codigo,$data='value',$page=0){
		error_reporting(0);
		$this->CI->session->set_flashdata('data', $data);
		$pagina='?fbpg='.$page;
		$data=$pagina.'&data='.$data;
		
		$token=file_get_contents('https://graph.facebook.com/oauth/access_token?client_id='.$this->api_id.'&redirect_uri='.urlencode(base_url().$this->redirect.$data).'&client_secret='.$this->secret_key.'&code='.$codigo);
		$params = null;
     	parse_str($token, $params);
     	
     	$this->CI->session->set_userdata('access_token',$params['access_token']);
     	
     	if($this->fbindex == "" ){
     		$page_data=$this->getData($page);
     		$this->CI->session->set_userdata('fbpage',$page);
     		if($page=='195554460484731')
     			$page_data->username='preproduccion.misiva';

     		$this->fbindex='https://www.facebook.com/'.$page_data->username.'?v=app_'.$this->api_id.'&app_data='.$this->namespace;
     	}
 		/*
     	if($this->namespace == "arbol_de_navidad")
     		echo $this->fbindex.' - '.$page;
     	else*/
   			echo '<script>parent.location.href="'.$this->fbindex.'"</script>';
     		
     	
	}
	
	public function getThinks($opcion){
		error_reporting(0);
		if($this->checkSession())
			return json_decode(file_get_contents('https://graph.facebook.com/'.$opcion.'?access_token='.$this->CI->session->userdata('access_token')));
	}

	
	public function getThinks2($opcion){
		error_reporting(0);
		echo 'https://graph.facebook.com/'.$opcion.'?access_token='.$this->CI->session->userdata('access_token');
	}
	
	public function publishThinks($opcion,$campos){ 
		$campos['access_token']=$this->CI->session->userdata('access_token');
		$s = curl_init();
    	curl_setopt($s,CURLOPT_URL,'https://graph.facebook.com/'.$opcion);
		curl_setopt($s,CURLOPT_HEADER, TRUE);
		curl_setopt($s,CURLOPT_NOBODY, TRUE);
		curl_setopt($s,CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($s,CURLOPT_POST,true);
		curl_setopt($s,CURLOPT_POSTFIELDS,$campos);
		curl_exec($s);
		curl_close($s);
	}
	
	
	public function getPhotosFromAlbum($id_album,$max){
		$data=$this->getData($id_album.'/photos');
		$result=array();
		if(!isset($data->error) or !is_array($data)){
			$photos=current($data);
			$count=0;
			foreach($photos as $photo){
				if($count==$max)
					break;
				$x=array();
				$x['id']=$photo->id;
				if(isset($photo->name))
					$x['name']=$photo->name;
				$x['picture']=$photo->picture;
				$x['width']=$photo->width;
				$x['height']=$photo->height;
				$x['link']=$photo->link;
				$x['created_time']=$photo->created_time;
				foreach($photo->images as $thumb){
					$t=array();
					$t['width']=$thumb->width;
					$t['height']=$thumb->height;
					$t['source']=$thumb->source;
					$x['thumbs'][]=$t;
				}
				$result[]=$x;
				$count++;
			}
			
		}
		return $result;
	}
	
	public function getAppData(){
		$data['api_id']=$this->api_id;
		$data['session']=$this->CI->session->userdata('access_token');
		return $data;
	}
	
	public function checkSession(){
		if(is_null(json_decode(file_get_contents('https://graph.facebook.com/me?access_token='.$this->CI->session->userdata('access_token'))))){
			$this->permission();
			return false;
		}
		return true;
	}
	
	public function getGeneral($pedido){
		return $this->parse_signed_request($pedido,$this->secret_key);
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
	
	private function getData($opcion){
		$curl = curl_init();
    	curl_setopt($curl,CURLOPT_URL,'https://graph.facebook.com/'.$opcion);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	
	    $result = curl_exec ($curl);
	    curl_close ($curl);
	    
	    return json_decode($result);
	}
	
}