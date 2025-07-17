@extends('adminlte::page')

@section('title', 'Gestión de Proveedores')

@section('content_header')
    <h1>Gestión de Proveedores</h1>
    <a class="my-2 btn btn-primary btn-sm" href="javascript:void(0);" data-toggle="modal" data-target="#createModal">
        <i class="fas fa-user-plus"></i> Agregar Nuevo Proveedor
    </a>
    <a class="my-2 btn btn-success btn-sm" href="{{route('cuentas.index')}}" >
        <i class="fas fa-file-invoice-dollar"></i> Gestionar Cuentas por Pagar
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
                    <h3 class="card-title">Listado de Proveedores</h3>
                </div>
                <div class="card-body">
                    <table id="tablaProveedores" class="table table-bordered table-striped dt-responsive nowrap"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Email</th>
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
            $('#tablaProveedores').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
                ajax: "{{ route('datatable.getProveedores') }}",
                columns: [{
                        data: "id"
                    },
                    {
                        data: "nombre"
                    },
                    {
                        data: "telefono"
                    },
                    {
                        data: "email"
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let editUrl = "{{ route('proveedores.edit', ':id') }}".replace(':id',
                                row.id);
                            return `
                                <a href="${editUrl}" class="btn btn-info btn-sm">
                                    <i class="fas fa-sync-alt"></i> Editar
                                </a>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal"
                                    data-id="${row.id}" data-codigo="${row.codigo}" data-nombre="${row.nombre}"
                                    data-telefono="${row.telefono}" data-email="${row.email}" data-direccion="${row.direccion}">
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
            var nombre = button.data('nombre');
            var telefono = button.data('telefono');
            var email = button.data('email');
            var direccion = button.data('direccion');
            var actionUrl = '{{ route('proveedores.destroy', ':id') }}'.replace(':id', id);

            $(this).find('form').attr('action', actionUrl);
            $(this).find('.modal-body #codigo').text(codigo);
            $(this).find('.modal-body #nombre').text(nombre);
            $(this).find('.modal-body #telefono').text(telefono);
            $(this).find('.modal-body #email').text(email);
            $(this).find('.modal-body #direccion').text(direccion);
        });
    </script>

    <script>
        const chk = document.getElementById('chkGenerarAuto');
        const txtcodigo_insumo = document.getElementById('codigo_proveedor');
            function AgregarCodigoAutomatico() {
            if (chk.checked) {
                const key = 'PROV';
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
                        txtcodigo_insumo.value = "PRV" + data.codigo;
                    })
                    .catch(error => console.error('Error:', error));
                txtcodigo_insumo.readOnly = true;
            } else {
                txtcodigo_insumo.readOnly = false;
                txtcodigo_insumo.value = "";
            }
        }

        chk.addEventListener('change', AgregarCodigoAutomatico);
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

<!-- Modal para crear proveedor -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Nuevo Proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('proveedores.store') }}" method="POST">
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
                        <label for="codigo_proveedor" class="d-inline-flex align-items-center">
                            Código del Proveedor
                            <div class="form-check ml-5">
                                <input type="checkbox"
                                    class="form-check-input @error('codigo_proveedor') is-invalid @enderror"
                                    value="Generar Automáticamente" {{ old('chkGenerarAuto') ? 'checked' : '' }}
                                    name="chkGenerarAuto" id="chkGenerarAuto">
                                <label class="form-check-label" for="chkGenerarAuto">Generar
                                    Automáticamente</label>
                            </div>
                        </label>
                        <input oninput="this.value = this.value.toUpperCase()" type="text"
                            class="form-control @error('codigo_proveedor') is-invalid @enderror" id="codigo_proveedor"
                            {{ old('chkGenerarAuto') ? 'readonly' : '' }} name="codigo_proveedor"
                            value="{{ old('codigo_proveedor') }}" maxlength="9"
                            oninput="this.value = this.value.toUpperCase()">
                        @error('codigo_proveedor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input oninput="this.value = this.value.toUpperCase()" type="text"
                            class="form-control @error('nombre') is-invalid @enderror" name="nombre" id="nombre"
                            value="{{ old('nombre') }}">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input oninput="this.value = this.value.toUpperCase()" type="text"
                            class="form-control @error('telefono') is-invalid @enderror" name="telefono" id="telefono"
                            value="{{ old('telefono') }}">
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            id="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <textarea oninput="this.value = this.value.toUpperCase()" class="form-control @error('direccion') is-invalid @enderror"
                            name="direccion" id="direccion">{{ old('direccion') }}</textarea>
                        @error('direccion')
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

<!-- Modal de confirmación para eliminar proveedor -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Eliminar Proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Código:</strong> <span id="codigo"></span></p>
                <p><strong>Nombre:</strong> <span id="nombre"></span></p>
                <p><strong>Teléfono:</strong> <span id="telefono"></span></p>
                <p><strong>Email:</strong> <span id="email"></span></p>
                <p><strong>Dirección:</strong> <span id="direccion"></span></p>
                <p>¿Estás seguro de que deseas eliminar este proveedor?</p>
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
