@extends('adminlte::page')

@section('title', 'Dashboard - Manage users')

@section('content_header')
<h1>Autenticacion</h1>
@stop

@section('content')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>

{{-- add in the middle of the screen the image /assets/img/nfc_read_icon.png with the text Acerca tu tarjeta al
lector--}}


<div class="row" id="mainWrap" onclick="startReader()">
    <div class="col-md-12 flex-center text-center">
        <img id="rfidimg" src="/assets/img/nfc_read_icon.png" alt="nfc_read_icon" width="200" height="200">
        <h3 id="info"> Haz click para activar el lector</h3>
    </div>
</div>




@stop
@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script src="/assets/dashboard/admin/manageUsers.js"></script>
<script src="/assets/js/serialController.js"></script>
@stop