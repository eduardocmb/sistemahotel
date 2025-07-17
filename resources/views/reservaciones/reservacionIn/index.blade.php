@extends('adminlte::page')

@section('title', 'Gestión de Reservas')

@section('content_header')
    <h1>Gestión de Reservas</h1>
    <div class="row">
        <div class="col-3"><a class="my-2 btn btn-primary btm-sm" href="{{ route('reservaciones.createReservacionIn') }}">
                <i class="fas fa-calendar-plus"></i> Agregar Nueva Reservación
            </a></div>
    </div>
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
                    <h3 class="card-title">Listado de Reservas</h3>

                </div>
                <div class="card-body">
                    <table id="tablaReservas" class="table table-bordered table-striped dt-responsive nowrap"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th>NÚMERO</th>
                                <th>Cliente</th>
                                <th>Habitación</th>
                                <th>Fecha de Entrada</th>
                                <th>Fecha de Salida</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
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
        var editUrlTemplate = "{{ route('reservaciones.edit', ['reservacione' => '__id__']) }}";
        var deleteUrlTemplate = "{{ route('reservaciones.show', ['reservacione' => '__id__']) }}";

        $(function() {
            $('#tablaReservas').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
                ajax: "{{ route('datatable.getReservaciones') }}",
                columns: [{
                        data: "numero"
                    },
                    {
                        data: "cliente",
                        render: function(data, type, row) {
                            return data.length > 15 ? data.substring(0, 15) + "..." : data;
                        }
                    },
                    {
                        data: "habitacion"
                    },
                    {
                        data: "fecha_entrada"
                    },
                    {
                        data: "salida"
                    },
                    {
                        data: "estado"
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let editUrl = "{{ route('reservaciones.editin', ':id') }}".replace(':id',
                                row.id);
                            let deleteUrl = "{{ route('reservacione.show', ':id') }}".replace(':id',
                                row.id);
                                let deleteButton = row.estado == 'PENDIENTE' ? ` <a href="${deleteUrl}" class="btn btn-danger btn-sm">
                <i class="fas fa-trash-alt"></i> Eliminar
            </a>`:``;
                            return `
            <a href="${editUrl}" class="btn btn-info btn-sm">
                <i class="fas fa-sync-alt"></i> Actualizar
            </a>
        `+deleteButton;
                        }
                    }
                ]
            });
        });
    </script>
    @if (session('reserv_estado'))
        <script>
            $('#modalImprimirFactura').modal('show');
        </script>
    @endif
@stop
<div class="modal fade" id="modalImprimirFactura" tabindex="-1" role="dialog"
    aria-labelledby="modalImprimirFacturaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalImprimirFacturaLabel">Imprimir Factura</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if (session('reserv'))
                    ¿Desea imprimir la factura de la reservación: <strong>{{ session('reserv') }}</strong>?
                @endif
            </div>
            <div class="modal-footer">
                <a href="{{ route('rptReservacionIn', session('reserv') ?? '') }}" class="btn btn-primary">
                    <i class="fas fa-print"></i> Imprimir
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tal vez luego
                </button>
            </div>
        </div>
    </div>
</div>
