@extends('adminlte::page')

@section('title', 'Papelera de Reciclaje')

@section('content_header')
    <h1>Papelera de Reciclaje</h1>
    <form action="{{ route('papelera.store') }}" method="POST"
        onsubmit="return confirm('¿Estás seguro de que deseas restaurar todos los registros?')">
        @csrf
        <button type="submit" class="btn btn-success btn-sm">
            <i class="fas fa-undo"></i> Restaurar Todos
        </button>
    </form>
    <form action="{{ route('papeleras.destroy', '1') }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger"
            onclick="return confirm('¿Estás seguro de que deseas vaciar la papelera? Esta acción no se puede deshacer.')">
            <i class="fas fa-trash"></i> Vaciar Papelera
        </button>
    </form>
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

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Registros Eliminados</h3>
                </div>
                <div class="card-body">
                    <table id="tablaPapeleras" class="table table-bordered table-striped dt-responsive nowrap"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Registro</th>
                                <th>Modelo</th>
                                <th>Token</th>
                                <th>Usuario</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Restaurar -->
    <div class="modal fade" id="restaurarModal" tabindex="-1" role="dialog" aria-labelledby="restaurarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="restaurarModalLabel">Confirmar Restaurar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas restaurar este registro?
                </div>
                <div class="modal-footer">
                    <form id="restaurarForm" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Restaurar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Eliminar -->
    <div class="modal fade" id="eliminarModal" tabindex="-1" role="dialog" aria-labelledby="eliminarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eliminarModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.
                </div>
                <div class="modal-footer">
                    <form id="eliminarForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
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
            $('#tablaPapeleras').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
                ajax: "{{ route('datatable.papelera') }}",
                columns: [{
                        data: "registro",
                        render: function(data, type, row) {
                            return data.length > 20 ? data.substring(0, 20) + "..." : data;
                        }
                    },
                    {
                        data: "modelo"
                    },
                    {
                        data: "pk"
                    },
                    {
                        data: "usuario"
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return row.fecha.replace(" 00:00:00", "");
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let editUrl = "{{ route('papelera.update', ':id') }}".replace(':id', row
                                .id);
                            let deleteUrl = "{{ route('papelera.destroy', ':id') }}".replace(':id', row
                                .id);
                            return `
                                <a href="#" class="btn btn-info btn-sm" data-id="${row.id}" data-url="${editUrl}" data-toggle="modal" data-target="#restaurarModal">
                                    <i class="fas fa-sync-alt"></i> Restaurar
                                </a>
                                <a href="#" class="btn btn-danger btn-sm" data-id="${row.id}" data-url="${deleteUrl}" data-toggle="modal" data-target="#eliminarModal">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </a>
                            `;
                        }
                    }
                ]
            });

            $('#restaurarModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var url = button.data('url');
                var form = $('#restaurarForm');
                form.attr('action', url);
            });

            $('#eliminarModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var url = button.data('url');
                var form = $('#eliminarForm');
                form.attr('action', url);
            });
        });
    </script>
@stop
