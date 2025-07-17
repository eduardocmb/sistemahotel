@extends('adminlte::page')

@section('title', 'Cerrar Caja')

@section('content_header')
    <h1>Cerrar Caja</h1>
@stop

@section('content')
    <div class="card">
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
                <div id="error"></div>
            </div>
        </div>
        <div class="card-header bg-teal">
            <h3 class="card-title">Generar Cierre</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('cierre_cajas.store') }}" id="formulario" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="fecha">Fecha:</label>
                        <input type="date" name="fecha" id="fecha" class="form-control"
                            value=" {{ date('Y-m-d') }} " required>
                    </div>
                    <div class="col-md-4">
                        <label for="usuario">Usuario:</label>
                        <input type="text" name="usuario" id="usuario" class="form-control"
                            value="{{ auth()->user()->username }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="turno">Turno:</label>
                        <select name="turno" id="turno" class="form-control">
                            <option value="">SELECCIONE UN TURNO</option>
                            @foreach ($turnos as $turno)
                                <option value="{{ $turno->id }}">{{ $turno->turno }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="fondo">Fondo:</label>
                        <input type="text" readonly name="fondo" id="fondo" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="ap_num">Código de Apertura:</label>
                        <input type="text" readonly name="ap_num" id="ap_num" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="estado">Estado:</label>
                        <input type="text" readonly name="estado" id="estado" class="form-control">
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Denominación</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td>Billetes de 1 Lempira</td>
                        <td><input type="number" name="cantidad[1]" class="form-control cantidad" data-denominacion="1"
                                min="0"></td>
                        <td><input type="text" class="form-control total" readonly></td>
                        @foreach ([2, 5, 10, 20, 50, 100, 200, 500] as $denominacion)
                            <tr>
                                <td>Billetes de {{ $denominacion }} Lempiras</td>
                                <td><input type="number" name="cantidad[{{ $denominacion }}]" class="form-control cantidad"
                                        data-denominacion="{{ $denominacion }}" min="0"></td>
                                <td><input type="text" class="form-control total" readonly></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-right font-weight-bold">TOTAL:</td>
                            <td><input type="text" name="grantotal" id="gran-total" class="form-control" readonly></td>
                        </tr>
                    </tfoot>
                </table>

                <div class="mt-3">
                    <button type="button" id="btn-guardar" class="btn btn-success"><i class="fas fa-save"></i>
                        Guardar</button>
                    <button type="button" class="btn btn-primary" id="btn-imprimir"><i class="fas fa-print"></i>
                        Imprimir</button>
                    <button type="reset" class="btn btn-danger"><i class="fas fa-times"></i> Cancelar</button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
        }
    </style>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cantidades = document.querySelectorAll('.cantidad');
            const granTotal = document.getElementById('gran-total');

            cantidades.forEach(input => {
                input.addEventListener('input', () => {
                    const denominacion = input.dataset.denominacion;
                    const cantidad = parseInt(input.value) || 0;
                    const total = cantidad * denominacion;

                    input.closest('tr').querySelector('.total').value = total.toFixed(2);

                    calcularGranTotal();
                });
            });

            function calcularGranTotal() {
                let total = 0;

                document.querySelectorAll('.total').forEach(input => {
                    total += parseFloat(input.value) || 0;
                });

                granTotal.value = total.toFixed(2);
            }

            const turnoElement = document.getElementById('turno');
            const fechaElement = document.getElementById('fecha');
            const btnGuardar = document.getElementById('btn-guardar');
            const mensajeConfirmacion = document.getElementById('mensaje-confirmacion');
            const modalConfirmacion = new bootstrap.Modal(document.getElementById('modal-confirmacion'));

            btnGuardar.addEventListener('click', () => {
                document.getElementById('error').innerHTML = '';

                if (turnoElement.value === "") {
                    document.getElementById('error').innerHTML =
                        `
                    <div class="alert alert-danger alert-dismissible sticky-top fade show" role="alert">
                        <strong>Debe seleccionar un turno.</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                    return false;
                }
                if (fechaElement.value === "") {
                    document.getElementById('error').innerHTML =
                        `
                    <div class="alert alert-danger alert-dismissible sticky-top fade show" role="alert">
                        <strong>Debe seleccionar una fecha.</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                    return false;
                }

                const turnoSeleccionado = turnoElement.options[turnoElement.selectedIndex].text;
                const fechaSeleccionada = fechaElement.value;
                mensajeConfirmacion.textContent =
                    `¿Desea cerrar la caja del turno "${turnoSeleccionado}" del día ${fechaSeleccionada}?`;

                modalConfirmacion.show();
            });

            document.getElementById('btn-confirmar-cierre').addEventListener('click', () => {
                document.getElementById('formulario').submit();
                var enlace = document.createElement('a');
                enlace.href =
                    '{{ route('cierre_cajas.index') }}';
                enlace.target = '_blank';
                document.body.appendChild(enlace);
                enlace.click();
                document.body.removeChild(enlace);
            });
        });
    </script>
    <script>
        const turnoElement = document.getElementById('turno');
        const fechaElement = document.getElementById('fecha');

        function getAperturas() {
            if (turnoElement && turnoElement.value !== "") {
                const turnoId = turnoElement.value;
                const fecha = fechaElement.value;
                const url = `/cajas/${turnoId}/${fecha}`;
                console.log(url);

                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error al obtener los datos');
                        }
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('fondo').value = data.apertura.fondoinicial == undefined ? '' : data
                            .apertura.fondoinicial;
                        document.getElementById('ap_num').value = data.apertura.codigo_apertura == undefined ? '' : data
                            .apertura.codigo_apertura;
                        document.getElementById('estado').value = data.apertura.estado == undefined ? '' : data.apertura
                            .estado;

                        if (data.apertura.estado === "ABIERTA") {
                            document.getElementById('btn-guardar').disabled = false;
                        } else {
                            document.getElementById('btn-guardar').disabled = true;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        }
        turnoElement.addEventListener('change', getAperturas);
        fechaElement.addEventListener('change', getAperturas);
    </script>

@stop
<div class="modal fade" id="modal-confirmacion" tabindex="-1" aria-labelledby="modal-confirmacion-label"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-confirmacion-label">Confirmación de Cierre de Caja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="mensaje-confirmacion"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-confirmar-cierre">Cerrar Caja</button>
            </div>
        </div>
    </div>
</div>
