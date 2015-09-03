<div style="max-width: 480px; width: 100%; margin: 0 auto">

    <div class="col-md-12 col-sm-12 col-xs-12   " style=" ">
        <div class="center-block col-center-block center center-block btn_compartir botontexto" nombrevideo="<?php echo $nombrevideo; ?>" idvideo="<?php echo $id; ?>">
            Compartir
        </div>
    </div>
   <!-- <div class="col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 15px">
        <div class=" btn_votar center-block botontexto" idvideo="<?php /*echo $id; */?>">
            Votar
        </div>
    </div>-->

    <!--<div class="col-md-12 col-sm-12 col-xs-12 text-center">
        <p style="text-align: center">

        <div class="mensajevotar"></div>
        </p>
    </div>-->


    <video width="100%" controls autoplay>
        <source src="<?php echo base_url() ?>videos/<?php echo $video; ?>" type="video/mp4">
        Su navegador no soporta video HTML5.
    </video>

</div>

<script type="text/javascript">
    var idvideo;
    var nombrevideoconcomillas = ""



    $(".btn_compartir").click(function () {
        idvideo = $(this).attr('idvideo');
        nombrecompleto = $(this).attr('nombrevideo');
        nombrecompleto = nombrecompleto.split(',');
        if (nombrecompleto[1] != "")
            nombrevideoconcomillas =   '"' + nombrecompleto[1] + '"';

        FB.ui({
            method: 'feed', /***metodo facebook compartir en el muro**/
            picture: "https://appss.misiva.com.ec/imagenes/karaokegalaxya/icono/190x190.png", /*carga de icono*/
           // link: 'https://apps.facebook.com/samsung_karaoke_galaxya/' +  idvideo, /******link que se comparte*******/
            link: accion+controladorApp+ '/vervideo/' +  idvideo , /******link que se comparte*******/
            caption: 'Galaxy Karaoke A',
            description:'Ya estoy participando en el Karaoke Galaxy A de Samsung. Sube el tuyo y revive los 90’s como un verdadero A.'}, function(response){
            //description:'Mira mi video'+ nombrevideoconcomillas + ' en  el Karaoke Galaxy A de Samsung. Sube el tuyo y revive la mejor música de los años 90.'}, function(response){
            if (response != undefined){
                $.ajax({
                    type: "GET",
                    url: accion+controladorApp+"/sumarCompartida/"+idvideo, /*contador suma de compartidos*/
                    success: function(valor) {
                        $(".btn_compartir").hide();
                    }
                });
            }
        });
    });
    $(".btn_votar").click(function () {
        idvideo = $(this).attr('idvideo')
        $.post(accion+controladorApp+ "/votar", {id : idvideo, fbid: "fbid123123"})
            .done(function (data) {
                $(".btn_votar").hide();
                $(".mensajevotar").html(data);
            });
    });
</script>
