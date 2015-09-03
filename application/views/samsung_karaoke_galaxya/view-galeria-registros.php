<?php 
if (is_array($galeria)){
	foreach ( $galeria as $row ){?>
	 <div style="float:left;width:250px;height: 300px;margin-right:15px ">
	 <img src="<?php echo base_url().'galaxy-a/generados/'.$row->imagen?> " width="100%"/>
	 </div>
	
	<?php }
}else{
	echo "No tiene imagenes";
	}?>