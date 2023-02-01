@extends('adminlte::page')

@section('title', 'Dashboard - Manage users')

@section('content_header')
<h1>Manage Users</h1>
@stop

@section('content')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Usuarios</h3>

    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table">
            <thead>

                <tr>

                    <th>ID</th>
                    <th>Name</th>
                    <th>Task</th>
                    {{-- <th>Google ID</th> --}}
                </tr>
            </thead>
            <tbody>

                @foreach($users as $user)
                @foreach($user->tasks as $task)
                <tr id="user-{{ $user->id }}">
                    <td>{{$user->id}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$task->nombre}}</td>
                    {{-- <td>{{$user->google_id}}</td> --}}
                </tr>
                @endforeach
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