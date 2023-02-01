@extends('adminlte::page')

@section('title', 'Dashboard - Manage users')

@section('content_header')
<h1>Hola de nuevo</h1>
@stop

@section('content')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>

<div class="col-12">

    <div class="card card-widget widget-user-2">

        <div class="widget-user-header " style="background-color:black; color:white">
            <div class="widget-user-image">
                <img class="img-circle elevation-2" src="/assets/img/logo.png" alt="User Avatar">
            </div>

            <h3 class="widget-user-username">{{ Auth::user()->name }}</h3>
            <h5 class="widget-user-desc">{{ Auth::user()->email ?? 'No asignado' }}
            </h5>
        </div>
        <div class="card-footer p-0">
            <ul class="nav flex-column">
                @foreach($impresiones as $impresion)
                <li class="nav-item">
                    <a href="#" class="nav-link">

                        {{$impresion->name}}  
                        @if($impresion->puestos_por->id!=1)
                        &nbsp;	&nbsp; | 	&nbsp;	&nbsp; 	Puesto por: {{ $impresion->puestos_por->name}}
                        @endif
                            
        
                        
                        @if ($impresion->estado == 0)
                        <span class="badge bg-primary float-right">No iniciada</span>
                        @elseif($impresion->estado == 1)
                        <span class="badge bg-warning float-right">En proceso</span>
                        @elseif($impresion->estado == 2)
                        <span class="badge bg-success float-right">Finalizada</span>
                        @elseif($impresion->estado == 3)
                        <span class="badge bg-danger float-right">Error</span>
                        @endif

                    </a>
                </li>
                @endforeach

            </ul>
        </div>
    </div>

</div>
@stop
@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script src="/assets/dashboard/admin/manageUsers.js"></script>
@stop