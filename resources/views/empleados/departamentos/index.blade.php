@extends('adminlte::page')

@section('title', 'Gestión de Departamentos')

@section('content_header')
    <h1>Gestión de Departamentos</h1>
    <a class="my-2 btn btn-primary btn-sm" onclick="AgregarCodigoAutomatico()" href="javascript:void(0);" data-toggle="modal" data-target="#createModal">
        <i class="fas fa-plus-circle"></i> Agregar Nuevo Departamento
    </a>
    <a class="btn btn-secondary my-2 btn-sm" href="{{ route('empleados.index') }}">
        <i class="fas fa-arrow-left"></i> Volver a Empleados
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
                    <h3 class="card-title">Listado de Departamentos</h3>
                </div>
                <div class="card-body">
                    <table id="tablaDepartamentos" class="table table-bordered table-striped dt-responsive nowrap"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Departamento</th>
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
            $('#tablaDepartamentos').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
                ajax: "{{ route('datatable.getDepartamentos') }}",
                columns: [{
                        data: "codigo"
                    },
                    {
                        data: "departamento"
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let editUrl = "{{ route('departamentos.edit', ':id') }}".replace(':id', row.id);
                            return `
                                <a href="${editUrl}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal"
                                    data-id="${row.id}" data-codigo="${row.codigo}" data-departamento="${row.departamento}">
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
            var codigo = button.data('codigo');
            var departamento = button.data('departamento');
            var actionUrl = '{{ route('departamentos.destroy', ':id') }}'.replace(':id', id);

            $(this).find('form').attr('action', actionUrl);
            $(this).find('.modal-body #codigo').text(codigo);
            $(this).find('.modal-body #departamento').text(departamento);
        });
    </script>
    <script>
        function AgregarCodigoAutomatico() {
            const txtcodigo = document.getElementById('codigo_departamento');
                const key = 'DPRT';
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
                        txtcodigo.value = "DPT" + data.codigo;
                    })
                    .catch(error => console.error('Error:', error));
                txtcodigo.readOnly = true;
            }
            AgregarCodigoAutomatico();
    </script>
    <script>
    $(document).ready(function () {
        $('#createModal').on('hidden.bs.modal', function () {
            $('#departamento').val('');
        });
    });
    </script>
@stop

<!-- Modal para crear departamento -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Nuevo Departamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('departamentos.store') }}" method="POST">
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
                        <label for="codigo_departamento">Código</label>
                        <input type="text" readonly class="form-control @error('codigo_departamento') is-invalid @enderror"
                               name="codigo_departamento" id="codigo_departamento" value="{{ old('codigo_departamento') }}">
                        @error('codigo_departamento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="departamento">Departamento</label>
                        <input oninput="this.value = this.value.toUpperCase()" type="text" class="form-control @error('departamento') is-invalid @enderror"
                               name="departamento" id="departamento" value="{{ old('departamento') }}">
                        @error('departamento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="m-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Registrar
                        </button>
                        <button type="button" data-dismiss="modal" class="btn text-light btn-warning">
                            <i class="fas fa-arrow-left"></i> Cerrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar departamento -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Eliminar Departamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Código:</strong> <span id="codigo"></span></p>
                <p><strong>Departamento:</strong> <span id="departamento"></span></p>
                <p>¿Estás seguro de que deseas eliminar este departamento?</p>
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
