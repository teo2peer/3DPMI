@extends('adminlte::page')

@section('title', 'Dashboard - Manage users')

@section('content_header')
<h1>Gcodes</h1>
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
</style>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>


<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tus Gcodes</h3>

    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table">
            <thead>

                <tr>
                    <th style="width: 10px">#</th>
                    <th>Nombre</th>
                    <th>Hecho para</th>
                    <th>Tiempo</th>
                    <th>Peso</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gcodes as $gcode)
                <tr>
                    <td>{{$gcode->id}}</td>
                    <td>{{$gcode->name}}</td>
                    <td>{{$gcode->made_for}}</td>
                    <td>{{gmdate("H:i:s",$gcode->time)}}</td>
                    <td>{{$gcode->filament_used}}m ({{round($gcode->filament_used*2.98,3)}} g)</td>
                    {{-- if the id is in impresiones(gcode->impresiones), write that state else, write subido --}}
                    <td>
                        @php
                        $impresion_exist = false;
                        @endphp

                        @foreach ($impresiones as $impresion)
                        @if ($impresion->gcode_id == $gcode->id)
                        @if ($impresion->estado == 0)
                        <span class="badge bg-primary">No iniciada</span>
                        @elseif($impresion->estado == 1)
                        <span class="badge bg-warning">Imprimiendo</span>
                        @elseif($impresion->estado == 2)
                        <span class="badge bg-success">Finalizada</span>
                        @elseif($impresion->estado == 3)
                        <span class="badge bg-danger">Error</span>
                        @endif
                        Puesto por: {{explode(" ", $impresion->puestos_por->name)[0]}}
                        @php
                        $impresion_exist = true;
                        @endphp

                        @endif
                        @endforeach
                        @if ($impresion_exist == false)
                        <span class="badge bg-secondary">Subido</span>
                        @endif


                    </td>


                    <td>
                        @if ($impresion_exist == false)
                        <a href="#" onclick="preparePrint('{{$gcode->id}}', '{{$gcode->name}}')"
                            class="btn btn-success">Imprimir</a>
                        @else
                        <a href="#" onclick="preparePrint('{{$gcode->id}}', '{{$gcode->name}}')" class="btn btn-success"
                            disabled>Imprimir</a>
                        @endif
                        {{-- download gcode open an new window --}}
                        <a href="{{url(str_replace('/var/www/hcs/public/', '/', $gcode->path))}}"
                            class="btn btn-primary" download>Descargar Gcode</a>
                        <a href="/dashboard/user/gcodes/delete/{{$gcode->id}}" class="btn btn-danger">Eliminar</a>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>

<div id="modal" style="display:none">
    <div id="modal_content">
        <div id="modal1">
            <h1>Pon tu impresion!</h1>
            <div class="form-group" hidden>
                <label for="name">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Descripcion [Porque quieres imprimirlo]</label>
                <input type="text" class="form-control" id="description" name="description" required>
            </div>
            <div class="form-group">
                <label for="impresora">Impresora</label>
                <select name="impresora" class="form-control" id="impresora" required>
                    @foreach ($impresoras as $impresora)
                    <option value="{{$impresora->id}}" @if($impresora->estado != 1) disabled @endif
                        >{{$impresora->name}} @if($impresora->estado == 0) | Fuera de sericio @elseif($impresora->estado
                        == 2)
                        | En uso @endif</option>

                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="filamento">Filamento</label>
                <select name="filamento" class="form-control" id="filamento" required>
                    @foreach ($filamentos as $filamento)
                    <option value="{{$filamento->id}}">{{$filamento->name}} | Disponible: {{$filamento->available}}m
                        ({{$filamento->available*2.98}}g)
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group  is-invalid " hidden>
                <input type="number" name="gcode" id="gcode" accept=".gcode" required />
            </div>



            <div class="alert alert-danger" role="alert" id="errorDiv" style="display: none">
                <h3 class="alert-heading">{{$error??""}}</h3>
            </div>

            <button type="submit" class="btn btn-primary" id="submit">Pon a imprimir</button>
            <a href="#" title="Close" id="close">✖</a>


        </div>
        <div id="modal2" style="display: none">
        </div>


    </div>
</div>

@stop
@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script src="/assets/dashboard/admin/manageUsers.js"></script>
<script type="text/javascript">
    function preparePrint(id, name) {
        $("#name").val(name);
        $("#gcode").val(id);
        $("#modal1").show();
        $("#modal2").hide();
        $("#modal").fadeIn();

    }
    $("#close").click(function() {
        $("#modal").fadeOut();
    });

    $("#submit").click(function() {
        // loafing animation
        $("#modal2").html('<div class="d-flex justify-content-center mt-4"><div class="spinner-border mt-4" role="status"><span class="sr-only">Procesando...</span></div> </div><h2 class="text-center mt-2">Procesando...</h2>');
        $("#modal1").hide();
        $("#modal2").show();

        var description = $("#description").val();
        var impresora = $("#impresora").val();
        var filamento = $("#filamento").val();
        var gcode = $("#gcode").val();
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            url: "/dashboard/impresion/crear",
            type: 'POST',
            data: {
                description: description,
                impresora: impresora,
                filamento: filamento,
                gcode: gcode,
                _token: token
            },
            success: function(response) {
                if (response == 200) {
                    // success animation icon and text
                    $("#modal2").html('<div class="d-flex justify-content-center mt-4"><i class="fas fa-check-circle fa-5x text-success"></i></div><h2 class="text-center mt-2">Impresion puesta correctamente</h2>             <a href="#" title="Close" onclick=\'window.location.reload()\'">✖</a>');
                } else if (response == 400) {
                    // error animation icon and text
                    $("#modal2").html('<div class="d-flex justify-content-center mt-4"><i class="fas fa-exclamation-triangle fa-5x text-danger"></i></div><h2 class="text-center mt-2">Un error desconocido ha pasado</h2>             <a href="#" title="Close" onclick=\'$(\"#modal\").fadeOut();\'">✖</a>');
                } else {
                    $("#modal2").html('<div class="d-flex justify-content-center mt-4"><i class="fas fa-exclamation-triangle fa-5x text-danger"></i></div><h2 class="text-center mt-2">Un error desconocido ha pasado</h2>             <a href="#" title="Close" onclick=\'$(\"#modal\").fadeOut();\'">✖</a>');

                }
            },error: function(response){
                // error animation icon and text
                $("#modal2").html('<div class="d-flex justify-content-center mt-4"><i class="fas fa-exclamation-triangle fa-5x text-danger"></i></div><h2 class="text-center mt-2">Un error desconocido ha pasado</h2>             <a href="#" title="Close" onclick=\'$(\"#modal\").fadeOut();\'">✖</a>');
            }
        });
    });

</script>
@stop