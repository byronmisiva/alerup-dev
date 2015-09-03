//funciones scriptcam
$(document).ready(function () {

    //llamada para mostrar webcam en div, incluye botones
    crearBotonesInterface();
    iniciaFormulario()
    //$("input.file").si();

   // $('#recorder').height(screen.height);

/*    document.getElementById("uploadBtn").onchange = function () {
        document.getElementById("uploadFile").value = this.value;
    };*/

});



function iniciaFormulario() {
    $("#formuploadvideo").submit(function (event) {
        //ocultamos el boton y mostramos el loader


        $('.loader-lineal').removeClass("hidden").show();
        $('.btn-subir-video').hide();

        var url = accion + controladorApp + "/uploadvideo";

        $.ajax({
            url: url,
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
               // error =   data.split(':');
                //if (error[0] = 'false'){
                  //  alert ("Error : " +  error[1])
                //} else {
                    nombreArchivoSubido = data;
                    var nuevovideo = '<video id="videoSubido" width="100%" controls="" autoplay="">' +
                        '<source src="' + accion + 'videos/' + nombreArchivoSubido + '" type="video/mp4">' +
                        'Su navegador no soporta video HTML5.' +
                        '</video>';
                    $('.videoSubido').html(nuevovideo);
                    setTimeout(callbackFunction, 3000);
                    $('.formuploadenvio').removeClass("hidden").show();
                    $('.formuploadfile').hide();
                //}
            }
        });
        event.preventDefault();
        return false;
    });
    $("#registroform").submit(function (event) {
        var url = accion + controladorApp + "/register";
        $.ajax({
            url: url,
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            DataType : "jsonp",
            success: function (data) {
                ocultarTodosSeccion();
                $("#recorder").removeClass("hidden").show();
                $('#loadergif').hide();

                $('#menuvideo-container').removeClass("hidden").show();
                $('#webcam-container').hide();
                $('#mediaplayer-container').hide();
                $('#uploadFileContainer').hide();

            }
        });
        event.preventDefault();
        return false;

    });
}
function crearBotonesInterface() {

    $('#btnwebcam').click(function () {
        $('#menuvideo-container').hide();
        $('#webcam-container').removeClass("hidden").show();
        $('#mediaplayer-container').hide();
        $('#uploadFileContainer').hide();

        cargarWebCam();
    })

    $('#subirVideo').click(function () {
        $('#menuvideo-container').hide();
        $('#webcam-container').hide();
        $('#uploadFileContainer').removeClass("hidden").show();
        $('#mediaplayer-container').hide();

        $('.formuploadenvio').hide();
        $('.formuploadfile').removeClass("hidden").show();
    })
    //botones home
    $('.btn-home-home').click(function () {
        ocultarTodosSeccion();
        $('#home').removeClass("hidden").show();
    })
    $('.btn-home-instrucciones').click(function () {
        ocultarTodosSeccion();
        $("#instrucciones").removeClass("hidden").show();
    })
    $('.btn-home-subir-video').click(function () {
        if (idParticipante != 0) {
            $.post(accion +   controladorApp + "/verificarParticipante", {idParticipante: idParticipante})
                .done(function (data) {
                    if (data != 'F') {
                        ocultarTodosSeccion();
                        $("#recorder").removeClass("hidden").show();

                        $('#menuvideo-container').removeClass("hidden").show();
                        $('#webcam-container').hide();
                        $('#uploadFileContainer').hide();
                        $('#mediaplayer-container').hide();
                        $('#loadergif').hide();
                        if (dis == 'movil'){

                            $('#subirVideo').click();
                            $('.fileUpload').removeClass("hidden").show();
                            $('.text-loader-inicio').removeClass("hidden").show();
                            $('.text-loader-lineal').hide();
                            $('.loader-lineal').hide();

                            $('input#fileToUpload').click();

                        }

                    } else {
                        // no esta registrado
                        $('#menuvideo-container').removeClass("hidden").show();
                        $('#webcam-container').hide();
                        $('#uploadFileContainer').hide();
                        $('#mediaplayer-container').hide();


                        $('#loadergif').hide();
                        $('#fbid').val(idParticipante);
                        //cargarWebCam();
                        ocultarTodosSeccion();
                        $("#registro").removeClass("hidden").show();
                    }
                });
        }
    })
    $('.btn-home-galeria').click(function () {
        ocultarTodosSeccion();
        $("#galeria").removeClass("hidden").show();
        cargarGaleria();
    })
    $('.btn-home-registro').click(function () {
        ocultarTodosSeccion();
        $("#registro").removeClass("hidden").show();
    })

    $('#volverGrabar').click(function () {
        $('#menuvideo-container').hide();
        $('#webcam-container').removeClass("hidden").show();
        $('#uploadFileContainer').hide();
        $('#mediaplayer-container').hide();

    })
    $('#btnContinuarGraba').click(function () {

        $('#menuvideo-container').removeClass("hidden").show();
        $('#webcam-container').hide();
        $('#uploadFileContainer').hide();
        $('#mediaplayer-container').hide();

        ocultarTodosSeccion();
        $('#galeria').removeClass("hidden").show();

        grabarBaseDatosVideo(fileNameSolo, fileNameSolo.replace("mp4", "gif"));

        cargarGaleria();

    })
    $('#btnContinuarSubir').click(function () {
        $('#recordStartButton').removeClass("hidden").show();
        $('#recorder').hide();
        $('#galeria').removeClass("hidden").show();
        grabarImagen();
    })

    $("input[type=file]").on('change', function () {
        $('.btn-subir-video').removeClass("hidden").show();
        $('.loader-lineal').hide();
        if (dis == 'movil'){


           $('.fileUpload').hide();
            $('.btn-subir-video').click();
            $('.loader-lineal').removeClass("hidden").show();
            $('.text-loader-lineal').removeClass("hidden").show();
            $('.text-loader-inicio').hide();



        }

    });

    if (vervideo > 0 )
    {
        ocultarTodosSeccion();
        $("#galeria").removeClass("hidden").show();
        cargarGaleria();
       // box-buscar-video
        $("#box-buscar-video").val(nombreUsuarioVideo);
        $("#boton-buscar-video").click();



    };
}

function ocultarTodosSeccion() {
    $('.seccion').hide();
}
function cargarWebCam() {
    $('webcam-container').removeClass("hidden").show();
    $("#webcam").scriptcam({
        fileReady: fileReady,
        cornerRadius: 20,
        cornerColor: 'e3e5e2',
        onError: onError,
        promptWillShow: promptWillShow,
        showMicrophoneErrors: false,
        onWebcamReady: onWebcamReady,
        //setVolume: setVolume,
        setVolume: 80,
        timeLeft: timeLeft,
        fileName: 'demofilename',
        connected: showRecord,
        maximumTime: 15,
        videoRoomThumbnails: true,
        path: accion + 'js/karaokegalaxya/scriptcam/',
        showDebug: false,
        width: 480,
        height: 360
    });

    $('#recordStartButton').click(function () {
        startRecording()
    })



    $('#recordPauseResumeButton').click(function () {
        pauseResumeCamera()
    })

    $('#recordStopButton').click(function () {
        closeCamera()
    })

    // evento de la ventana alert
    window.alert = function (al, $) {
        return function (msg) {
            al.call(window, msg);
            $(window).trigger("okbuttonclicked");
        };
    }(window.alert, window.jQuery);

    $(window).on("okbuttonclicked", function () {
        $('#recordStopButton').click()
    });




}
function callbackFunction() {
    var canvas = document.getElementById('canvas');
    var video = document.getElementById('videoSubido');
    canvas.width = 200;
    canvas.height = 157;
    canvas.getContext('2d').drawImage(video, 0, 0, 300, 150);
}

var nombrevideoinput = '';
function grabarImagen() {
    // Generate the image data
    nombre1 = $('#box-nombre-video').val();
    if (nombre1 == "undefined")
        nombre1 = "";

    nombre2 = $('#box-nombre-video1').val();
    if (nombre2 == "undefined")
        nombre2 = "";



    nombrevideoinput = nombre1 +  nombre2;
    var Pic = document.getElementById("canvas").toDataURL("image/png");
    Pic = Pic.replace(/^data:image\/(png|jpg);base64,/, "")

    $.post(accion + controladorApp + "/uploadimagen", {imageData: Pic, nombreArchivoSubido: nombreArchivoSubido })
        .done(function (data) {
            obj = JSON.parse(data);
            grabarBaseDatosVideo(obj['video'], obj['imagen']);
            cargarGaleria();
        });
}

var nombreArchivoSubido = "";

function cargarLigthbox() {
    // delegate calls to data-toggle="lightbox"

    $(document).undelegate('*[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', 'click');

    $(document).delegate('*[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', 'click', function (event) {

        this.variable = 12;
        event.preventDefault();
        return $(this).ekkoLightbox({
            onShown: function () {
                if (window.console) {
                    return console.log('Checking our the events huh?');
                }
            },
            onNavigate: function (direction, itemIndex) {
                if (window.console) {
                    return console.log('Navigating ' + direction + '. Current item: ' + itemIndex);
                }
            }
        });
    });
}


function cargarGaleria() {
    $.post(accion +  "index.php/" + controladorApp + "/listadojson", {filtro:nombreUsuarioVideo})
        .done(function (data) {
            // Cargamos la informacion de la galeria
            // en caso que data regrese como str convertimos en objeto json
            if ("object" != typeof data)
                var data = JSON.parse(data);

            generaGaleria(data);
            cargarLigthbox();
            eventoBuscarVideo();
        });
}


function eventoBuscarVideo() {
    $(".boton-buscar-video").click(function () {
        filtro = $("#box-buscar-video").val();
        $.post(accion + controladorApp + "/listadojson", {filtro: filtro })
            .done(function (data) {
                // Cargamos la informacion de la galeria
                // en caso que data regrese como str convertimos en objeto json
                if ("object" != typeof data)
                    var data = JSON.parse(data);

                generaGaleria(data);
                cargarLigthbox();
                eventoBuscarVideo();
            });
    })

}
// en base al array data generamos la galeria de imagenes
function generaGaleria(data) {
    //todo link archivo
    var cabeceraGaleria = '<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">' +
        '<div class="carousel-inner" role="listbox">';

    var finGaleria = '</div>' +
        '<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">' +
        '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> ' +
        '<span class="sr-only">Previous</span></a>' +
        ' <a class="right carousel-control" href="#carousel-example-generic" role="button"  data-slide="next">' +
        '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>' +
        '<span class="sr-only">Next</span></a>' +
        '</div>';

    var htmlGaleria = "";
    var iteraciones = Math.ceil(data.length / 6);

    for (j = 0; j < iteraciones; j++) {
        var divimagen = "";
        for (i = 0 + (j * 6); i < 6 + (j * 6); i++) {
            if (typeof data[i] != 'undefined') {

                nombreimagen = data[i]["filenameimage"];
                nombrevideo = data[i]["filename"];
                titulovideo = data[i]["nombre"];
                idimagen = data[i]["id"];
                imagen = '<img src="http://appss.misiva.com.ec/videos/' + nombreimagen + '" class="imagen-galeria img-responsive">';
                link = '<div class="col-md-4 col-sm-4 col-xs-6 itemgale"><a href="' + accion + controladorApp + '/video/' + nombrevideo + '/' + idimagen + '" data-title="' + titulovideo+'" data-toggle="lightbox" data-parent="" data-gallery="remoteload">' + imagen + '</a></div>';
                divimagen = divimagen + link;
            }
        }
        // pegamos item
        if (j == 0) primero = "active";
        else
            primero = "";
        htmlGaleria =  htmlGaleria + '<div class="item ' + primero + '">' + divimagen + '</div> ';
    }
    $("#galeria-imagenes").html(cabeceraGaleria + htmlGaleria +  finGaleria);
};

function grabarBaseDatosVideo(filenameOriginal, filenameimagen) {
    $.post(accion + controladorApp + "/grabavideo", {
        filename: filenameOriginal,
        filenameimage: filenameimagen,
        id_user: 2000,
        fbid: idParticipante,
        nombre: nombreParticipante + ', '+ nombrevideoinput
    })
}

function showRecord() {
    $("#recordStartButton").attr("disabled", false);
}

function startRecording() {
    $("#recordStartButton").attr("disabled", true);
    $("#recordStopButton").attr("disabled", false);
    $("#recordPauseResumeButton").attr("disabled", false);
    $.scriptcam.startRecording();
}
function closeCamera() {



    $.scriptcam.closeCamera();
    $('#loadergif').removeClass('hidden').show();
    $('#message').html('Un momento conversión del vídeo en proceso...');
}
function pauseResumeCamera() {
    if ($("#recordPauseResumeButton").html() == 'Pausa') {
        $("#recordPauseResumeButton").html("Continuar");
        $.scriptcam.pauseRecording();
    }
    else {
        $("#recordPauseResumeButton").html("Pausa");
        $.scriptcam.resumeRecording();
    }
}
// cuando termina de convertir el archivo
var fileNameSolo = "";
var fileName = "";
function fileReady(fileName) {
    //$('#recorder').hide();
    $('#mediaplayer-container').removeClass("hidden").show();
    $('#loadergif').removeClass("hidden").show();
    $('#loadergif').hide();
    $('#webcam-container').hide();

    var filenameOriginal = fileName;
    fileName = fileName.replace("http://europe.www.scriptcam.com/dwnld/", "http://appss.misiva.com.ec/videos/");
    fileName = fileName.replace("http://usa.www.scriptcam.com/dwnld/", "http://appss.misiva.com.ec/videos/");

    $('#message').html('');

    var fileNameNoExtension = fileName.replace(".mov", "gif");
    var fileNameNoExtension = fileName.replace(".mp4", "gif");
    var fileNameNoExtension = fileName.replace(".mpg", "gif");
    var fileNameNoExtension = fileName.replace(".MOV", "gif");
    var fileNameNoExtension = fileName.replace(".MP4", "gif");
    var fileNameNoExtension = fileName.replace(".MPG", "gif");

    fileNameSolo = fileNameNoExtension.replace("http://appss.misiva.com.ec/videos/", "");

    muestraJwplayer('mediaplayer', fileName, fileNameNoExtension);

}

function muestraJwplayer(divContent, filename, fileNameNoExtension) {
    jwplayer(divContent).setup({
        width: 480,
        height: 360,
        file: filename,
        image: fileNameNoExtension
    });
    $('#' + divContent).show();
}

function onError(errorId, errorMsg) {
    alert(errorMsg);
}

function onWebcamReady(cameraNames, camera, microphoneNames, microphone, volume) {
    $.each(cameraNames, function (index, text) {
        $('#cameraNames').append($('<option></option>').val(index).html(text))
    });
    $('#cameraNames').val(camera);
    $.each(microphoneNames, function (index, text) {
        $('#microphoneNames').append($('<option></option>').val(index).html(text))
    });
    $('#microphoneNames').val(microphone);
}

function promptWillShow() {
    alert('Se mostrará un cuadro de diálogo de seguridad. Por favor, haga clic en PERMITIR.');
}

function timeLeft(value) {
    $('#message').html("Grabando " + value + " seg.");
}
function changeCamera() {
    $.scriptcam.changeCamera($('#cameraNames').val());
}
function changeMicrophone() {
    $.scriptcam.changeMicrophone($('#microphoneNames').val());
}
//fin funciones scriptcam