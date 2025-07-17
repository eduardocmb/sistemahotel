@extends('adminlte::page')

@section('title', 'Gestión de Deducciones')

@section('content_header')
    <h1>Gestión de Deducciones</h1>
    <a class="my-2 btn btn-primary btn-sm" href="{{ route('deducciones.create') }}">
        <i class="fas fa-plus-circle"></i> Agregar Nueva Deducción
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
                    <h3 class="card-title">Listado de Deducciones</h3>
                </div>
                <div class="card-body">
                    <table id="tablaDeducciones" class="table table-bordered table-striped dt-responsive nowrap"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Monto</th>
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
            $('#tablaDeducciones').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
                ajax: "{{ route('datatable.getDeducciones') }}",
                columns: [
                    {
                        data: "nombre"
                    },
                    {
                        data: "tipo",
                        render: function(data) {
                            return data === "MONTOTOTAL" ? 'MONTO TOTAL' : 'PORCENTAJE';
                        }
                    }, {
                        data: "monto"
                    },
                    {
                        data: "activo",
                        render: function(data) {
                            return data === "S" ? 'ACTIVO' : 'INACTIVO';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let editUrl = "{{ route('deducciones.edit', ':id') }}".replace(':id',
                                row.id);
                            let deleteUrl = "{{ route('deducciones.show', ':id') }}".replace(':id',
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
