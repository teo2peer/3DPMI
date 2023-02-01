@extends('adminlte::page')

@section('title', 'Dashboard - Manage users')

@section('content_header')
<h1>Impresora</h1>
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
<form method="post" action="{{url('dashboard/impresoras/add')}}">
    @csrf

    <div class="form-group">
        <label for="name">Nombre</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="ip">IP</label>
        <input type="text" class="form-control" id="ip" name="ip" required value="none">
    </div>
    <div class="form-group">
        <label for="port">Puerto</label>
        <input type="text" class="form-control" id="port" name="port" required value="none">
    </div>
    <div class="form-group">
        <label for="type">Tipo</label>
        <select name="type" class="form-control" id="type" required>
            <option value="FDM">FDM</option>
            <option value="SLA">SLA</option>
            <option value="DLP">DLP</option>
            <option value="SLS">SLS</option>
            <option value="FFF">FFF</option>
            <option value="FFF">Resina</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>

</form>


{{-- filamentos table bootstrap--}}

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Impresoras</h3>

    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table">
            <thead>

                <tr>
                    <th style="width: 10px">#</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($impresoras as $impresora)
                <tr>
                    <td>{{$impresora->id}}</td>
                    <td>{{$impresora->name}}</td>
                    <td>{{$impresora->type}}</td>
                    <td>
                        {{-- if impresora estado == 1, is enabled, else is disabled --}}
                        @if ($impresora->estado == 1)
                        <a href="{{url('dashboard/impresoras/alter/'.$impresora->id)}}"
                            class="btn btn-warning">Deshabilitar</a>
                        @else
                        <a href="{{url('dashboard/impresoras/alter/'.$impresora->id)}}"
                            class="btn btn-success">Habilitar</a>
                        @endif
                        <a href="/dashboard/incidencias?impresora={{$impresora->id}}"
                            class="btn btn-info">Incidencia</a>
                        <a href="{{url('dashboard/impresoras/delete/'.$impresora->id)}}"
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