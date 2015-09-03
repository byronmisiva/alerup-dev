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
			<div class="fondo-seccion">
				<div class="franja-amarilla">
					<div class="logo-escuela"></div>
					<div class="logo-samsung"></div>
				</div>
				<div class="libreta"></div>
			    <!-- INTRO APLICACION- -->
				<div class="intro-test">
					<div class="franja-celeste contenedor-texto espacio-texto-test">
					Deberás completar 3 tests, de los cuales se sacará la calificación <br> final que 
					te permitirá entrar en la <span class="acento">Escuela Samsung</span> ¡Buena suerte!
					</div>
					<div class="franja-celeste contenedor-texto espacio-texto">Test</div>
					<div class="contenedor-opciones-test">
						<div class="opcione-test ">
							<div class="fondo-test1">
								<div class="test1"></div>
							</div>
							
							<div class="franja-celeste contenedor-texto espacio-entre-texto">Test 1</div>
						</div>
										
						<div class="opcione-test espacio-opciones-test">
							<div class="fondo-test2">
								<div class="test2"></div>
							</div>							
							<div class="franja-celeste contenedor-texto espacio-entre-texto">Test 2</div>
						</div>
						
						<div class="opcione-test espacio-opciones-test">
							<div class="fondo-test3">
								<div class="test3"></div>
							</div>
							
							<div class="franja-celeste contenedor-texto espacio-entre-texto">Test 3</div>
						</div>						
					</div>					
					<div class="btn-fondo btn-continuar-test">CONTINUAR</div>
				</div>			
				
				<div class="franja-amarilla-inferior2"> </div>
			</div>							
			<div class="view-text1">
					<div class="franja-amarilla">
						<div class="logo-escuela"></div>
						<div class="logo-samsung"></div>
					</div>
					<div class="lupa"></div>
				    <!-- INTRO APLICACION- -->
					<div class="intro-test">
						<div class="franja-celeste contenedor-texto titular-franja">
							<div class="ico-izquierda"> Test 1</div>
						</div>
						<div class="aviso-test1">
							<div class="franja-celeste contenedor-texto espacio-entre-texto ">
								<span class="acento">Instrucciones</span> <br>
								Observa la imagen e	identifica el producto Samsung oculto.
							</div>
							<div class="contenedor-opciones-test">
								<div class="opcion-test-prueba">
									<div class="fondo-celeste">
										<div class="imagen-buscar"></div>
									</div>
								</div>
								<div class="btn-fondo btn-continuar-test1">JUGAR</div>
							</div>
						</div>
						<?php 
						
						$ganador = rand(0,4);
						$listaNombre = array("Lavadora","Audífonos","Cámara","Curved tv","Microondas");
						$letras = array("A. ","B. ","C. ","D. ","C. ");
						shuffle($listaNombre);
						?>
						<div class="juego-test1">
							<div class="franja-roja contenedor-texto espacio-entre-texto ">
								<div class="contenedor-opciones-test-juego">
									<div class="opcion-test-prueba-juego">
											<div class="opcion-test-libreta">
												<div class="img-reconocer opcion-test-img<?php echo $ganador?>"></div>
											</div>
									</div>									
									<div class="lista-opciones">
									<?php for($x=0;$x<5; $x++){?>
										<div class="opcion-nombre" ref="<?php echo $x?>" 
										name="<?php echo $listaNombre[$x]?>">
										<?php echo $letras[$x]?>
										<?php echo $listaNombre[$x]?>
										</div>
									<?php 
									}?>
									</div>
								</div>
							</div>
						</div>	
						
						<div class="resultado-test1">
							<div class="franja-roja contenedor-texto espacio-entre-texto ">
								<div class="contenedor-opciones-test-juego">
									<div class="franja-mini"></div>
									<div class="ico-resultado1"></div>
									<div class="franja-max">
										<div class="texto-resultado-test1">
											Tu calificación es <span id="resultado-test1"></span>. <br>
											Da click en CONTINUAR para avanzar <br>
											con el siguiente test.
										</div>
										<div class="fondo-resultado">
											<div class="ico-resultado-test1"></div>
										</div>
									</div>
									
								</div>
								<div class="btn-fondo btn-continuar-test2">CONTINUAR</div>
							</div>
						</div>					
				</div>
				<div class="franja-amarilla-inferior2"> </div>
		</div>
		
		<div class=" view-text2"></div>			
		<div class=" view-text3">
			<div class="franja-amarilla">
				<div class="logo-escuela"></div>
				<div class="logo-samsung"></div>
			</div>
			<div class="cuadro"></div>					    
			<div class="intro-test">
				<div class="franja-celeste contenedor-texto titular-franja">
					<div class="ico-izquierda"> Test 3</div>
				</div>
				<div class="aviso-test3">
					<div class="franja-celeste contenedor-texto espacio-entre-texto ">
						<span class="acento">Instrucciones</span> <br>
						Recuerda el patrón de colores y marca la secuencia correcta.
						</div>
						<?php							
							$ganadorgrupo = rand(0,4);
							//$ganadorcuadro = rand(0,4);
							$listaCuadro = array("cuadro0","cuadro1","cuadro2","cuadro3","cuadro4");
							shuffle($listaCuadro);
						?>
						<div class="contenedor-opciones-test">
							<div class="opcion-test-prueba">
								<div class="fondo-celeste">
									<img class="cuadro-color" 
									src="<?php echo base_url()?>imagenes/escuela_samsung/test/cuadros/cuador<?php echo $ganadorgrupo."/". $listaCuadro[$ganadorgrupo].'.png'?>" />									
								</div>
							</div>
							<div class="btn-fondo btn-next-test3">JUGAR</div>
						</div>
					</div>
					
					<div class="juego-test3">
						<div class="franja-roja contenedor-texto espacio-entre-texto ">
								<div class="contenedor-opciones-test-juego">
									<div class="opcion-test-prueba-juego3">
										<?php for($x=0;$x<5; $x++){?>
											<div class="opcion-cuadro" 
											name="<?php echo "cuadro".$x?>"  ref="<?php echo $x?>" >
												<img src="<?php echo base_url()?>imagenes/escuela_samsung/test/cuadros/cuador<?php echo $ganadorgrupo."/".$listaCuadro[$x].".png"?>" />
											</div>
										<?php 
										}?>
									</div>	
								</div>
							</div>
						</div>			
										
					</div>
					<div class="franja-amarilla-inferior2"> </div>
			</div>
			<div class="view-ganaste">
				<div class="franja-amarilla">
					<div class="logo-escuela"></div>
					<div class="logo-samsung"></div>
				</div>
				<div class=premios-mini></div>					    
				<div class="intro-test">					
						<div class="franja-celeste contenedor-texto espacio-entre-texto ">
							¡Lo conseguiste has sido admitido! <br> 
							Tu calificación final es <span class="resultado-test3"></span>. <br>
							Comparte tu carnet oficial en tus redes sociales para que todos vean lo que has logrado. <br> 
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
	
			<div class="contenedor-postear-muro">
		 		<div class="fondo-posteo">
		 		<div class="titulo-posteo">Publicar en el Muro</div>
					<div class="cuadrotexto-ingreso">
						<textarea class="caja-texto" maxlength="250" placeholder="Escribe aqui un mensaje para tu amigo"></textarea>
					</div>
					<div class="imagen-postear"></div>					
					<div class="btn-fondo btn-postear">PUBLICAR</div>
					<div class="btn-fondo btn-cancelar-postear">CANCELAR</div>
		 		</div>	
		 	</div>		
						
			<div type="button" class="btn btn-primary btn-lg" data-toggle="modal" 
	 		 data-target="#mensaje-alerta" style="display: none;" id="accion-alerta-mensaje"></div>
	 			
			<div class="modal fade" id="mensaje-alerta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header color-titular">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
			        <span class="sr-only">Cerrar</span></button>
			        <h4 class="modal-title" id="myModalLabel" style="color:#fff;">Alerta</h4>
			      </div>
			      <div class="modal-body" style="color:#0E4083;">
			      	No te olvides de ingresar un mensaje para compartirlo con tus amigos.</div>			      
			    </div>
			  </div>
			</div>	
	
	<button type="button" id="posteoGenerado" style="display:none;" data-toggle="modal" data-target="#mensajePosteo"></button>			
<!-- Modal Crear Video-->
	<div class="modal fade" id="mensajePosteo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header color-titular">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	      </div>
	      <div class="modal-body">	      	
	      	<div>
	      		Tu imagen fue posteada con éxito.
	      	</div>	
	      </div>      
	    </div>
	  </div>
	</div>
 
<script src="<?php echo base_url()?>js/escuela_samsung/complemento2.js?frefresh=<?php echo rand(0,1000)?>"></script>
<script src="<?php echo base_url()?>js/escuela_samsung/game_intercambio.js?frefresh=<?php echo rand(0,1000)?>"></script>

<script type="text/javascript" >
	var test1 =<?php echo $ganador; ?>;
    var test2 = <?php echo $ganadorgrupo?>;
									
	$(".espera").hide();
  </script>











