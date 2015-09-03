<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="content-language" content="es"/>
	<title>App Compartir</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, user-scalable=no">	
	 <link type="text/css" rel="stylesheet" href="<?=base_url()?>css/bootstrap.min.css" />
	 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	 <link href="<?=base_url()?>css/app_compartir/app.css?refresh=<?php echo rand(200, 1000)?>" rel="stylesheet">	
	<script>				
		var usuarioFB;
		var idParticipante=0;
		var accion="<?php echo base_url()?>";
		var controladorApp="<?php echo $data['controlador'];?>";
		var linkCompartir= <?php echo $data['enlace'];?>;
	</script>	
	</head>
	<body>
	<div id="fb-root"></div>
	 <div class="container">
		<div class="row ">
			<div class="col-lg-4">				
				<div class="coke-share"></div>
			</div>
		</div>		
		</div>

	
	<script src="<?=base_url()?>js/bootstrap.min.js" type="text/javascript"></script>
	
	

	<script type="text/javascript" charset="utf-8">
		function statusChangeCallback(response) {
		    if (response.status === 'connected') {      
		    	onLogin();
		    } 
		  };
		  
	    function checkLoginState() {
		    FB.getLoginStatus(function(response) {
		      statusChangeCallback(response);
		    });
		  };

			function onLogin(response) {			  
			    FB.api('/me', function(respuesta) {
			    	usuarioFB = respuesta;
			    	if (modoDev == true){
						idParticipante="1005762036104636";						
			    	}else{
			    		idParticipante=respuesta.id;		    					    			
			    	}			    				    	
			    });
			};
			  
	 window.fbAsyncInit = function() {
		 FB.init({
		      appId      :  <?php echo $data['idApp'] ?>,		      
		      version    : 'v2.2',
              oauth  	 : true,
              status 	 : true,
              cookie 	 : true,
              xfbml  	 : true 
		    });

		 FB.getLoginStatus(function(response) {		 
			  if (response.status == 'connected') {
			    onLogin(response);
			  } else {		    
			    FB.login(function(response) {
			      onLogin(response);
			    }, {scope: 'email, publish_actions'});
				  }
				});  
	 };
		    
	  (function(d, s, id){
		     var js, fjs = d.getElementsByTagName(s)[0];
		     if (d.getElementById(id)) {return;}
		     js = d.createElement(s); js.id = id;
		     js.src = "//connect.facebook.net/es_LA/sdk.js";
		     fjs.parentNode.insertBefore(js, fjs);
		   }(document, 'script', 'facebook-jssdk'));

	 
	</script>
<script src="<?=base_url()?>js/app_compartir/app.js?refresh=<?php echo rand(10, 1000)?>" type="text/javascript" charset="utf-8"></script>


</body>
</html>


