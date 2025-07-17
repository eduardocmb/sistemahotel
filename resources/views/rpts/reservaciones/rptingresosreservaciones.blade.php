@extends('adminlte::page')

@section('title', 'Ingresos de Reservaciones')

@section('content_header')
    <h1>Ingresos de Reservaciones</h1>
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
                    <h3 class="card-title">Ingresos de Reservaciones</h3>
                    <button id="btnImprimir" class="btn mx-3 btn-sm btn-primary">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <table id="tablaIngresosReservaciones"
                                class="table table-striped table-bordered dt-responsive nowrap" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>N. Reservación</th>
                                        <th>Fecha</th>
                                        <th>Huesped</th>
                                        <th>Isv 15%</th>
                                        <th>Isv 18%</th>
                                        <th>Descto</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí se cargarán los datos de las reservaciones -->
                                </tbody>
                            </table>
                        </div>
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
            $(function() {
                $('#tablaIngresosReservaciones').DataTable({
                    responsive: true,
                    autoWidth: false,
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                    },
                    ajax: "{{ route('datatables.getIngresosReservaciones') }}",
                    columns: [{
                            data: "numreservacion"
                        },
                        {
                            data: "fecha"
                        },
                        {
                            data: "cliente"
                        },
                        {
                            data: "isv15"
                        },
                        {
                            data: "isv18"
                        },
                        {
                            data:"descto"
                        },
                        {
                            data:"total"
                        }
                    ]
                });
            });
        </script>
        <script>
            document.getElementById('btnImprimir').addEventListener('click', function() {
                var enlace = document.createElement('a');
                enlace.href = '{{route("imprimirReservacionesIngresos")}}';
                enlace.target = '_blank';
                document.body.appendChild(enlace);
                enlace.click();
                document.body.removeChild(enlace);
            });
        </script>
    @stop
