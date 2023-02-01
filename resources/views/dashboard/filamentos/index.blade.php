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
<form method="post" action="{{url('dashboard/filamentos/add')}}">
    @csrf

    <div class="form-group">
        <label for="name">Nombre</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="type">Tipo</label>
        <select name="type" class="form-control" id="type" required>
            <option value="PLA">PLA</option>
            <option value="PLA">PLA-RE</option>
            <option value="ABS">ABS</option>
            <option value="PETG">PETG</option>
            <option value="TPU">TPU</option>
            <option value="ASA">ASA</option>
            <option value="PC">PC</option>
            <option value="HIPS">HIPS</option>
            <option value="PVA">PVA</option>
            <option value="Nylon">Nylon</option>
            <option value="Carbon Fiber">Carbon Fiber</option>
            <option value="Wood">Wood</option>
            <option value="Metal">Metal</option>
            <option value="Flexible">Flexible</option>
            <option value="Other">Other</option>
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
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($filamentos as $filamento)
                <tr>
                    <td>{{$filamento->id}}</td>
                    <td>{{$filamento->name}}</td>
                    <td>
                        <a href="{{url('dashboard/filamentos/delete/'.$filamento->id)}}"
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