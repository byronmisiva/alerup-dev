<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Galaxy A :: Registros </title>
	<link href="<?php echo base_url()?>css/bootstrap.min.css" type="text/css" rel="stylesheet" />
	<link href="<?php echo base_url()?>css/galaxy-a/app.css?frefresh=<?php echo rand(0,1000)?>" type="text/css" rel="stylesheet">
  	<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>	
</head>
<body> 
<div class="container">
<?php foreach ($registros as $row){?>
		<div class="row">
			<div class="col-lg-12">		
				<div class="accordion" id="accordion2">
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" 
							   href="#collapse<?php echo $row->id?>" id="<?php echo $row->id?>">
								<?php echo $row->completo ?> 
							</a>
						</div>
						<div id="collapse<?php echo $row->id?>"
							class="accordion-body collapse">
							<div class="accordion-inner">
								<div class="contenedor-previo-galeria-<?php echo $row->id?>"></div>
							</div>
						</div>
					</div>
				</div>
			</div>		
		</div>
		
		
		
		<?php } ?>
		
				

</div>
	<script src="<?php echo base_url()?>js/bootstrap.min.js" type="text/javascript"></script>
<script>
var idPartido;
var contenedorToggle=0;
var accionUrl ="<?php echo base_url()?>";
    $(document).ready(function () {
		$(".accordion-toggle").click(function(){
			idPartido = $(this).attr("id");
			$(".contenedor-previo-galeria-"+idPartido).load(accionUrl+"samsung_galaxy_a/cargaGaleria/",{iduser:idPartido});
			/*if ( contenedorToggle == 0 )
				contenedorToggle = idPartido;
			else{
				$(".contenedor-previo-imagen-"+contenedorToggle).html("");
				contenedorToggle = idPartido;
			}*/			
		});

	
		
    });
    
</script>

</body>
</html>
























