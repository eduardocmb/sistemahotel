@extends('adminlte::page')

@section('title', 'Gestión de Habitaciones')

@section('content_header')
    <h1>Gestión de Habitaciones</h1>
    <a class="my-2 btn btn-primary btn-sm" href="{{ route('habitaciones.create') }}">
        <i class="fas fa-bed"></i> Agregar Nueva Habitación
    </a>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Listado de Habitaciones</h3>
                </div>
                <div class="card-body">
                    <table id="tablaHabitaciones" class="table table-bordered table-striped dt-responsive nowrap"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Número de Habitación</th>
                                <th>Tipo de Habitación</th>
                                <th>Precio Diario</th>
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
        $(function() {
            $('#tablaHabitaciones').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
                ajax: "{{ route('datatable.getHabitaciones') }}",
                columns: [{
                        data: "id"
                    },
                    {
                        data: "numero_habitacion"
                    },
                    {
                        data: "tipo_habitacion"
                    },
                    {
                        data: "precio_diario"
                    },
                    {
                        data: "estado"
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let editUrl = "{{ route('habitaciones.edit', ':id') }}".replace(':id',
                                row.id);
                            let deleteUrl = "{{ route('habitaciones.show', ':id') }}".replace(':id',
                                row.id);
                            return `
                                <a href="${editUrl}" class="btn btn-info btn-sm">
                                    <i class="fas fa-sync-alt"></i> Actualizar
                                </a>
                                <a href="${deleteUrl}" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </a>
                            `;
                        }
                    }
                ]
            });
        });
    </script>
@stop
