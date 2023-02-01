@extends('adminlte::page')

@section('title', 'Dashboard - Gestion de usuarios')

@section('content_header')
    <h1>Gestion de {{ $user->name }}</h1>
@stop

@section('content')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}" />

    </head>




    {{-- filamentos table bootstrap --}}

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filamentos de {{ $user->name }}</h3>

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
                    @foreach ($user->filamentos as $filamento)
                        <tr>
                            <td>{{ $filamento->id }}</td>
                            <td>{{ $filamento->name }}</td>
                            <td>
                                <a href="{{ url('dashboard/deleteFilamento/' . $filamento->id) }}"
                                    class="btn btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Impresiones de {{ $user->name }}</h3>

        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table">
                <thead>

                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Nombre</th>
                        <th>Filamento</th>
                        <th>Estado</th>
                        <th>Impresion</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($impresiones as $impresion)
                        <tr>
                            <td>{{ $impresion->id }}</td>
                            <td>{{ $impresion->name }}</td>
                            <td>{{ $impresion->filamentos->name }}</td>
                            <td>{{ $impresion->impresoras->name }}</td>
                            <td>
                                @if ($impresion->estado == 0)
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

                                @if ($impresion->estado == 0)
                                    <a href="{{ url('dashboard/startImpresion/' . $impresion->id) }}"
                                        class="btn btn-success">Iniciar</a>
                                @elseif($impresion->estado == 1)
                                    <a href="{{ url('dashboard/finishImpresion/' . $impresion->id) }}"
                                        class="btn btn-success">Finalizar</a>
                                @endif

                                <a href="{{ url('dashboard/deleteImpresion/' . $impresion->id) }}"
                                    class="btn btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@stop
@section('css')
        <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
        <script src="/assets/dashboard/admin/manageUsers.js"></script>
@stop
