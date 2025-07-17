@extends('adminlte::page')

@section('title', 'Gestión de Unidades')

@section('content_header')
    <h1>Gestión de Unidades</h1>
    <a class="my-2 btn btn-primary btn-sm" href="javascript:void(0);" data-toggle="modal" data-target="#createModal">
        <i class="fas fa-plus"></i> Agregar Nueva Unidad
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
                    <h3 class="card-title">Listado de Unidades</h3>
                </div>
                <div class="card-body">
                    <table id="tablaUnidadesInsumos" class="table table-bordered table-striped dt-responsive nowrap"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Contiene</th>
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
    @if ($errors->any())
        <script>
            $('#createModal').modal('show');
        </script>
    @endif
    <script>
        $(function() {
            $('#tablaUnidadesInsumos').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
                ajax: "{{ route('datatable.getUnidades') }}",
                columns: [{
                        data: "id"
                    },
                    {
                        data: "nombre"
                    },
                    {
                        data: "contiene"
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let editUrl = "{{ route('unidades.edit', ':id') }}".replace(':id', row
                                .id);
                            return `
                                <a href="${editUrl}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal"
                                    data-id="${row.id}" data-nombre="${row.nombre}" data-contiene="${row.contiene}">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                            `;
                        }
                    }
                ]
            });
        });

        $('#deleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var nombre = button.data('nombre');
            var contiene = button.data('contiene');
            var actionUrl = '{{ route('unidades.destroy', ':id') }}'.replace(':id', id);

            $(this).find('form').attr('action', actionUrl);
            $(this).find('.modal-body #nombre').text(nombre);
            $(this).find('.modal-body #contiene').text(contiene);
        });
    </script>
     <script>
        $(document).ready(function () {
            $('#createModal').on('hidden.bs.modal', function () {
                const form = $(this).find('form')[0];
                if (form) {
                    form.reset();
                    $(form).find('.is-invalid').removeClass('is-invalid');
                    $(form).find('.alert-danger').remove();
                    $('#codigo_proveedor').prop('readonly', false);
                }
            });
        });
    </script>
@stop

<!-- Modal para crear unidad de insumo -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Nueva Unidad de Insumo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('unidades.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" oninput="this.value = this.value.toUpperCase()" class="form-control @error('nombre') is-invalid @enderror" name="nombre"
                            id="nombre" value="{{ old('nombre') }}">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="contiene">Contiene</label>
                        <input type="number" class="form-control @error('contiene') is-invalid @enderror"
                            name="contiene" id="contiene" value="{{ old('contiene') }}">
                        @error('contiene')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Registrar
                    </button>
                    <button type="button" class="btn text-light btn-warning" data-dismiss="modal">
                        <i class="fas fa-arrow-left"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para confirmar eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Eliminar Unidad de Insumo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Nombre:</strong> <span id="nombre"></span></p>
                <p><strong>Contiene:</strong> <span id="contiene"></span></p>
                <p>¿Estás seguro de que deseas eliminar esta unidad de insumo?</p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>
