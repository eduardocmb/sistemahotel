@extends('adminlte::page')

@section('title', 'Clientes mas habituales')

@section('content_header')
    <h1>Clientes mas habituales</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('nopermiso'))
        <div class="alert alert-danger" role="alert">
            {{ session('nopermiso') }}
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Clientes mas habituales</h3>
                </div>
                <div class="card-body">
                    <button id="btnImprimir" class="btn my-1 btn-sm btn-primary">
                        <i class="fas fa-print"></i> Imprimir
                    </button>

                    <table id="tablaReservacionesXCliente" class="table table-striped table-bordered dt-responsive nowrap"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Cant. de Reservaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>
    <script>
        document.getElementById('btnImprimir').addEventListener('click', function() {
            var enlace = document.createElement('a');
            enlace.href = `{{ route('rpt.imprimirHuespedesFrecuentes') }}`
            enlace.target = '_blank';
            document.body.appendChild(enlace);
            enlace.click();
            document.body.removeChild(enlace);
        });
        const tableId = '#tablaReservacionesXCliente';
        $(tableId).DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
            },
            ajax: {
                url: `{{ route('datatables.getHuespedesfrecuentes') }}`
            },
            columns: [{
                    data: "codigo_cliente"
                },
                {
                    data: "nombre_completo"
                },
                {
                    data: "total_reservaciones"
                }
            ]
        });
    </script>
@stop
