@extends('adminlte::page')

@section('title', 'Gestión de Aperturas y Cierres de Cajas')

@section('content_header')
    <h1>Gestión de Cajas</h1>
    <a href="{{ route('cierre_cajas.index') }}" class="btn btn-sm btn-success"><i class="fas fa-money-check-alt"></i> Ir a
        Cierre de Caja</a>
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

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div id="alertdiv"></div>

            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Abrir Caja</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('aperturas_cajas.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <label for="codigo" class="d-inline-flex align-items-center">
                                    Código de Apertura
                                </label>
                                <input type="text" {{ old('chkGenerarAuto') ? 'readonly' : '' }} name="codigo"
                                    id="codigo" class="form-control @error('codigo') is-invalid @enderror">
                                @error('codigo')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="turno_id">Turno:</label>
                                <select name="turno_id" id="turno_id"
                                    class="form-control @error('turno_id') is-invalid @enderror" required>
                                    <option value="">Selecciona un turno</option>
                                    @foreach ($turnos as $turno)
                                        <option value="{{ $turno->id }}"
                                            {{ old('turno_id') == $turno->id ? 'selected' : '' }}>
                                            {{ $turno->turno }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('turno_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fila 2 -->
                            <div class="col-md-6 mt-2">
                                <label for="caja_id">Caja:</label>
                                <select name="caja_id" id="caja_id"
                                    class="form-control @error('caja_id') is-invalid @enderror" required>
                                    <option value="">Selecciona una caja</option>
                                    @foreach ($cajas as $caja)
                                        <option value="{{ $caja->id }}"
                                            {{ old('caja_id') == $caja->id ? 'selected' : '' }}>
                                            {{ $caja->numcaja }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('caja_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="fondoinicial">Fondo Inicial:</label>
                                <input type="number" step="0.01" name="fondoinicial" id="fondoinicial"
                                    class="form-control @error('fondoinicial') is-invalid @enderror">
                                @error('fondoinicial')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2 mt-2">
                                <button class="btn btn-primary" id="btnAbrirCaja">
                                    <i class="fas fa-cash-register"></i> Abrir Caja
                                </button>
                            </div>
                        </div>
                    </form>


                    <!-- Listado de Cajas Abiertas -->
                    <div class="card mt-2">
                        <div class="card-header">
                            <h3 class="card-title">Cajas Abiertas</h3>
                        </div>
                        <div class="card-body">
                            <table id="tablaCajasAbiertas" class="table table-bordered table-striped dt-responsive nowrap"
                                style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Código Apertura</th>
                                        <th>Fecha</th>
                                        <th>Turno</th>
                                        <th>Usuario</th>
                                        <th>Fondo Inicial</th>
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
                    $('#tablaCajasAbiertas').DataTable({
                        responsive: true,
                        autoWidth: false,
                        language: {
                            url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                        },
                        ajax: "{{ route('datatable.getCajasAbiertas') }}",
                        columns: [{
                                data: "codigo_apertura"
                            },
                            {
                                data: "fecha"
                            },
                            {
                                data: "turno"
                            },
                            {
                                data: "username"
                            },
                            {
                                data: "fondoinicial"
                            },
                            {
                                data: "estado"
                            },
                            {
                                data: null,
                                render: function(data, type, row) {
                                    let printUrl = "{{ route('rpt.cierreCaja', ':id') }}".replace(':id',
                                        row.id);
                                    let closeUrl = "{{ route('aperturas_cajas.destroy', ':id') }}".replace(
                                        ':id', row.id);

                                    let printLink = row.estado === 'CERRADA' ?
                                        `<a href="${printUrl}" target="_blank" class="btn btn-info btn-sm"><i class="fas fa-print"></i> Imprimir</a>` :
                                        '';

                                    return `
                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal"
                                        data-id="${row.id}"
                                        data-codigo="${row.codigo_apertura}"
                                        data-fecha="${row.fecha}"
                                        data-turno="${row.turno}"
                                        data-usuario="${row.username}"
                                        data-fondo="${row.fondoinicial}">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                    ${printLink}
                                `;
                                }
                            }

                        ]
                    });
                });
            </script>
            <script>
                const txtcodigo = document.getElementById('codigo');

                function AgregarCodigoAutomatico() {
                    const key = 'APER';
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
                            txtcodigo.value = "APE" + data.codigo;
                        })
                        .catch(error => console.error('Error:', error));
                    txtcodigo.readOnly = true;
                }
                AgregarCodigoAutomatico();
            </script>
            <script>
                function validarNumero(event) {
                    const input = event.target;
                    const valor = input.value;

                    if (valor === "") {
                        return;
                    }

                    if (valor === "-0") {
                        input.value = 1;
                        return;
                    }

                    const valorNumerico = parseFloat(valor);
                    if (isNaN(valorNumerico) || valorNumerico < 0) {
                        input.value = 1;
                    }
                }
                document.getElementById('fondoinicial').addEventListener('input', validarNumero);
            </script>

            <script>
                $('#deleteModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var id = button.data('id');
                    var codigo = button.data('codigo');
                    var fecha = button.data('fecha');
                    var turno = button.data('turno');
                    var usuario = button.data('usuario');
                    var fondo = button.data('fondo');

                    $(this).find('#codigoDel').text(codigo);
                    $(this).find('#fechaDel').text(fecha);
                    $(this).find('#turnoDel').text(turno);
                    $(this).find('#usuarioDel').text(usuario);
                    $(this).find('#fondoDel').text(fondo);

                    var formAction = "{{ route('aperturas_cajas.destroy', ':id') }}".replace(':id', id);
                    $(this).find('#deleteForm').attr('action', formAction);
                });
            </script>
        @stop


        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>¿Deseas eliminar la siguiente caja? Esta acción es irreversible.</p>
                        <ul>
                            <li><strong>Código de Apertura:</strong> <span id="codigoDel"></span></li>
                            <li><strong>Fecha de Apertura:</strong> <span id="fechaDel"></span></li>
                            <li><strong>Turno:</strong> <span id="turnoDel"></span></li>
                            <li><strong>Usuario:</strong> <span id="usuarioDel"></span></li>
                            <li><strong>Fondo Inicial:</strong> <span id="fondoDel"></span></li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <form id="deleteForm" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
