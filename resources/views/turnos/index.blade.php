@extends('adminlte::page')

@section('title', 'Gestión de Turnos')

@section('content_header')
    <h1>Gestión de Turnos</h1>
    <a class="my-2 btn btn-primary btn-sm" href="javascript:void(0);" data-toggle="modal" data-target="#createModal">
        <i class="fas fa-plus"></i> Agregar Nuevo Turno
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
                    <h3 class="card-title">Listado de Turnos</h3>
                </div>
                <div class="card-body">
                    <table id="tablaTurnos" class="table table-bordered table-striped dt-responsive nowrap"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Código</th>
                                <th>Turno</th>
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
            $('#tablaTurnos').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
                ajax: "{{ route('datatable.getTurnos') }}",
                columns: [{
                        data: "id"
                    },
                    {
                        data: "codigo"
                    },
                    {
                        data: "turno"
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let editUrl = "{{ route('turnos.edit', ':id') }}".replace(':id', row.id);
                            return `
                                <a href="${editUrl}" class="btn btn-info btn-sm">
                                    <i class="fas fa-sync-alt"></i> Editar
                                </a>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" data-id="${row.id}" data-turno="${row.turno}">
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
            var turno = button.data('turno');
            var actionUrl = '{{ route('turnos.destroy', ':id') }}'.replace(':id', id);

            $(this).find('form').attr('action', actionUrl);
            $(this).find('.modal-body #turno').text(turno);
        });
    </script>
    <script>
        const chk = document.getElementById('chkGenerarAuto');
        const txtcodigo = document.getElementById('codigo');
            function AgregarCodigoAutomatico() {
            if (chk.checked) {
                const key = 'TURN';
                const table = 'correlativos';
                const prefix = '';

                const url = `/correlativos/get/${key}/${table}/${prefix}`;

                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    txtcodigo.value = "TUR" + data.codigo;
                })
                .catch(error => console.error('Error:', error));
                txtcodigo.readOnly = true;
            }else{
                txtcodigo.readOnly = false;
                txtcodigo.value = "";
            }
        }

        chk.addEventListener('change', AgregarCodigoAutomatico);
    </script>
@stop

<!-- Modal para crear turno -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Nuevo Turno</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('turnos.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="codigo" class="d-inline-flex align-items-center">
                            Código del Turno
                            <div class="form-check ml-5">
                                <input type="checkbox" oninput="this.value = this.value.toUpperCase()"
                                    class="form-check-input @error('codigo') is-invalid @enderror"
                                    value="Generar Automáticamente" {{old('chkGenerarAuto') ? 'checked':''}} name="chkGenerarAuto" id="chkGenerarAuto">
                                <label class="form-check-label" for="chkGenerarAuto">Generar
                                    Automáticamente</label>
                            </div>
                        </label>
                        <input oninput="this.value = this.value.toUpperCase()" type="text"
                            class="form-control @error('codigo') is-invalid @enderror" name="codigo"
                            id="codigo" value="{{ old('codigo') }}" required>
                        @error('codigo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="turno">Turno</label>
                        <input type="text" oninput="this.value = this.value.toUpperCase()" class="form-control @error('turno') is-invalid @enderror" name="turno"
                            id="editTurno" value="{{ old('turno') }}" required>
                        @error('turno')
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

<!-- Modal para eliminar turno -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Eliminar Turno</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar el turno <strong id="turno"></strong>?</p>
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
