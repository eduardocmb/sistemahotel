@extends('adminlte::page')

@section('title', 'Gestión de Notificaciones')

@section('content_header')
    <h1>Gestión de Notificaciones</h1>
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
                    <h3 class="card-title">Listado de Notificaciones</h3>
                </div>
                <div class="card-body">
                    <table id="tablaNotificaciones" class="table table-bordered table-striped dt-responsive nowrap"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Descripción</th>
                                <th>Fecha</th>
                                <th>Leído</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver notificación -->
    <div class="modal fade" id="modalNotificacion" tabindex="-1" aria-labelledby="modalNotificacionLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNotificacionLabel">Detalles de la Notificación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Título:</strong> <span id="notificacionTitulo"></span></p>
                    <p><strong>Descripción:</strong> <span id="notificacionDescripcion"></span></p>
                    <p><strong>Fecha:</strong> <span id="notificacionFecha"></span></p>
                    <p><strong>Leído:</strong> <span id="notificacionLeido"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
            $('#tablaNotificaciones').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
                ajax: "{{ route('datatable.getNotificaciones') }}",
                columns: [{
                        data: "title",
                        render: function(data, type, row) {
                            return data.length > 15 ? data.substring(0, 15) + "..." : data;
                        }
                    },
                    {
                        data: "description",
                        render: function(data, type, row) {
                            return data.length > 15 ? data.substring(0, 15) + "..." : data;
                        }
                    },
                    {
                        data: "created_at",
                        render: function(data) {
                            if (data) {
                                const date = new Date(data);
                                return date.toLocaleDateString('es-ES', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: 'numeric'
                                });
                            }
                            return '';
                        }
                    },
                    {
                        data: "leido",
                        render: function(data) {
                            return data === 'S' ? 'Sí' : 'No';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-info btn-sm verNotificacion" data-id="${row.id}" data-title="${row.title}" data-description="${row.description}" data-created_at="${row.created_at}" data-leido="${row.leido}">
                                    <i class="fas fa-eye"></i> Ver
                                </button>`;
                        }
                    }
                ]
            });

            $(document).on('click', '.verNotificacion', function() {
                const title = $(this).data('title');
                const description = $(this).data('description');
                const createdAt = new Date($(this).data('created_at')).toLocaleDateString('es-ES', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
                const leido = $(this).data('leido') === 'S' ? 'Sí' : 'No';

                $('#notificacionTitulo').text(title);
                $('#notificacionDescripcion').text(description);
                $('#notificacionFecha').text(createdAt);
                $('#notificacionLeido').text(leido);

                $('#modalNotificacion').modal('show');
            });
        });
    </script>
@stop
