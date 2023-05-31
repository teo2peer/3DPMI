@extends('adminlte::page')

@section('title', 'Dashboard - Manage users')

@section('content_header')
<h1>Agregar producto al inventario</h1>
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

<form method="post" action="{{url('dashboard/inventario/add')}}">
    @csrf

    <div class="form-group">
        <label for="name">Nombre</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="description">Descripcion (opcional)</label>
        <input type="text" class="form-control" id="description" name="description">
    </div>
    {{-- cantidad --}}
    <div class="form-group">
        <label for="cantidad">Cantidad</label>
        <input type="number" class="form-control" id="cantidad" name="cantidad" required>
    </div>
    {{-- precio 2 decimals--}}
    <div class="form-group">
        <label for="precio">Precio</label>
        <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
    </div>
    {{-- <div class="form-group">
        <label for="zona_id">Zona</label>
        <select class="form-control" id="zona_id" name="zona_id" required>
            @foreach ($zonas as $zona)
            <option value="{{$zona->id}}">{{$zona->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="subzona_id">Sub Zona</label>
        <select class="form-control" id="subzona_id" name="subzona_id" required>
            <option value="a">Seleciona una zona</option>
        </select>
    </div> --}}

    <div class="form-group">
        <label for="description">Codigo de barras de subzona</label>
        <input type="text" class="form-control" id="barcode_input" name="barcode_input" required
            value="{{Session::get('barcode_subzona')?? ""}}">
    </div>

    {{-- categoria --}}
    <div class="form-group">
        <label for="categoria_id">Categoria</label>
        <select class="form-control" id="categoria_id" name="categoria_id" required>
            @foreach ($categorias as $categoria)
            <option value="{{$categoria->id}}">{{$categoria->name}}</option>
            @endforeach
        </select>
    </div>
    {{-- href to dashboard/categories --}}
    <a href="{{url('dashboard/categorias')}}">Agregar categoria</a>
    <br>
    {{-- select with zona id --}}
    <button type="submit" class="btn btn-primary">Submit</button>
</form>


{{-- filamentos table bootstrap--}}

<br>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Productos</h3>
        {{-- at left, button to imprimir, --}}

    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table">
            <thead>

                <tr>
                    <th style="width: 10px">#</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Zona</th>
                    <th>Sub Zona</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                <tr>
                    <td>{{$producto->id}}</td>
                    <td>{{$producto->name}}</td>
                    <td>{{$producto->descripcion}}</td>
                    <td>{{$producto->cantidad}}</td>
                    <td>{{$producto->precio}}</td>
                    <td>{{$producto->zona->name}}</td>
                    <td>{{$producto->subzona->name}}</td>
                    <td>
                        {{-- SHOW BARCODE MODAL --}}
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default"
                            onclick="JsBarcode('#barcode', '{{$producto->barcode}}', {format: 'EAN8'});">
                            Ver Codigo
                        </button>
                        <a href="{{url('dashboard/inventario/edit/'.$producto->id)}}" class="btn btn-primary">Editar</a>
                        <a href="{{url('dashboard/inventario/delete/'.$producto->id)}}"
                            class="btn btn-danger">Eliminar</a>
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

<script type="text/javascript">
    // save all zonas in a variable
    var zonas = {!! json_encode($zonas->toArray()) !!};
    // save all subzonas in a variable
    var subzonas = {!! json_encode($subzonas->toArray()) !!};
    // save all categorias in a variable
    var categorias = {!! json_encode($categorias->toArray()) !!};
    // when zone select change, change subzone select with the subzones of the selected zone
    $('#zona_id').change(function () {
        // get the selected zone id
        var zona_id = $(this).val();
        // get the subzones of the selected zone
        var subzonasOfZona = subzonas.filter(subzona => subzona.zona_id == zona_id);
        // clear the subzone select
        $('#subzona_id').empty();
        // add the subzones to the subzone select
        subzonasOfZona.forEach(subzona => {
            $('#subzona_id').append(`<option value="${subzona.id}">${subzona.name}</option>`);
        });
    });
    // load the subzones of the first zone
    $('#zona_id').change();

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