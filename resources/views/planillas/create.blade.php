@extends('adminlte::page')

@section('title', 'Creación de Planilla')

@section('content_header')
    <a href="{{ route('planilla.index') }}" class="ml-2 btn text-light btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="error" class="mt-2"></div>
            </div>
            <div class="col-md-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
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

                @if (session('error'))
                    <div class="alert alert-danger">
                        <strong>{{ session('error') }}</strong>
                    </div>
                @endif
            </div>
        </div>

        <form method="POST" id="formId" action="{{ route('planilla.store') }}">
            @csrf
            <!-- Datos Generales -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">DATOS GENERALES</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="empleado_id">Empleado:</label>
                            <select name="empleado_id" id="empleado_id"
                                class="form-control @error('empleado_id') is-invalid @enderror" required>
                                <option value="">Seleccione un Empleado</option>
                                @foreach ($empleados as $empleado)
                                    <option value="{{ $empleado->id }}">{{ $empleado->nombrecompleto }}</option>
                                @endforeach
                            </select>
                            @error('empleado_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="fecha_inicio">Inicio de Periodo:</label>
                            <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="fecha_final">Fin de Periodo:</label>
                            <input type="date" id="fecha_final" name="fecha_final" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="numero">Código de Planilla:</label>
                            <input type="text" readonly id="codigo" name="codigo" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="salario">Salario del Empleado:</label>
                            <input readonly type="text" id="salario" name="salario" class="form-control">
                        </div>
                        <div class="col-md-3 mt-4 d-flex align-items-center">
                            <div class="form-check mt-2">
                                <input checked class="form-check-input" type="checkbox" id="incluir_hextra" name=""
                                    style="transform: scale(1.5);">
                                <label class="form-check-label" for="incluir_hextra">
                                    Incluir Horas Extra
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agregar Detalles -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">AGREGAR DETALLES</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="motivo">Motivo:</label>
                            <input oninput="this.value = this.value.toUpperCase()" type="text" id="motivo"
                                name="motivo" class="form-control @error('motivo') is-invalid @enderror" required>
                            @error('motivo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="cantidad">Cantidad:</label>
                            <input type="number" id="cantidad" name="cantidad"
                                class="form-control @error('cantidad') is-invalid @enderror" min="1" required>
                            @error('cantidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4" id="div_devengado">
                            <label for="devengado">Devengado:</label>
                            <input readonly type="number" id="devengado" name="devengado"
                                class="form-control @error('devengado') is-invalid @enderror" step="0.01" required>
                            @error('devengado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="tipo">Tipo:</label>
                            <select name="tipo" id="tipo" class="form-control @error('tipo') is-invalid @enderror"
                                required>
                                <option value="devengado">Devengado</option>
                                <option value="deduccion">Deducción</option>
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4" id="div_tipodeduccion">
                            <label for="tipodeduccion">Tipo de Deducción:</label>
                            <select name="tipodeduccion" id="tipodeduccion"
                                class="form-control @error('tipodeduccion') is-invalid @enderror" required>
                                <option value="">Seleccione un tipo de deducción</option>
                                @foreach ($deducciones as $deduccion)
                                    <option value="{{ $deduccion->id }}">{{ $deduccion->nombre }}</option>
                                @endforeach
                            </select>
                            @error('tipodeduccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4" id="div_deducido">
                            <label for="deducido">Deducido:</label>
                            <input readonly type="number" id="deducido" name="deducido"
                                class="form-control @error('deducido') is-invalid @enderror" step="0.01" required>
                            @error('deducido')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2 mt-2">
                            <button type="button" class="btn btn-success w-100" id="agregar_detalle">
                                <i class="fas fa-plus"></i> Agregar Detalle
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="divHorasExtra" class="card mb-4">
                <div class="card-header bg-info text-white">AGREGAR DETALLES DE HORAS EXTRA</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="motivo">Motivo:</label>
                            <input oninput="this.value = this.value.toUpperCase()" type="text" id="motivoHextra"
                                name="motivoHextra" class="form-control @error('motivo') is-invalid @enderror" required>
                            @error('motivo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="cantidad">Cantidad de Horas:</label>
                            <input type="number" id="cantidadHextra" name="cantidadHextra"
                                class="form-control @error('cantidad') is-invalid @enderror" min="1" required>
                            @error('cantidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4" id="div_devengado">
                            <label for="devengado">Valor por Hora:</label>
                            <input type="number" id="devengadoHextra" name="devengadoHextra"
                                class="form-control @error('devengado') is-invalid @enderror" step="0.01" required>
                            @error('devengado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4" id="div_valor">
                            <label for="valor">Valor Neto:</label>
                            <input readonly type="number" id="valorHextra" name="valorHextra"
                                class="form-control @error('valor') is-invalid @enderror" step="0.01" required>
                            @error('valor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2 mt-4">
                            <button type="button" class="btn btn-success mt-1 w-100" id="agregar_detalle_hextra">
                                <i class="fas fa-plus"></i> Agregar Detalle
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Detalles -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">DETALLES DE LA PLANILLA</div>
                <div class="card-body">
                    <table class="table table-bordered" id="tabla_detalles">
                        <thead>
                            <tr>
                                <th>Motivo</th>
                                <th>Cantidad</th>
                                <th>Devengado</th>
                                <th>Deducido</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <input type="hidden" id="total_planilla" name="total_planilla">
        </form>

        <!-- Totales -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 offset-md-8">
                        <div class="d-flex justify-content-between">
                            <span>Total:</span>
                            <span name="total" id="total">0.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Botón de Envío -->
        <div class="text-center">
            <button id="btnguardar" type="button" class="my-2 btn btn-primary">
                <i class="fa fa-save"></i> Guardar Planilla
            </button>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script>
        function calcularValorNeto() {
            const cantidad = parseFloat(document.getElementById('cantidadHextra').value) || 0;
            const valorPorHora = parseFloat(document.getElementById('devengadoHextra').value) || 0;
            const valorNeto = cantidad * valorPorHora;

            document.getElementById('valorHextra').value = valorNeto.toFixed(2);
        }

        document.getElementById('cantidadHextra').addEventListener('input', calcularValorNeto);
        document.getElementById('devengadoHextra').addEventListener('input', calcularValorNeto);

        function AgregarCodigoAutomatico() {
            const key = 'PLAN';
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
                    document.getElementById('codigo').value = "PLA" + data.codigo;
                })
                .catch(error => console.error('Error:', error));
            document.getElementById('codigo').readOnly = true;
        }
        AgregarCodigoAutomatico();
    </script>
    <script>
        document.getElementById('fecha_final').addEventListener('change', function() {
            const fechaInicio = document.getElementById('fecha_inicio').value;
            const fechaFinal = this.value;

            if (fechaFinal && fechaInicio && new Date(fechaFinal) < new Date(fechaInicio)) {
                document.getElementById("error").innerHTML =
                    'La fecha final no puede ser anterior a la fecha de inicio.';
                this.value = ''; // Resetea el campo de fecha final
            }
        });

        document.getElementById('motivoHextra').disabled = true;
        document.getElementById('cantidadHextra').disabled = true;
        document.getElementById('devengadoHextra').disabled = true;
        document.getElementById('valorHextra').disabled = true;

        document.getElementById('cantidadHextra').addEventListener('input', function() {
            document.getElementById('devengadoHextra').value = parseFloat(this.value) * parseFloat(document
                .getElementById('valorHextra').value);

        });

        document.getElementById("btnguardar").addEventListener("click", function(event) {
            let tableBody = document.querySelector("#tabla_detalles tbody");
            if (tableBody.rows.length === 0) {
                event.preventDefault();
                document.getElementById("error").innerHTML = `
                <div class="alert alert-danger alert-dismissible sticky-top fade show" role="alert">
                <strong>Por favor, complete todos los campos.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`;
                return false;
            }

            if (document.getElementById('fecha_inicio').value === "") {
                document.getElementById("error").innerHTML = `
                <div class="alert alert-danger alert-dismissible sticky-top fade show" role="alert">
                <strong>Debe elegir una fecha de inicio.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`;
                return false;
            }

            if (document.getElementById('fecha_final').value === "") {
                document.getElementById("error").innerHTML = `
                <div class="alert alert-danger alert-dismissible sticky-top fade show" role="alert">
                <strong>Debe elegir una fecha final.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`;
                return false;
            }

            document.getElementById("formId").submit();
        });

        document.getElementById('valorHextra').addEventListener('input', function() {
            document.getElementById('devengadoHextra').value = parseFloat(document.getElementById(
                'cantidadHextra').value) * parseFloat(document
                .getElementById('valorHextra').value);
        });
        let rowIndex = 0;
        $(document).on('click', '#agregar_detalle_hextra', function() {
            let _motivoHextra = document.getElementById('motivoHextra').value;
            let _cantidadHextra = document.getElementById('cantidadHextra').value;
            let _devengadoHextra = document.getElementById('devengadoHextra').value;
            let _valorHextra = document.getElementById('valorHextra').value;

            if (!_motivoHextra || !_cantidadHextra || !_devengadoHextra || !_valorHextra) {
                document.getElementById('error').innerHTML = `
            <div class="alert alert-danger alert-dismissible sticky-top fade show" role="alert">
                <strong>Por favor, complete todos los campos.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
                return false;
            }
            let totalHextra = 0;
            totalHextra = parseFloat(_valorHextra);
            const row = `
        <tr>
            <input type="hidden" name="motivo[${rowIndex}]" value="${_motivoHextra + "("+_devengadoHextra+")"}">
            <input type="hidden" name="cantidad[${rowIndex}]" value="${_cantidadHextra}">
            <input type="hidden" name="devengado[${rowIndex}]" value="${totalHextra}">
            <input type="hidden" name="deducido[${rowIndex}]" value="">
            <input type="hidden" name="total[${rowIndex}]" value="${totalHextra.toFixed(2)}">
            <td>${_motivoHextra}</td>
            <td>${_cantidadHextra}</td>
            <td>${_devengadoHextra}</td>
            <td>-------</td>
            <td>${totalHextra.toFixed(2)}</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm eliminar-detalle-hextra">
                    <i class="fas fa-trash-alt"></i> Eliminar
                </button>
            </td>
        </tr>
    `;
            rowIndex++;
            $('#tabla_detalles tbody').append(row);

            $('#motivoHextra').val('');
            $('#cantidadHextra').val('');
            $('#devengadoHextra').val('');
            $('#valorHextra').val('');
            $('#error').html('');
            actualizarTotal();
        });

        function actualizarTotalHorasExtra() {
            let totalHorasExtra = 0;
            $('#tabla_detalles_hextra tbody tr').each(function() {
                let total = parseFloat($(this).find('td').eq(4).text());
                totalHorasExtra += total;
            });
            $('#total_horas_extra').text(totalHorasExtra.toFixed(2));
        }

        $(document).on('click', '.eliminar-detalle-hextra', function() {
            $(this).closest('tr').remove();
            actualizarTotal();
        });

        $(document).on('click', '#agregar_detalle', function() {
            let _tipo = document.getElementById('tipo').value;
            let _motivo = document.getElementById('motivo').value;
            let _cantidad = document.getElementById('cantidad').value;
            let _tipoDeduccion = document.getElementById('tipodeduccion').value;
            let _deducido = document.getElementById('deducido').value;

            if (_tipo === 'devengado') {
                if (!_motivo || !_cantidad) {
                    document.getElementById('error').innerHTML =
                        `
                    <div class="alert alert-danger alert-dismissible sticky-top fade show" role="alert">
                        <strong>Por favor, complete el Motivo y la Cantidad para el tipo Devengado.</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                    return false;
                }
            } else if (_tipo === 'deduccion') {
                if (!_tipoDeduccion || !_deducido || !_cantidad) {
                    document.getElementById('error').innerHTML =
                        `
                    <div class="alert alert-danger alert-dismissible sticky-top fade show" role="alert">
                        <strong>Por favor, complete todos los campos: Motivo, Tipo de Deducción, Deducido y Cantidad.</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                    return false;
                }
            }

            const motivo = _tipo === "devengado" ?
                $('#motivo').val() :
                $('#tipodeduccion option:selected').text();
            const cantidad = parseFloat($('#cantidad').val());
            let devengado = _tipo === "deduccion" ? '------' :  parseFloat($('#devengado').val());
            let deducido = parseFloat($('#deducido').val());

            const tipo = document.getElementById('tipo').value;
            let total = 0;
            let devengadoDisplay = '--------';
            let deducidoDisplay = '--------';

            if (tipo === "devengado") {
                total = devengado;
                devengadoDisplay = devengado.toFixed(2);
            } else {
                total = deducido;
                deducidoDisplay = deducido.toFixed(2);
            }


            const row = `
    <tr>
        <input type="hidden" name="motivo[${rowIndex}]" value="${motivo}">
        <input type="hidden" name="cantidad[${rowIndex}]" value="${cantidad}">
        <input type="hidden" name="devengado[${rowIndex}]" value="${devengado}">
        <input type="hidden" name="deducido[${rowIndex}]" value="${deducido}">
        <input type="hidden" name="total[${rowIndex}]" value="${total.toFixed(2)}">
        <td>${motivo}</td>
        <td>${cantidad}</td>
        <td>${devengadoDisplay}</td>
        <td>${deducidoDisplay}</td>
        <td>${total.toFixed(2)}</td>
        <td>
            <button type="button" class="btn btn-danger btn-sm eliminar-detalle">
               <i class="fas fa-trash-alt"></i> Eliminar
            </button>
        </td>
    </tr>
`;
            rowIndex++;
            devengado = 0;
            deducido = 0;

            $('#tabla_detalles tbody').append(row);
            $('#motivo').val('');
            $('#cantidad').val('');
            $('#devengado').val('');
            $('#tipo').val('devengado');
            $('#deducido').val('');
            $('#tipo').trigger('change');
            actualizarTotal();
        });

        document.getElementById('tipodeduccion').addEventListener('change', function() {
            if ($('#empleado_id').val() === "") {
                return;
            }
            $('#motivo').val('');

            const tipodeduccionSeleccionado = $('#tipodeduccion').val();
            fetch(`/get-info-deduccion/${tipodeduccionSeleccionado}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la solicitud');
                    }
                    return response.json();
                })
                .then(data => {
                    let totalCalc = parseFloat(document.getElementById('total').textContent.replace(/,/g, ''));

                    if (data.tipo == "PORCENTAJE") {
                        if (isNaN(totalCalc) || totalCalc <= 0) {
                            document.getElementById('error').innerHTML = `
                    <div class="alert alert-danger alert-dismissible sticky-top fade show" role="alert">
                        <strong>No se han agregado cantidades devengadas a la planilla.</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                            $('#tipo').val('devengado');
                            $('#tipo').trigger('change');
                            return;
                        }
                        let deduc = totalCalc * (parseFloat(data.monto) / 100);
                        $('#motivo').val('');
                        $('#cantidad').val('1').prop('readonly', true);
                        $('#deducido').val(deduc);
                    } else if (data.tipo == "MONTOTOTAL") {
                        $('#cantidad').val('1');
                        let deduc = parseFloat($('#cantidad').val()) * parseFloat(data.monto);
                        $('#deducido').val(deduc);
                        $('#cantidad').prop('readonly', false);
                    } else {
                        return;
                    }
                })
                .catch(error => {
                    console.error('Hubo un problema con la solicitud:', error);
                });
        });


        function actualizarTotal() {
            let total = 0;

            $('#tabla_detalles tbody tr').each(function() {
                const devengadoText = $(this).find('td').eq(2).text().trim();
                const totalText = $(this).find('td').eq(4).text().trim();

                const devengado = parseFloat(devengadoText);
                const totalValue = parseFloat(totalText) || 0;

                if (!isNaN(devengado)) {
                    total += totalValue;
                } else {
                    total -= totalValue;
                }
            });

            $('#total').text(total.toFixed(2));
            $('#total_planilla').val(total.toFixed(2));
        }

        $(document).on('click', '.eliminar-detalle', function() {
            $(this).closest('tr').remove();
            actualizarTotal();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#tipo').change(function() {
                let tipoSeleccionado = $(this).val();

                if (tipoSeleccionado === 'deduccion') {
                    $('#motivo').prop('readonly', true);
                    $('#div_devengado').css('visibility', 'hidden');
                    $('#div_deducido').css('visibility', 'visible');
                    $('#div_tipodeduccion').css('visibility', 'visible');
                } else if (tipoSeleccionado === 'devengado') {
                    $('#motivo').prop('readonly', false);
                    $('#div_devengado').css('visibility', 'visible');
                    $('#div_deducido').css('visibility', 'hidden');
                    $('#div_tipodeduccion').css('visibility', 'hidden');
                }
            });

            $('#tipo').trigger('change');
            //////
        });
    </script>
    <script>
        document.getElementById('incluir_hextra').addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('divHorasExtra').classList.remove('d-none');
            } else {
                document.getElementById('divHorasExtra').classList.add('d-none');
            }
        });

        document.getElementById('empleado_id').addEventListener('change', function() {
            if (this.value !== "") {
                fetch(`/get-info-empleado/${this.value}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la solicitud');
                        }
                        return response.json();
                    })
                    .then(data => {
                        $('#salario').val(data.salario);
                        document.getElementById('motivoHextra').disabled = false;
                        document.getElementById('cantidadHextra').disabled = false;
                        document.getElementById('devengadoHextra').disabled = false;
                        document.getElementById('valorHextra').disabled = false;
                    })
                    .catch(error => {
                        console.error('Hubo un problema con la solicitud:', error);
                    });
            } else {
                $('#salario').val('');
                document.getElementById('motivoHextra').disabled = true;
                document.getElementById('cantidadHextra').disabled = true;
                document.getElementById('devengadoHextra').disabled = true;
                document.getElementById('valorHextra').disabled = true;
            }
        });

        function validarNumero(event) {
            const input = event.target;
            const valor = input.value;

            if (valor === "") {
                return;
            }

            const valorNumerico = parseFloat(valor);
            if (isNaN(valorNumerico) || valorNumerico <= 0) {
                input.value = 1;
            }
        }

        document.getElementById('cantidad').addEventListener('input', function(event) {
            validarNumero(event);
            const txtdevengado = document.getElementById('devengado');
            const txtsalario = document.getElementById('salario');

            if (txtsalario.value === "") {
                document.getElementById('error').innerHTML = `
                <div class="alert alert-danger alert-dismissible sticky-top fade show" role="alert">
                    <strong>No se ha encontrado el salario del empleado, intente de nuevo.</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `;
                this.value = "";
                txtdevengado.value = "";
                return;
            } else {
                document.getElementById('error').innerHTML = '';
            }

            const cantidad = parseFloat(this.value);
            const salario = parseFloat(txtsalario.value);

            if (!isNaN(cantidad) && !isNaN(salario)) {
                txtdevengado.value = (cantidad * salario).toFixed(2);
            } else {
                txtdevengado.value = "";
            }

            if (document.getElementById('tipo').value == "deduccion") {
                if (document.getElementById('tipodeduccion').value !== "") {
                    const tipodeduccionSeleccionado = $('#tipodeduccion').val();
                    fetch(`/get-info-deduccion/${tipodeduccionSeleccionado}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error en la solicitud');
                            }
                            return response.json();
                        })
                        .then(data => {
                            let totalCalc = parseFloat(document.getElementById('total').textContent.replace(
                                /,/g, ''));

                            if (data.tipo == "MONTOTOTAL") {
                                document.getElementById('deducido').value = (parseFloat(this.value) * data
                                    .monto);
                            }
                        })
                        .catch(error => {
                            console.error('Hubo un problema con la solicitud:', error);
                        });
                }
            }
        });
    </script>
@stop
