<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Entradas Juanes :: Samsung</title>
	<link href="<?php echo base_url()?>css/bootstrap.min.css" type="text/css" rel="stylesheet" />
	<link href="<?php echo base_url()?>css/entradas_samsung/app.css?frefresh=<?php echo rand(0,1000)?>" type="text/css" rel="stylesheet">
	<link href="<?php echo base_url()?>fonts/samsung_regular_if/stylesheet.css" type="text/css" rel="stylesheet">
	<link href="<?php echo base_url()?>fonts/samsung_bold_if/stylesheet.css" type="text/css" rel="stylesheet">			    
	<script src="<?php echo base_url()?>js/entradas_samsung/app.js?frefresh=<?php echo rand(0,1000)?>" type="text/javascript" charset="utf-8"></script>
  	<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>  	
	<script type="text/javascript">
	var usuarioFB;
	var idParticipante=0;
	var modoDev=true;
	if (modoDev == true)
		idParticipante=1005762036104636; 
	var edadUser;
	var accion="<?php echo base_url()?>";
	var controladorApp="<?php echo $data['controlador'];?>";	
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
	</script>
</head>
<body>
<div id="fb-root"> </div> 
<div class="container">
	<div class="row view-inicio">
		<div class="col-lg-12 " >
		<div class="logo-samsung"></div>
		<div class="fondo-seccion">
			<div class="logo-evento"></div>
			<div class="franja-celeste">
				<div class="intro-texto">
					<p class="titular">¿Eres fan de Juanes?</p>				
					<p>Demuéstralo y responde correctamente nuestras preguntas.</p>	
					<p>Como premio, podrás verlo en vivo.</p>				
					<div class="btn-fondo btn-entrar">Participar</div>
				</div>
				<div class="view-registro">
				<form id="register" name="register" method="post" role="form" class="form-horizontal formulario" >
					<div class="registro espacio-columna" >							
								<div class="form-group">	
									<label for="nombre" class="col-sm-2 control-label">Nombre</label>
    								<div class="col-sm-10">      
										<div class="form-control inputTexto" >
											<input type="text" id="nombre"	name="nombre" placeholder="Nombre" value="" required />				
										</div>
									</div>
								</div>
								<div class="form-group">	
								<label for="apellido" class="col-sm-2 control-label">Apellido</label>
    								<div class="col-sm-10">	
										<div class="form-control inputTexto espacio-input" >
											<input type="text" id="apellido" name="apellido" placeholder="Apellido" value="" required />				
										</div>
									</div>
								</div>								
								
								<div class="form-group " >	
								<label for="telefono" class="col-sm-2 control-label">Teléfono</label>
    								<div class="col-sm-10">			
									<div class="form-control inputTexto espacio-input" >
										<input type="text" id="telefono" name="telefono" placeholder="Teléfono" value="" maxlength="10" required />
									</div>
									</div>
								</div>
																						
								<div class="form-group " >
								<label for="cedula" class="col-sm-2 control-label">Cédula</label>
    								<div class="col-sm-10">				
									<div class="form-control inputTexto espacio-input" >
										<input type="text" id="cedula" name="cedula" placeholder="Cédula" value="" maxlength="10" required />
									</div>
									</div>
								</div>																				
								<input type="hidden" name="user" id="user" value="">
								<input type="hidden" name="fbid" id="fbid" value="">															
							
								<div class="form-group" >	
									<label for="mail" class="col-sm-2 control-label">Email</label>
    								<div class="col-sm-10">				
									<div class="form-control inputTexto espacio-input" >					
										<input type="text" id="mail" name="mail" placeholder="E-mail" value="" required />
									</div>
									</div>
								</div>						
															
								<div class="form-group">	
								<label for="ciudad" class="col-sm-2 control-label">Ciudad</label>
    								<div class="col-sm-10">			
										<div class="form-control inputTexto espacio-input" >			
											<input type="text"  id="ciudad" name="ciudad" placeholder="Ciudad" value="" required />
										</div>
									</div>
								</div>							
							</div>
							 <div class="form-group" style="margin-top: 10px;display: none;">
									<input id="submit" name="submit" type="submit" value="" class="btn-sumbit" />
								</div>
							</form>
						<div class="btn-fondo btn-registro">Continuar</div>
					</div>
				</div>
			</div>			
		</div>
		<!-- <div class="fondo-juanes-sombra"></div> -->		
		<div class="fondo-juanes"></div>
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
			    }, {scope: 'email'});
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
		
		var rules = [ 
					   { name: 'nombre', display: 'nombre', rules: 'required'},
					   { name: 'apellido', display: 'apellido', rules: 'required'},
		               { name: 'ciudad', display: 'ciudad', rules: 'required'},
		               { name: 'cedula', display: 'cedula', rules: 'required|numeric||max_length[10]'},
		               { name: 'telefono', display: 'telefono', rules: 'required|numeric|max_length[10]'},	               	               
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
			    	$(".btn-registro").hide();
				    $("#submit").hide();
				    edadUser=$("#edad").val();
			    	enviarForma('register'); 		    			    	
				    }
			});	  
		var dis ="<?php  echo $data['dispositivo'];?>";		
	</script>  
<script src="<?php echo base_url()?>js/entradas_samsung/complemento.js?frefresh=<?php echo rand(0,1000)?>" type="text/javascript" ></script>
</body>
</html>
























