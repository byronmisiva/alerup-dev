<!DOCTYPE html>
<html>
<head>
  <title>jQuery-cropbox</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1"/>  
  <link type="text/css" rel="stylesheet" href="<?php echo base_url()?>css/bootstrap.min.css">      
  
</head>
<body>
<div id="status"></div>
<input id="width" type="number" step="1" value="500">
<input id="height" type="number" step="1" value="300">
<input id="framerate" type="number" step="1" value="15">
<div class="container">
	 <canvas id="myCanvas" width="578" height="400"></canvas>    
</div>

<video id="awesome" loop="" autoplay="" controls=""></video>


<a id="download" download="video.webm" style="display:none">Download WebM</a>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/bootstrap.min.js"></script>
<script src="<?php echo base_url()?>js/galaxy-a/whammy.js"></script>		
	<script>
	var accion1 ="http://appss.misiva.com.ec/";
	var accion ="<?php echo base_url()?>";
	var controladorApp = "samsung_galaxy_a";
	var filesarr = [];
	var ctx = 0;
	var canvas = document.getElementById('myCanvas');
    var context = canvas.getContext('2d');
    var video = new Whammy.Video(15);

    canvas.width = document.getElementById("width").value;
    canvas.height = document.getElementById("height").value;
    var total;
    var x=0;
    var intervalos;
	function obtenetImagen(){		
			$.post( accion+controladorApp+"/getGaleria", { participante: "1005762036104636" }, 
					function( resultado ) {
						//filesarr = $.parseJSON(resultado);						
						 //total = filesarr.length;
						 //total = 1;						
						 //intervalos = setInterval(function(){ enviarImagen(); }, 1000);
						//for (var idx in filesarr){
						  //      img = filesarr[idx];
						    //    convertImgToBase64(accion+"galaxy-a/generados/"+img, function(base64Img){
						    //    });			
						        //crearCanvas(img)         
					    //};					   
					    //img = filesarr[0];
				        //crearCanvas(img);
				        
			});
	};

	function enviarImagen(){
		console.log(x);
		if( x<total ){
			var img = filesarr[x];			
	        convertImgToBase64(accion+"galaxy-a/generados/"+img, function(base64Img){
	        });	
		}else{
			clearInterval(intervalos);
			}
		 x++;
		};
			
	function crearCanvas(img2){
		      var imageObj = new Image();		      
	      
	      	imageObj.src = "./galaxy-a/generados/"+img;			  
		      imageObj.onload = function() {
		    	  context.globalAlpha = 0.2;
		          context.drawImage(imageObj, 0, 0);
		          video.add(canvas);
		          context.clearRect(0, 0, context.canvas.width,context.canvas.height);		          
		          context.globalAlpha = 0.4;
				  context.drawImage(imageObj, 0, 0, canvas.width, canvas.height);
				  video.add(context);
				  context.clearRect(0, 0, context.canvas.width,context.canvas.height);
				  
				  context.globalAlpha = 0.8;
				  context.drawImage(imageObj, 0, 0, canvas.width, canvas.height);
				  video.add(context);
				  context.clearRect(0, 0, context.canvas.width,context.canvas.height);
				  context.globalAlpha = 1;				  
				  context.drawImage(imageObj, 0, 0, canvas.width, canvas.height);
				  video.add(context);
				  context.clearRect(0, 0, context.canvas.width,context.canvas.height);

				   video.add(context);
				   video.add(context);
					video.add(context);
					video.add(context);
					video.add(context);
					video.add(context);
					video.add(context);
				  
				  context.globalAlpha = 0.8;
				  context.drawImage(imageObj, 0, 0, canvas.width, canvas.height);
				  video.add(context);
				  
				  context.clearRect(0, 0, context.canvas.width,context.canvas.height);
				  context.globalAlpha = 0.4;
				  context.drawImage(imageObj, 0, 0, canvas.width, canvas.height);
				  video.add(context);
				  context.clearRect(0, 0, context.canvas.width,context.canvas.height);
				  context.globalAlpha = 0.2;
		          context.drawImage(imageObj, 69, 50);
		          video.add(context);
		          ctx++;
				  finalizeVideo();
		      };
		      imageObj.src = accion1+"/galaxy-a/generados/"+img;

		     // convertImgToBase64URL(imageObj.src, function(base64Img){
		  	   //console.log(base64Img);
		  	//});
	}

	function finalizeVideo() {		
			var start_time = +new Date;			
			var output = video.compile();
			var end_time = +new Date;
			var url = webkitURL.createObjectURL(output);
			document.getElementById('awesome').src = url;
			document.getElementById('download').style.display = '';
			document.getElementById('download').href = url;
			document.getElementById('status').innerHTML = "Compiled Video in "
					+ (end_time - start_time) + "ms, file size: "
					+ Math.ceil(output.size / 1024) + "KB";
	}	
	




	function convertImgToBase64(url, callback, outputFormat){	
		var img = new Image;
		img.crossOrigin = 'Anonymous';
		img.onload = function(){
			canvas.height = img.height;
			canvas.width = img.width;
			context.globalAlpha = 0.2;
			context.drawImage(img,0,0);
			var dataURL = canvas.toDataURL(outputFormat || 'image/png');
		  	callback.call(this, dataURL);
			video.add(context);		  	
			context.globalAlpha = 0.4;
			context.drawImage(img,0,0);			
			var dataURL = canvas.toDataURL(outputFormat || 'image/png');
		  	callback.call(this, dataURL);
		  	video.add(context);
		  	
			context.globalAlpha = 0.6;
			context.drawImage(img,0,0);			
			var dataURL = canvas.toDataURL(outputFormat || 'image/png');
		  	callback.call(this, dataURL);
		  	video.add(context);
			context.globalAlpha = 0.8;
			context.drawImage(img,0,0);
			
			var dataURL = canvas.toDataURL(outputFormat || 'image/png');
		  	callback.call(this, dataURL);
		  	video.add(context);
		  	
			context.globalAlpha = 1;
			context.drawImage(img,0,0);			
		  	var dataURL = canvas.toDataURL(outputFormat || 'image/png');
		  	callback.call(this, dataURL);
		  	video.add(context);
		  	video.add(context);
		  	video.add(context);
		  	video.add(context);
		  	video.add(context);
		  	video.add(context);
		  	context.globalAlpha = 0.8;
			context.drawImage(img,0,0);			
		  	var dataURL = canvas.toDataURL(outputFormat || 'image/png');
		  	callback.call(this, dataURL);
		  	video.add(context);

		  	context.globalAlpha = 0.6;
			context.drawImage(img,0,0);			
		  	var dataURL = canvas.toDataURL(outputFormat || 'image/png');
		  	callback.call(this, dataURL);
		  	video.add(context);

		  	context.globalAlpha = 0.4;
			context.drawImage(img,0,0);			
		  	var dataURL = canvas.toDataURL(outputFormat || 'image/png');
		  	callback.call(this, dataURL);
		  	video.add(context);
		  	
		  	context.globalAlpha = 0.2;
			context.drawImage(img,0,0);			
		  	var dataURL = canvas.toDataURL(outputFormat || 'image/png');
		  	callback.call(this, dataURL);
		  	video.add(context);




		  		        
		  	//canvas = null; 
		  	
	        ctx++;
			finalizeVideo();
		};
		img.src = url;
	};

	obtenetImagen();
	
    </script>  
</body>























