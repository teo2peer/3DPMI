@extends('adminlte::page')

@section('title', 'Dashboard - Manage users')

@section('content_header')
<h1>Buscar objeto</h1>
@stop

@section('content')


<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    {{-- include assets/css/modal.css --}}
    <link rel="stylesheet" href="/assets/css/modal.css">
    {{-- include assets/css/manageUsers.css --}}
</head>


{{-- form to add a task. inputs: 'nombre',
'descripcion',
'estado',
'departamento',
'fecha_limite' --}}



<div id="reader" width="600px"></div>

<h1 id="status_h1" style="display: none">Buscando...</h1>

<!-- Modal Fullscreen -->
<div class="modal fade modal-fullscreen" id="modal-data-found" tabindex="-1" role="dialog" aria-labelledby="data-found"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Contenido del <i id="modal_subzona"></i> de la zona <i
                        id="modal_zona"></i> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Precio x unidad</th>
                            {{-- <th>Fecha limite</th> --}}
                        </tr>
                    </thead>
                    <tbody id="modal_table_body">

                    </tbody>
                </table>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>


@stop
@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script type="text/javascript">
    function onScanSuccess(decodedText, decodedResult) {
        // stop scanning
        html5QrcodeScanner.clear();

        // status_h1
        $('#status_h1').show();

        // ajax request to dashboard/buscador/get with the _token and the decodedText
        // if the response status is 200, add all items to the modal in modal_table_body
        // if the response status is 404, show a modal with the message "No se ha encontrado ningun objeto con ese codigo"
        // alert("Barcode value is " + decodedText + ",\n");
        $.ajax({
            url: '/dashboard/buscador/get',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                barcode: decodedText
            },
            success: function (response) {
                // handle success
                console.log(response);
                if (response.status == 200) {
                    // alert("Barcode value is " + decodedText + ",\n");

                    // add all items to the modal in modal_table_body
                    $('#modal_table_body').empty();
                    response.productos.forEach((item, index) => {
                        $('#modal_table_body').append(`
                        <tr>
                            <td>${item.id}</td>
                            <td>${item.name}</td>
                            <td>${item.cantidad}</td>
                            <td>${item.precio}</td>
                        </tr>
                        `);
                    });
                    // update the modal title
                    $('#modal_zona').text(response.zona);
                    $('#modal_subzona').text(response.subzona);

                        
                        $('#modal-data-found').modal('show');
                        $('#modal-data-found').show();
                    
                } else if (response.status == 404) {
                    // show a modal with the message "No se ha encontrado ningun objeto con ese codigo"
                    $('#modal_table_body').empty();
                    $('#modal_table_body').append(`
                    <tr>
                        <td colspan="5">No se ha encontrado ningun objeto con ese codigo</td>
                    </tr>
                    `);
                    $('#modal-data-found').modal('show');
                }
            },
            error: function (response) {
                // handle error
                $('#modal_table_body').empty();
                    $('#modal_table_body').append(`
                    <tr>
                        <td colspan="5">No se ha encontrado ningun objeto con ese codigo(error fetch)</td>
                    </tr>
                    `);
                    $('#modal-data-found').modal('show');
                    
            }
        });
        
        
        
// when modal is closed, start scanning again
$('#modal-data-found').on('hidden.bs.modal', function () {
    $('#status_h1').hide();
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
});
        
}


function onScanFailure(error) {
}

let html5QrcodeScanner = new Html5QrcodeScanner(
  "reader",
  { fps: 10 },
  /* verbose= */ false);

html5QrcodeScanner.render(onScanSuccess, onScanFailure);

</script>
@stop