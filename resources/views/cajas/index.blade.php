@extends('adminlte::page')

@section('title', 'Gestión de Cajas')

@section('content_header')
    <h1>Gestión de Cajas</h1>
    <a class="my-2 btn btn-primary btn-sm" href="javascript:void(0);" data-toggle="modal" data-target="#createModal">
        <i class="fas fa-plus"></i> Agregar Nueva Caja
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
                    <h3 class="card-title">Listado de Cajas</h3>
                </div>
                <div class="card-body">
                    <table id="tablaCajas" class="table table-bordered table-striped dt-responsive nowrap" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Número de Caja</th>
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
        $(function() {
            $('#tablaCajas').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
                ajax: "{{ route('datatable.getCajas') }}",
                columns: [
                    { data: "id" },
                    { data: "numcaja" },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let editUrl = "{{ route('cajas.edit', ':id') }}".replace(':id', row.id);
                            return ` <a href="${editUrl}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" data-id="${row.id}" data-numcaja="${row.numcaja}">
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
            var numcaja = button.data('numcaja');
            var actionUrl = '{{ route('cajas.destroy', ':id') }}'.replace(':id', id);

            $(this).find('form').attr('action', actionUrl);
            $(this).find('.modal-body #numcaja').text(numcaja);
        });
    </script>
@stop

<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Nueva Caja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('cajas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="numcaja">Número de Caja</label>
                        <input type="text" oninput="this.value = this.value.toUpperCase()" class="form-control @error('numcaja') is-invalid @enderror" name="numcaja" id="numcaja" value="{{ old('numcaja') }}" required>
                        @error('numcaja')
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

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Eliminar Caja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar la caja <strong id="numcaja"></strong>?</p>
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
