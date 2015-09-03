<!DOCTYPE html>
<html>
<head>
	<title>Escuela :: Samsung</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">    
    <meta name="viewport" content="width=device-width, user-scalable=no"/>
	<link href="<?php echo base_url()?>css/bootstrap.min.css" type="text/css" rel="stylesheet" />
	<link href="<?php echo base_url()?>css/escuela_samsung/app.css?frefresh=<?php echo rand(0,1000)?>" type="text/css" rel="stylesheet">
	<link href="<?php echo base_url()?>fonts/samsung_regular_if/stylesheet.css" type="text/css" rel="stylesheet">
	<link href="<?php echo base_url()?>fonts/samsung_bold_if/stylesheet.css" type="text/css" rel="stylesheet">			    
	<script src="<?php echo base_url()?>js/escuela_samsung/app.js?frefresh=<?php echo rand(0,1000)?>" type="text/javascript" charset="utf-8"></script>
  	<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
  	<script src="<?php echo base_url()?>js/escuela_samsung/jquery_path.js?frefresh=<?php echo rand(0,1000)?>" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
	var usuarioFB;
	var idParticipante=0;
	var edadUser;
	var modoDev=false;
	
	if (modoDev == true)
		idParticipante=1005762036104636; 
	
	var accion="<?php echo base_url()?>";
	var controladorApp="<?php echo $data['controlador'];?>";	
	function onLogin(response) {	  
			    FB.api('/me', function(respuesta) {
			    	usuarioFB = respuesta;			    	
			    	if (modoDev == true){
						idParticipante="1005762036104636";	
						$(".login-caja").hide();
			    		$(".btn-entrar").show();					
			    	}else{
			    		idParticipante=respuesta.id;
			    		$(".login-caja").hide();
			    		$(".btn-entrar").show();
			    		}			    				    	
			    });
			};			
	</script>
</head>
<body>
<div id="fb-root"> </div> 	
<div class="container">
							
<div class="row view-inicio">
		<div class="col-lg-12 " >
		<div class="fondo-seccion">
			<div class="franja-amarilla">
				<div class="logo-escuela"></div>
				<div class="logo-samsung"></div>
			</div>
			<div class="pizarra"></div>
			<div class="franja-celeste">
				<div class="contenedor-texto espacio-texto">
				Pasa las pruebas para ingresar a la <span class="acento">Escuela Samsung</span> <br>y 
				¡gana kits escolares! Así, estarás listo para este regreso a clases.							
				</div>					
				
			</div>
				<div class="login-caja">
					<fb:login-button scope="email" onlogin="checkLoginState();"></fb:login-button>																
				</div>
				<div class="btn-fondo btn-entrar"> INGRESAR</div>
				
			<div class="franja-amarilla-inferior2"></div>				
		</div>
			
		</div>
	</div>
	
	<div class="row view-registro">
		<div class="col-lg-12 incio" >
			<div class="fondo-seccion">
				<div class="franja-amarilla">
					<div class="logo-escuela"></div>
					<div class="logo-samsung"></div>
				</div>
				<div class="premios"></div>
				<div class="franja-celeste">
					<div class="contenedor-texto espacio-texto">
					Llena todos tus datos para acceder a tu Examen de Ingreso							
					</div>					
					
				</div>
				<div class="franja-celeste">
				<form id="register" name="register" method="post" role="form" class="form-horizontal formulario" >
					<div class="registro espacio-columna" >
							
								<div class="form-group">	
									<label for="nombre" class="col-sm-2 control-label">Nombre</label>
    								<div class="col-sm-10">      
										<div class="form-control inputTexto" >
											<input type="text" id="nombre"	name="nombre" placeholder="Nombre" value="" />				
										</div>
									</div>
								</div>
								<div class="form-group">	
								<label for="apellido" class="col-sm-2 control-label">Apellido</label>
    								<div class="col-sm-10">	
										<div class="form-control inputTexto espacio-input" >
											<input type="text" id="apellido" name="apellido" placeholder="Apellido" value="" />				
										</div>
									</div>
								</div>								
								
								<div class="form-group " >	
								<label for="telefono" class="col-sm-2 control-label">Teléfono</label>
    								<div class="col-sm-10">			
									<div class="form-control inputTexto espacio-input" >
										<input type="text" id="telefono" name="telefono" placeholder="Teléfono" value="" maxlength="10" />
									</div>
									</div>
								</div>
																						
								<div class="form-group " >
								<label for="cedula" class="col-sm-2 control-label">Cédula</label>
    								<div class="col-sm-10">				
									<div class="form-control inputTexto espacio-input" >
										<input type="text" id="cedula" name="cedula" placeholder="Cédula" value="" maxlength="10" />
									</div>
									</div>
								</div>																				
								<input type="hidden" name="user" id="user" value="">
								<input type="hidden" name="fbid" id="fbid" value="">
															
							</div>
							
							
							<div class="registro espacio-columna" >
								<div class="form-group" >	
									<label for="mail" class="col-sm-2 control-label">Email</label>
    								<div class="col-sm-10">				
									<div class="form-control inputTexto espacio-input" >					
										<input type="text" id="mail" name="mail" placeholder="E-mail" value="" />
									</div>
									</div>
								</div>
							
															
								<div class="form-group">	
								<label for="ciudad" class="col-sm-2 control-label">Ciudad</label>
    								<div class="col-sm-10">			
										<div class="form-control inputTexto espacio-input" >			
											<input type="text"  id="ciudad" name="ciudad" placeholder="Ciudad" value="" />
										</div>
									</div>
								</div>
								
								<div class="form-group" >	
								<label for="mail" class="col-sm-2 control-label">Edad</label>
    								<div class="col-sm-10">				
									<div class="form-control inputTexto espacio-input" >					
										<input type="text" id="edad" name="edad" placeholder="Edad" value="" maxlength="2" />
									</div>
									</div>
								</div>
												
								
															
							</div>
							 <div class="form-group" style="margin-top: 10px;display: none;">
									<input id="submit" name="submit" type="submit" value="" class="btn-sumbit" />
								</div>
							</form>
							</div>
							
							<div class="btn-fondo btn-registro">CONTINUAR</div>
					<div class="franja-amarilla-inferior2"></div>
			</div>	
						
		</div>
	</div>

</div>
<div id="condiciones">
		 <strong style="color:#000;;">
		 	<?php echo $data['condiciones'];?>
		 </strong><br/>			
	</div>
	<div class="espera">
		<div class="cargando"></div>		
	</div>
	<script type="text/javascript" src="<?php echo base_url('js/general/validate.js')?>"></script>
	<script src="<?php echo base_url()?>js/bootstrap.min.js" type="text/javascript"></script>
	


<script type="text/javascript" charset="utf-8">
function statusChangeCallback(response) {
    if (response.status === 'connected') {      
    	onLogin();
    }else{
    	$(".login-caja").show();
		$(".btn-entrar").hide();
    } 
  };
  
	  function checkLoginState() {
	    FB.getLoginStatus(function(response) {
	      statusChangeCallback(response);
	    });
	  }

	
window.fbAsyncInit = function() {
    FB.init({
      appId      : <?php echo $data['idApp'] ?>,
      xfbml      : true,
      version    : 'v2.2'
    });

    FB.getLoginStatus(function(response) {		 
		  if (response.status == 'connected') {
		    onLogin(response);
		  } else {		    
		    FB.login(function(response) {
		      onLogin(response);
		    }, {scope: 'email, publish_actions'});
		    $(".login-caja").show();
			$(".btn-entrar").hide();
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
  $(".login-caja").hide();
	$(".btn-entrar").show();	
  
	var rules = [ 
				   { name: 'nombre', display: 'nombre', rules: 'required'},
				   { name: 'apellido', display: 'apellido', rules: 'required'},
	               { name: 'ciudad', display: 'ciudad', rules: 'required'},
	               { name: 'cedula', display: 'cedula', rules: 'required|numeric||max_length[10]'},
	               { name: 'telefono', display: 'telefono', rules: 'required|numeric|max_length[10]'},
	               { name: 'edad', display: 'Edad', rules: 'required|numeric|max_length[2]'},	               
	               { name: 'mail', display: 'mail', rules: 'required|valid_email'}	               	               
	            ];    
	
	var validator = new FormValidator('register',rules, function(errors, event) {		
		 if (errors.length > 0) {
		        var errorString = '';		        
		        for (var i = 0, errorLength = errors.length; i < errorLength; i++) {
		        	$('#'+errors[i].id).val("");		        	
			        $('#'+errors[i].id).css({"color":"#42332a"});
			        errorString+=errors[i].id+"<br>";
		        }; 
		        alert("REGISTROS NO COMPLETADOS");				
		    }else{
		    	edadUser=$("#edad").val();
		    	enviarForma('register'); 		    			    	
			    }
		});	  

	var dis ="<?php  echo $data['dispositivo'];?>";		
	</script>  
<script src="<?php echo base_url()?>js/escuela_samsung/complemento.js?frefresh=<?php echo rand(0,1000)?>" type="text/javascript" ></script>

</body>
</html>



	




















