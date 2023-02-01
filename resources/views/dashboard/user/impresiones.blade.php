@extends('adminlte::page')

@section('title', 'Dashboard - Manage users')

@section('content_header')
<h1>Impresiones</h1>
@stop

@section('content')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>



{{-- filamentos table bootstrap--}}

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tus impresiones</h3>

    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table">
            <thead>

                <tr>
                    <th style="width: 10px">#</th>
                    <th>Nombre</th>
                    <th>Filamento</th>
                    <th>Impresora</th>
                    <th>Iniciado</th>
                    <th>Tiempo Estimado</th>
                    <th>Finaliza a las</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($impresiones as $impresion)
                <tr>
                    <td>{{$impresion->id}}</td>
                    <td>{{ substr($impresion->name,0,25)}}</td>
                    <td>{{$impresion->filamentos->name}}</td>
                    <td>{{$impresion->impresoras->name}}</td>
                    <td>{{$impresion->created_at}}</td>
                    <td>{{gmdate("H:i:s", $impresion->gcode->time)}}</td>
                    {{-- add to created_at the time --}}
                    <td>{{$impresion->created_at->addSeconds($impresion->gcode->time)}}</td>
                    <td>@if ($impresion->estado == 0)
                        <span class="badge bg-primary">No iniciada</span>
                        @elseif($impresion->estado == 1)
                        <span class="badge bg-warning">En proceso</span>
                        @elseif($impresion->estado == 2)
                        <span class="badge bg-success">Finalizada</span>
                        @elseif($impresion->estado == 3)
                        <span class="badge bg-danger">Error</span>
                        @endif
                    </td>
                    <td>
                        {{-- estado 0 = not started, 1 = started 2 = finished, 3=error --}}



                        {{-- if state = 0 start impresion, 1 finish impresion --}}
                        @if ($impresion->estado == 0)
                        <a href="{{url('dashboard/startImpresion/'.$impresion->id)}}"
                            class="btn btn-success">Iniciar</a>
                        @elseif($impresion->estado == 1)
                        <a href="{{url('dashboard/finishImpresion/'.$impresion->id)}}"
                            class="btn btn-success">Finalizar</a>
                        @endif

                        <a href="{{url('dashboard/deleteImpresion/'.$impresion->id)}}"
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