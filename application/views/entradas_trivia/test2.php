<div class="franja-amarilla">
	<div class="logo-escuela"></div>
	<div class="logo-samsung"></div>
</div>
<div class="pregunta"></div>
<div class="intro-test">
	<div class="franja-celeste contenedor-texto titular-franja">
		<div class="ico-izquierda">Test 2</div>
	</div>
	<div class="aviso-test2">
		<div class="franja-celeste contenedor-texto espacio-entre-texto ">
			<span class="acento">Instrucciones</span> <br>
			Observa con atención y señala en cuál vaso <br>
			se encuentra la Tablet Samsung.
		</div>
		<div class="contenedor-opciones-test">
			<div class="opcion-test-prueba">
				<div class="fondo-celeste">
					<div class="tablet-buscar"></div>
				</div>
			</div>
			<div class="btn-fondo btn-next-test2">JUGAR</div>
		</div>
	</div>
	<div class="juego-test2">
		<div class="franja-roja contenedor-texto espacio-entre-texto ">
			<div class="contenedor-actividad-bolita">
				<div class="vaso objeto1" ref="1"></div>
				<div class="vaso objeto2" ref="2"></div>
				<div class="vaso objeto3" ref="3"></div>
			</div>
		</div>
	</div>

	<div class="resultado-test2">
		<div class="franja-roja contenedor-texto espacio-entre-texto ">
			<div class="contenedor-opciones-test-juego">
				<div class="franja-mini"></div>
				<div class="ico-resultado2"></div>
				<div class="franja-max">
					<div class="texto-resultado-test1">
						Tu calificación es <span id="resultado-test2"></span>. <br> Da
						click en CONTINUAR para avanzar <br> con el test final.
					</div>
					<div class="fondo-resultado">
						<div class="ico-resultado-test2"></div>
					</div>
				</div>

			</div>
			<div class="btn-fondo btn-continuar-test3">CONTINUAR</div>
		</div>
	</div>
</div>
<div class="franja-amarilla-inferior2"> </div>

<script>
$(document).ready(function(){	
	$(".btn-next-test2").click(function(){
		$(".aviso-test2").hide();
		$(".juego-test2").show();
	});
	
	$(".vaso").click(function(){	
		if($(this).attr("id")=="activo"){
			$('.vaso').attr('id','activo2');
			mostrarGanador($(this).attr("ref"));
		}
	});
	
	$(".btn-continuar-test3").click(function(){
		$(".view-text2").hide();
		$(".view-text3").show();
	});
});

setTimeout(function(){ 
	xrep=0;	
	encerarValores();
	cambioPuestos();	
}, 2500);


</script>