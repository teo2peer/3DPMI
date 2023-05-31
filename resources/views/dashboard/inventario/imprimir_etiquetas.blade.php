@extends('adminlte::page')

@section('title', 'Dashboard - Manage users')

@section('content_header')
<h1>Imprimir etiquetas</h1>
@stop

@section('content')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>



{{-- filamentos table bootstrap--}}
<br>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Zonas</h3>

    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table">
            <thead>

                <tr>
                    <th style="width: 10px">#</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Acciones (min -> max)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($zonas as $zona)
                <tr>
                    <td>{{$zona->id}}</td>
                    <td>{{$zona->name}}</td>
                    <td>{{$zona->descripcion}}</td>
                    <td>

                        {{-- small --}}
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default"
                            onclick="printBarcode({{$zona->id}}, 24, 1)">
                            <i class="fas fa-print"></i>
                        </button>
                        {{-- small-medium --}}
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-default"
                            onclick="printBarcode({{$zona->id}}, 30, 1.5)">
                            <i class="fas fa-print"></i>
                        </button>
                        {{-- medium --}}
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-default"
                            onclick="printBarcode({{$zona->id}}, 40, 1.7)">
                            <i class="fas fa-print"></i>
                        </button>
                        {{-- medium-large --}}
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-default"
                            onclick="printBarcode({{$zona->id}}, 50, 2)">
                            <i class="fas fa-print"></i>
                        </button>
                        {{-- large --}}
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-default"
                            onclick="printBarcode({{$zona->id}}, 72, 3)">
                            <i class="fas fa-print"></i>
                        </button>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>

<div style="display:none" id="hidden_div">
    <div id="modal_body_barcode">
        <div class="col-3">
            <h3 id="barcode_name" class="text-center"></h3>
            <svg id="barcode_code_svg"></svg>
        </div>
    </div>
</div>
<div class="row" id="hidden_div_data">

</div>

@stop
@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script src="/assets/dashboard/admin/manageUsers.js"></script>
<script src="/assets/js/JsBarcode.all.min.js"></script>
<script>
    async function printBarcode(id, height_s = 48, width_w = 2) {

        // ajax request to get the barcode
        await $.ajax({
            type: "GET",
            url: "/dashboard/zonas/subzonas/get/barcodes/" + id,
            data: {
                
            },
            success: async function (response) {
                // clear the div
                $("#hidden_div_data").html("");
                
                for(var i = 0; i < response.barcodes.length; i++){
                    // print barcode
                    await JsBarcode("#barcode_code_svg", response.barcodes[i], {
                        format: "EAN8",
                        height: height_s,
                        width: width_w,
                    });
                    $("#barcode_name").html(response.name[i]);
                    // change the id of the barcode
                    $("#barcode_code_svg").attr("id", "barcode_code_svg_" + i);
                    // append the barcode to the div
                    $("#hidden_div_data").append($("#hidden_div").html());
                    // change the id of the barcode
                    $("#barcode_code_svg_" + i).attr("id", "barcode_code_svg");
                    // sleep 0.2 seconds
                    await new Promise(r => setTimeout(r, 200));



                }

                // return;
                var innerContents = document.getElementById("hidden_div_data").innerHTML;
                var popupWindow = window.open('', 'Print');

                // print barcode into the printers
                // include bootstrap
                popupWindow.document.write('<html><head><title>Print</title>');
                popupWindow.document.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">');
                popupWindow.document.write('</head><body onload="window.print()"> <div class="container"><div class="row">' + innerContents + '  </div></div></body>  </html>');
                popupWindow.document.close();
                popupWindow.focus();
                // popupWindow.print();
                // timeout
                setTimeout(function () {
                    popupWindow.close();
                }, 100);

                    }
                });
                


        };
</script>
@stop