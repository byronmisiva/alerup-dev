	<style>
	.opaco{
		background-color: rgba(0, 0, 0, 0.2) !important;
    	background-image: none;
	}	
	.opaco span{
	color:rgba(0, 0, 0, 0.2) !important;
	}	
	</style>	
	<div class="row view-inicio">
		<div class="col-lg-12">
			<div class="logo-samsung"></div>
			<div class="fondo-seccion">
			    <!-- INTRO APLICACION- -->
				<div class="intro-preguntas">
					<p class="titular color-titular">
					Instrucciones
					</p>
					<p>
					1. Contestar todas las preguntas en un lapso de 45 segundos.
					</p>
					<p>
					2. Al contestarloas todas correctamente, participarás por ver a Juanes en vivo.
					</p>
					<p>
					3. ¡Diviértete!
					</p>
					<div class="btn-fondo btn-continuar-test">Empezar</div>
				</div>			
				<!-- PREGUNTAS -->
				<div class="seccion-preguntas">
					<div id="myCarousel" class="carousel slide" data-ride="carousel">
						  <!-- Wrapper for slides -->
						  <div class="carousel-inner" role="listbox">
						  	<?php
						  	$contador=0; 
						  	foreach($preguntas as $row){?>
								<div class="item <?php echo ($contador==0)?"active" : " ";?>" id="opcion-<?php echo $contador?>">
						      		<p class="fondo-pregunta"><?php echo $row->texto?></p>
						      		<ul>
						      		<?php foreach($respuestas as $row2){
						      			if($row2->padre == $row->id){?>
						      			<li class="fondo-respuesta" ref="<?php echo $row2->respuesta?>"><?php echo $row2->texto ?></li>
						      			<?php }?>
						      		<?php }?>
						      		</ul>
						    	</div>						  	
						  	<?php 
						  	$contador++;
							}?>    
						  </div>  
						</div>					
				</div>	
				<!-- CORRECTO -->
				
				<!-- NUEVA OPORTUNIDAD -->
				
			</div>	
			<div class="fondo-juanes"></div>						
			
		
					
		
			<div class="view-ganaste">				
				<div class=premios-mini></div>					    
				<div class="intro-test">					
						<div class="franja-celeste contenedor-texto espacio-entre-texto ">							 
							¡Felicitaciones!
						</div>
						
						<div class="contenedor-opciones-test">
							<div class="opcion-test3-prueba">
								<div class="fondo-carnet">
									<div class="nombre-user alto-datos"></div>
									<div class="edad-user"></div>
									<div class="seccion-user">
										Estudiante <br> 2015-2016
									</div>
									<div class="foto-user">									
										<img class="imagen-perfil-user" src="" /></div>
									</div>
								</div>
							</div>
							<div class="contenedor-btn-compartir">
								<div class="btn-fondo btn-compartir-app">COMPARTIR</div>
								<div class="btn-fondo btn-post-img">POSTEAR</div>
							</div>
					
					</div>
					</div>
					<div class="view-perdiste">
						<div class="franja-amarilla">
							<div class="logo-escuela"></div>
							<div class="logo-samsung"></div>
						</div>
											    
						<div class="perdiste-test">
							<div class="aviso-perdiste">
								<div class="error-perdiste"></div>
							</div>
							
							<div class="franja-celeste contenedor-texto">
								<span class="acento">¡Lo sentimos has reprobado!</span><br>
								Tu calificación  final es <spn class="resultado-test3"></spn> <br> 
								<span class="acento">¡Practica un poco más y vuelve a intentarlo!</span>
							</div>					
								
							<div class="btn-fondo btn-volver">VOLVER</div>
							
							</div>
					</div>			
			</div>
			</div>
	
 
<script src="<?php echo base_url()?>js/entradas_samsung/complemento2.js?frefresh=<?php echo rand(0,1000)?>"></script>


<script type="text/javascript" >
	var test1 =<?php echo $ganador; ?>;
    var test2 = <?php echo $ganadorgrupo?>;
									
	$(".espera").hide();
	$('.carousel').carousel({
		  interval: false
		})
  </script>











