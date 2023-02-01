@extends('adminlte::page')

@section('title', 'Dashboard - Manage users')

@section('content_header')
<h1>Impresiones</h1>
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


{{-- filamentos table bootstrap--}}

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Todas las Impresiones</h3>

    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table">
            <thead>

                <tr>
                    <th style="width: 10px">#</th>
                    <th>De</th>
                    <th>Nombre</th>
                    <th>Filamento</th>
                    <th>Impresora</th>
                    <th>Filamento usado</th>
                    <th>Dura</th>
                    <th>Finaliza</th>
                    <th>Estado</th>
                    <th>Puesto por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($impresiones as $impresion)
                <tr>
                    <td>{{$impresion->id}}</td>
                    <td>{{$impresion->user->name}}</td>
                    <td>{{substr($impresion->name,0,25)}}</td>
                    <td>{{$impresion->filamentos->name}}</td>
                    <td>{{$impresion->impresoras->name}}</td>
                    <td>{{$impresion->gcode->filament_used}}m ({{round($impresion->gcode->filament_used*2.98,3)}} g)
                    </td>
                    <td>{{gmdate("H:i:s",$impresion->gcode->time)}} </td>
                    <td>{{$impresion->created_at->addSeconds($impresion->gcode->time)}}</td>


                    <td>@if ($impresion->estado == 0)
                        <span class="badge bg-primary">No iniciada</span>
                        @elseif($impresion->estado == 1)
                        <span class="badge bg-warning">Imprimiendo</span>
                        @elseif($impresion->estado == 2)
                        <span class="badge bg-success">Finalizada</span>
                        @elseif($impresion->estado == 3)
                        <span class="badge bg-danger">Error</span>
                        @endif
                    </td>
                    <td>{{explode(" ", $impresion->puestos_por->name)[0]}}</td>
                    <td>
                        {{-- estado 0 = not started, 1 = started 2 = finished, 3=error --}}



                        {{-- if state = 0 start impresion, 1 finish impresion --}}
                        @if ($impresion->estado == 0)
                        <a href="{{url('dashboard/startImpresion/'.$impresion->id)}}"
                            class="btn btn-success">Iniciar</a>
                        @elseif($impresion->estado == 1)
                        <a href="{{url('dashboard/finishImpresion/'.$impresion->id)}}"
                            class="btn btn-success">Finalizar</a>
                        <a href="{{url('dashboard/finishImpresionError/'.$impresion->id)}}"
                            class="btn btn-danger">Finalizar
                            con error</a>
                        @endif

                        {{-- download gcode open an new window --}}
                        <a href="{{url(str_replace('/var/www/hcs/public/', '/', $impresion->gcode->name))}}"
                            class="btn btn-primary" download>Descargar Gcode</a>

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