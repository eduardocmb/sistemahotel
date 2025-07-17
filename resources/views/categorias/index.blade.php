@extends('adminlte::page')

@section('title', 'Gestión de Categorías')

@section('content_header')
    <h1>Gestión de Categorías</h1>
    <a class="my-2 btn btn-primary btn-sm" href="javascript:void(0);" data-toggle="modal" data-target="#createModal">
        <i class="fas fa-plus"></i> Agregar Nueva Categoría
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
                    <h3 class="card-title">Listado de Categorías</h3>
                </div>
                <div class="card-body">
                    <table id="tablaCategorias" class="table table-bordered table-striped dt-responsive nowrap"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Categoría</th>
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
        $(document).ready(function() {
            $('#createModal').on('hidden.bs.modal', function() {
                const form = $(this).find('form')[0];
                if (form) {
                    form.reset();
                    $(form).find('.is-invalid').removeClass('is-invalid');
                    $(form).find('.alert-danger').remove();
                }
            });
        });
    </script>
    <script>
        $(function() {
            $('#tablaCategorias').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
                ajax: "{{ route('datatable.getCategorias') }}",
                columns: [{
                        data: "id"
                    },
                    {
                        data: "categoria"
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let editUrl = "{{ route('categorias.edit', ':id') }}".replace(':id',
                                row.id);
                            return `
                                <a href="${editUrl}" class="btn btn-info btn-sm">
                                    <i class="fas fa-sync-alt"></i> Editar
                                </a>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" data-id="${row.id}" data-categoria="${row.categoria}">
                                    <i class="fas fa-trash"></i> Eliminar
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
            var categoria = button.data('categoria');
            var actionUrl = '{{ route('categorias.destroy', ':id') }}'.replace(':id', id);

            $(this).find('form').attr('action', actionUrl);
            $(this).find('.modal-body #categoria').text(categoria);
        });
    </script>
@stop

<!-- Modal para crear categoría -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Nueva Categoría</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('categorias.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="categoria">Categoría</label>
                        <input oninput="this.value = this.value.toUpperCase()" type="text"
                            class="form-control @error('categoria') is-invalid @enderror" name="categoria"
                            id="editCategoria" value="{{ old('categoria') }}" required>
                        @error('categoria')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="m-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Registrar
                    </button>
                    <button type="button" data-dismiss="modal" class="btn text-light btn-warning">
                        <i class="fas fa-arrow-left"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para eliminar categoría -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Eliminar Categoría</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar la categoría <strong id="categoria"></strong>?</p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>
