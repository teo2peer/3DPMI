@extends('adminlte::page')

@section('title', 'Dashboard - Manage users')

@section('content_header')
<h1>Smart Login</h1>
@stop

@section('content')
<style>
    div#modal {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background: rgba(0, 103, 138, 0.9);
        color: white;
        padding: 50px 100px;
    }

    div#modal_content {
        position: absolute;
        width: 550px;
        left: 50%;
        top: 35%;
        margin: -130px -275px;
    }

    div#modal h1,
    div#modal h2 {

        text-shadow: 1px 1px 0px #000;
    }

    div#modal a {
        color: white;
        display: block;
        margin: auto;
        width: 50px;
        height: 50px;
        line-height: 35px;
        text-align: center;
        text-decoration: none;
        font-size: 2em;
        padding: 5px;
        border: 2px solid white;
        border-radius: 50%;
        text-shadow: 0 0 5px #333;
        box-shadow: 0 0 2px #333;
    }

    div#modal a:hover {
        color: #333;
        border: 2px solid #333;
    }

    .main-sidebar {
        display: none;
    }

    .content-wrapper {
        margin-left: 0px !important;
    }

    .main-header {
        margin-left: 0px !important;
    }
</style>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>

{{-- add in the middle of the screen the image /assets/img/nfc_read_icon.png with the text Acerca tu tarjeta al
lector--}}


<div class="row" id="mainWrap" onclick="startReader()">
    <div class="col-md-12 flex-center text-center">
        <img id="rfidimg" src="/assets/img/nfc_read_icon.png" alt="nfc_read_icon" width="200" height="200">
        <h3 id="info"> Haz click para activar el lector</h3>
    </div>
</div>
<div class="row" id="infoWarp" onclick="startReader()"" style=" display:none">

</div>




@stop
@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script src="/assets/dashboard/admin/manageUsers.js"></script>
<script type="text/javascript">
    async function startReader() {
    $("#info").html("Elige el puerto serie del lector de tarjetas");
    const port = await navigator.serial.requestPort({});
    await port.open({ baudRate: 9600 });
    const textDecoder = new TextDecoderStream();
    const readableStreamClosed = port.readable.pipeTo(textDecoder.writable);
    const reader = textDecoder.readable.getReader();
    $("#info").html("Acerca la tarjeta en el lector");
    // read the arduino serial until end of line
    while (true) {
        const { value, done } = await reader.read();
        if (done) {
            // Allow the serial port to be closed later.
            reader.releaseLock();
            break;
        }
        if (value && value.length > 7) {
            console.log(value);

            $("#mainWrap").hide();
            // loading animation icon
            $("#infoWarp").html('    <div class="col-md-12 flex-center text-center"><div class="spinner-border mt-4 text-center" role="status"><span class="sr-only text-center">Procesando...</span></div> </div><h2 class="text-center mt-2">Iniciando Sesion...</h2>');
            $("#infoWarp").show();


            // ajax call to the server
            $.ajax({
                url: "/login/smartLogin",
                type: "POST",
                data: {
                    uid: value,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    // succes icon in mainWrap
                    if(data == 200){
                    $("#infoWarp").html('    <div class="col-md-12 flex-center text-center"><i class="fas fa-check-circle fa-5x text-success"></i><h2 class="text-center mt-2">Session Iniciada, redirigiendo...</h2>   </div>       ');
                    setTimeout(function () {
                        window.location.href = "/dashboard/userGcodes";
                    }, 2000);
                }else{
                        $("#infoWarp").html('    <div class="col-md-12 flex-center text-center"><i class="fas fa-times-circle fa-5x text-danger"></i><h2 class="text-center mt-2">Error, vuelve a intentarlo</h2>             <a href="#" title="Close" onclick=\'$("#infoWarp").hide();$("#mainWrap").show();  \'">✖</a></div>');
                    }

                }, error: function (data) {
                    // error icon in mainWrap
                    $("#infoWarp").html('    <div class="col-md-12 flex-center text-center"><i class="fas fa-times-circle fa-5x text-danger"></i></div><h2 class="text-center mt-2">Error, vuelve a intentarlo</h2>             <a href="#" title="Close" onclick=\'$("#infoWarp").hide();$("#mainWrap").show();  \'">✖</a>');

                }

            });
            break;
        }
        // stop the port
    }
    reader.releaseLock();

    await port.close();


}


</script>
@stop