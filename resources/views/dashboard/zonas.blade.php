@extends('adminlte::page')

@section('title', 'Dashboard - Manage users')

@section('content_header')
<h1>Filamentos</h1>
@stop

@section('content')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>


{{-- form to add a task. inputs: 'nombre',
'descripcion',
'estado',
'departamento',
'fecha_limite' --}}
<h2>Agregar Zona</h2>
<form method="post" action="{{url('dashboard/addZonas')}}">
    @csrf

    <div class="form-group">
        <label for="name">Nombre</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="description">Descripcion</label>
        <input type="text" class="form-control" id="description" name="description" required>
    </div>


    <button type="submit" class="btn btn-primary">Submit</button>

</form>

<h2>Agregar Sub Zona</h2>
<form method="post" action="{{url('dashboard/addSubZona')}}">
    @csrf
    <div class="form-group">
        <label for="zona_id">Zona</label>
        <select class="form-control" id="zona_id" name="zona_id" required>
            @foreach ($zonas as $zona)
            <option value="{{$zona->id}}">{{$zona->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="name">Nombre</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="description">Descripcion</label>
        <input type="text" class="form-control" id="description" name="description" required>
    </div>
    {{-- select with zona id --}}
    <button type="submit" class="btn btn-primary">Submit</button>
</form>


{{-- filamentos table bootstrap--}}
<br>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Zonas</h3>

    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table">
            <thead>

                <tr>
                    <th style="width: 10px">#</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($zonas as $zona)
                <tr>
                    <td>{{$zona->id}}</td>
                    <td>{{$zona->name}}</td>
                    <td>{{$zona->descripcion}}</td>
                    <td>
                        <a href="{{url('dashboard/deleteZona/'.$zona->id)}}" class="btn btn-danger">Eliminar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<br>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sub Zonas</h3>

    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table">
            <thead>

                <tr>
                    <th style="width: 10px">#</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Zona</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subzonas as $subzona)
                <tr>
                    <td>{{$subzona->id}}</td>
                    <td>{{$subzona->name}}</td>
                    <td>{{$subzona->descripcion}}</td>
                    <td>{{$subzona->zona->name}}</td>

                    <td>
                        {{-- SHOW BARCODE MODAL --}}
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default"
                            onclick="JsBarcode('#barcode', '{{$subzona->barcode}}', {format: 'ITF14'});">
                            Ver Codigo
                        </button>
                        {{-- DELETE --}}
                        <a href="{{url('dashboard/deleteSubZona/'.$subzona->id)}}" class="btn btn-danger">Eliminar</a>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>

{{-- MODAL TO SHOW BARCODE --}}
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Codigo de Barra</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modal_body_barcode" class="modal-body" style="margin: auto; display:block; text-align:center">
                <svg id="barcode"></svg>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                {{-- print barcode --}}
                <button type="button" class="btn btn-primary" onclick="printBarcode()">Imprimir</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


@stop
@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script src="/assets/dashboard/admin/manageUsers.js"></script>
<script src="/assets/js/JsBarcode.all.min.js"></script>
<script>
    function printBarcode() {

        var innerContents = document.getElementById("modal_body_barcode").innerHTML;
        var popupWindow = window.open('', 'Print');

        // print barcode into the printers
        popupWindow.document.write('<html><head><title>Print</title></head><body onload="window.print()">' + innerContents + '  </body>  </html>');
        popupWindow.document.close();
        popupWindow.focus();
        popupWindow.print();
        // timeout
        setTimeout(function () {
            popupWindow.close();
        }, 10);


        };
</script>
@stop