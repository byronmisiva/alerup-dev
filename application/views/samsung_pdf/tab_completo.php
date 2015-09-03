 <div id="juego" style="position:absolute;width:810px;height:870px;left:0px;top:0px;margin: 0px; padding: 0px;overflow:hidden;">	 	 
	 <div style="position: absolute;left:0px;top:100px;">
	 	<img src="<?=$info?>"  />
	 </div>
	 
	 <div style="position:absolute;left:140px;top:93%;cursor:pointer;width:115px;height:28px;" 
			 onmouseout="$('#des1').hide();$('#act1').show();" onmouseover="$('#des1').show();$('#act1').hide();"
			 onclick="<?=$archivo1?>">
			<div id='act1' style="float:left;cursor:pointer;" >
				<img src="<?=$img_path.'btn1.png'?>"  />
			</div>
			<div id='des1'style="float:left;display:none;cursor:pointer;"  >
				<img src="<?=$img_path.'rollover.png'?>"  />
			</div>	
	</div>	
		
	<div style="position:absolute;left:400px;top:93%;cursor:pointer;width:115px;height:28px;" 
			 onmouseout="$('#des2').hide();$('#act2').show();" onmouseover="$('#des2').show();$('#act2').hide();"
			 onclick="<?=$archivo2?>">
				<div id='act2' style="float:left;cursor:pointer;" >
					<img src="<?=$img_path.'btn1.png'?>" />
				</div>
				<div id='des2'style="float:left;display:none;cursor:pointer;"  >
					<img src="<?=$img_path.'rollover.png'?>" />
				</div>	
		</div>	
		
	<div style="position:absolute;left:660px;top:93%;cursor:pointer;width:115px;height:28px;" 
			 onmouseout="$('#des3').hide();$('#act3').show();" onmouseover="$('#des3').show();$('#act3').hide();"
			 onclick="<?=$archivo3?>">
				<div id='act3' style="float:left;cursor:pointer;" >
					<img src="<?=$img_path.'btn1.png'?>" />
				</div>
				<div id='des3'style="float:left;display:none;cursor:pointer;"  >
					<img src="<?=$img_path.'rollover.png'?>" />
				</div>	
		</div>	
	 		
</div>  

   
