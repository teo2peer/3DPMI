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
                    <th>Email</th>
                    {{-- <th>Google ID</th> --}}
                    <th>Email verified</th>
                    <th>Rol</th>
                    <th>Departamento</th>
                    <th>Subdepartamento</th>
                    <th>Cargo</th>
                    <th>Created at</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                @foreach($users as $user)
                <tr id="user-{{ $user->id }}  onclick=(window.location.href('/dashboard/users/{{ $user->id }}')) ">
                    <td>{{$user->id}}</td>
                    <td>{{$user->name}}</td>
{{--                     <td>{{substr($user->email, 0, 5)}}xxxxxx{{substr($user->email, 8)}}</td> --}}
                    <td>{{$user->email}}</td>
                    {{-- <td>{{substr($user->google_id, 0, 5)}}xxxxxxxxxxxx{{substr($user->google_id, 20)}}</td> --}}
                    <td>{{$user->email_verified ? 'Autorizado' : 'No Autorizado'}}</td>
                    <td>{{$user->rol}}</td>

                    @if($user->departamentos != null )
                
                    <td>{{$user->departamentos->nombre}}</td>               
                    @else
                    <td>No tiene</td>
                    @endif  
                    
                    @if($user->subdepartamentos != null )
                    <td>{{$user->subdepartamentos->nombre}}</td>        
                    @else
                    <td>No tiene</td>
                    @endif  
                    
                    <td>{{$user->cargo}}</td>
                    <td>{{$user->created_at}}</td>
                    {{-- actions are based on id calling an js and are, if email_verified is true, disable, if is false
                    then enable, and delete user--}}
                    <td>
                        @if($user->email_verified)
                        <button class="btn btn-warning" onclick="disableUser({{$user->id}})" {{$user->email_verified ?
                            ''
                            : 'disabled'}}>Disable</button>
                        @else
                        <button class="btn btn-primary" onclick="enableUser({{$user->id}})" {{$user->email_verified ?
                            'disabled' : ''}}>Enable</button>
                        @endif
                        <button class="btn btn-danger" onclick="deleteUser({{$user->id}})">Delete</button>
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