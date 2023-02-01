@extends('adminlte::page')

@section('title', 'Dashboard - Manage users')

@section('content_header')
<h1>Categorias</h1>
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


<form method="post" action="{{url('dashboard/addCategorias')}}">
    @csrf

    <div class="form-group">
        <label for="name">Nombre</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="description">Descripcion</label>
        <input type="text" class="form-control" id="description" name="description" required>
    </div>
    {{-- cantidad --}}
    {{-- select with zona id --}}
    <button type="submit" class="btn btn-primary">Submit</button>
</form>


{{-- filamentos table bootstrap--}}

<br>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Categorias</h3>

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
                @foreach ($categorias as $categoria)
                <tr>
                    <td>{{$categoria->id}}</td>
                    <td>{{$categoria->name}}</td>
                    <td>{{$categoria->descripcion}}</td>

                    <td>
                        <a href="{{url('dashboard/editCategoria/'.$categoria->id)}}" class="btn btn-primary">Editar</a>
                        <a href="{{url('dashboard/deleteCategoria/'.$categoria->id)}}"
                            class="btn btn-danger">Eliminar</a>
                    </td>
                </tr>
                @endforeach
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