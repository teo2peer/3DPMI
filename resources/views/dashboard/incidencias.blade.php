@extends('adminlte::page')

@section('title', 'Dashboard - Manage users')

@section('content_header')
<h1>Incidencias</h1>
@stop

@section('content')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>

@php
if(isset($_GET['impresora'])){
$incidencia_impresora = $_GET['impresora'];
}else{
$incidencia_impresora = null;
}
@endphp


{{-- form to add a task. inputs: 'nombre',
'descripcion',
'estado',
'departamento',
'fecha_limite' --}}
<form method="post" action="{{url('dashboard/addIncidencia')}}">
    @csrf

    <div class="form-group">
        <label for="name">Nombre</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>

    <div class="form-group">
        <label for="description">Descripcion</label>
        <input type="text" class="form-control" id="description" name="description" required>
    </div>



    {{-- checkbocx impresora, if click show the impresora select option--}}

    <div class="form-group">
        <label for="isImpresora">Es una incidencia de impresora?</label>
        <input type="checkbox" class="form-control float-left" id="isImpresora" name="isImpresora"
            @if($incidencia_impresora !=null ) checked @endif
            onclick="this.checked ? $('#impresoraForm').show() : $('#impresoraForm').hide(); " required>
    </div>

    <div class="form-group" id="impresoraForm"
        style="@if($incidencia_impresora!=null) display: block @else display: none @endif">
        <label for="impresora">Impresora</label>
        <select class="form-control" id="impresora" name="impresora" required>
            @foreach($impresoras as $impresora)
            <option @if($impresora->id == $incidencia_impresora) selected @endif
                value="{{$impresora->id}}">{{$impresora->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="gravedad">Gravedad</label>
        <select class="form-control" id="gravedad" name="gravedad" required>
            <option value="3">Baja</option>
            <option value="2">Media</option>
            <option value="1">Alta</option>
        </select>
    </div>


    <button type="submit" class="btn btn-primary">Submit</button>

</form>


{{-- filamentos table bootstrap--}}

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tus filamentos</h3>

    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table">
            <thead>

                <tr>
                    <th style="width: 10px">#</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Estado</th>
                    <th>Gravedad</th>

                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>


@stop
@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script src="/assets/dashboard/admin/manageUsers.js"></script>
@stop