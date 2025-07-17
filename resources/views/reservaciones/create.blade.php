@extends('adminlte::page')

@section('title', 'Crear Reservación')

@section('content_header')
    <h1>Crear Nueva Reservación</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
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
    <div class="card">
        <form action="{{ route('reservaciones.store') }}" method="POST">
            @csrf
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div class="container form-group">
                        <small class="rounded p-2 text-primary bg-primary">Datos Generales del Cliente</small>
                    </div>
                    <input type="hidden" id="numeroreservacion" name="numeroreservacion">
                    <h5 id="numeroreservacionlabel" class="font-weight-bold">N°:</h5>
                </div>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-11">
                        <label for="cliente_id">NOMBRE DEL CLIENTE</label>
                        <select class="form-control" id="cliente_id" name="cliente_id" required>
                            <option value="">Seleccione un cliente</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}" data-dni="{{ $cliente->identificacion }}"
                                    data-telefono="{{ $cliente->telefono }}" data-email="{{ $cliente->email }}"
                                    {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->codigo_cliente }} - {{ $cliente->nombre_completo }}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div class="col-1 mt-4">
                        <label>&nbsp;</label>
                        <button id="btnAgregarCliente" type="button" class="btn mt-2 btn-success"><i
                                class="fas fa-user-plus"></i></button>
                    </div>
                    <div class="col-md-4 my-1">
                        <label for="cliente_dni">N° IDENTIFICACIÓN</label>
                        <input type="text" value="{{ old('cliente_dni') }}" class="form-control" name="cliente_dni"
                            id="cliente_dni" readonly>
                    </div>
                    <div class="col-md-3 my-1">
                        <label for="telefono">TELÉFONO</label>
                        <input type="text" value="{{ old('cliente_telefono') }}" class="form-control"
                            id="cliente_telefono" name="cliente_telefono" readonly>
                    </div>
                    <div class="col-md-5 my-1">
                        <label for="email">CORREO ELECTRÓNICO</label>
                        <input class="form-control" value="{{ old('cliente_email') }}" name="cliente_email"
                            id="cliente_email" readonly></input>
                    </div>
                </div>
            </div> <!-- Cierre del card-body -->


            <div class="card my-2">
                <div class="card-header">
                    <small class="bg-primary p-2 rounded text-primary">Información Detallada de la Reservación</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="fecha_entrada">Fecha de Entrada</label>
                            <input type="date" class="form-control" id="fecha_entrada" name="fecha_entrada"
                                value="{{ old('fecha_entrada') }}" min="{{ date('Y-m-d') }}" required>
                            @error('fecha_entrada')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="salida">Fecha de Salida</label>
                            <input type="date" class="form-control" id="fecha_salida"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}" name="salida" value="{{ old('salida') }}"
                                required>
                            @error('salida')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="habitacion">Habitación</label>
                            <select class="form-control" id="habitacion" name="habitacion_id">
                                <option value="">Seleccione una habitación.</option>
                                @foreach ($habitaciones as $habitacion)
                                    <option value="{{ $habitacion->id }}"
                                        {{ old('habitacion_id') == $habitacion->id ? 'selected' : '' }}
                                        data-precio="{{ $habitacion->precio_diario }}">
                                        Habitación N°: {{ $habitacion->numero_habitacion }} -
                                        {{ number_format($habitacion->precio_diario, 2) }}Lps / día
                                    </option>
                                @endforeach
                            </select>
                            @error('habitacion_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mt-3">
                            <label for="estado">Estado</label>
                            <select class="form-control" id="estado" name="estado">
                                <option value="">Seleccione un estado.</option>
                                <option value="CONFIRMADA" {{ old('estado') == 'CONFIRMADA' ? 'selected' : '' }}>CONFIRMADA
                                </option>
                                <option value="PENDIENTE" {{ old('estado') == 'PENDIENTE' ? 'selected' : '' }}>PENDIENTE
                                </option>
                            </select>
                            @error('estado')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Servicios Adicionales -->
                        <div class="col-12">
                            <div class="form-group mt-3">
                                <h5><strong>Servicios Adicionales</strong></h5>
                                <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Servicio</th>
                                                <th>Precio</th>
                                                <th>Cantidad</th>
                                                <th>Total</th>
                                                <th>Seleccionar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
    @foreach ($servicios as $servicio)
        <tr>
            <td>
                <input type="hidden" name="servicios[{{ $loop->iteration }}][nombre_servicio]" value="{{ $servicio->nombre }}">
                {{ $servicio->nombre }}
                <input type="hidden" value="{{ $servicio->id }}" name="servicios[{{ $loop->iteration }}][id]">
            </td>
            <td>
                <input type="hidden" name="servicios[{{ $loop->iteration }}][precio_servicio]" value="{{ $servicio->precio_venta }}">
                {{ $servicio->precio_venta }} Lps.
            </td>
            <td>
                <input type="number"
                    name="servicios[{{ $loop->iteration }}][cantidad]"
                    class="form-control" min="0"
                    value="{{ old('servicios.' . $loop->iteration . '.cantidad', 0) }}"
                    oninput="validarCantidad(this, {{ $servicio->id }}, {{ $servicio->tipo_producto == 'PRODUCTO FINAL' ? 'true' : 'false' }})">
            </td>
            <td>
                <input type="hidden" name="servicios[{{ $loop->iteration }}][total]" value="0">
                <span></span>
            </td>
            <td>
                <input type="checkbox"
                    name="servicios[{{ $loop->iteration }}][seleccionado]"
                    {{ old('servicios.' . $loop->iteration . '.seleccionado') == 1 ? 'checked' : '' }}
                    value="1">
            </td>
        </tr>
    @endforeach
</tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mt-3">
                            <label for="total">Total</label>
                            <input type="number" readonly class="form-control" id="total" name="total"
                                step="0.01" value="{{ old('total') }}" required>
                            @error('total')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8 mt-3">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control mb-1" oninput="this.value = this.value.toUpperCase()" id="observaciones"
                                name="observaciones" rows="3">{{ old('observaciones') }}</textarea>
                            @error('observaciones')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Registrar
                </button>
                <a href="{{ route('reservaciones.index') }}" class="btn text-light btn-warning">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>

    </div>
    </div>
    </form>
    </div> <!-- Cierre del card -->
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        .error-alert {
            display: none;
        }

        .error-alert.show {
            display: block;
        }

        .fila-desactivada {
            background-color: #f9f9f9;
            color: #b3b3b3;
        }

        .fila-desactivada input[type="number"] {
            pointer-events: none;
            background-color: #e9ecef;
        }

        .fila-desactivada td span {
            color: #b3b3b3;
        }
    </style>
@stop

@section('js')
    <script>
        async function verificarCantidadTabla(input, servicioId, esProductoFinal) {
            const cantidad = parseInt(input.value);
            const row = input.closest('tr');
            const checkbox = row.querySelector(`input[type="checkbox"]`);

            // Si la cantidad no es válida (vacía o menor o igual a 0)
            if (isNaN(cantidad) || cantidad <= 0) {
                input.value = "";
                input.setCustomValidity("Ingrese una cantidad válida.");
                input.reportValidity();
                if (checkbox) checkbox.disabled = true;
                return;
            }

            // Si no es producto final, no aplica validación de stock
            if (!esProductoFinal) {
                input.setCustomValidity("");
                if (checkbox) checkbox.disabled = false;
                return;
            }

            try {
                const response = await fetch(`/existencias-prod/${servicioId}/`);

                if (!response.ok) throw new Error("Error al consultar stock.");

                const stockDisponible = parseInt(await response.text());

                // Si excede el stock
                if (cantidad > stockDisponible) {
                    input.value = "";
                    input.setCustomValidity(`Solo hay ${stockDisponible} unidades disponibles.`);
                    input.reportValidity();
                    if (checkbox) checkbox.disabled = false;
                } else {
                    input.setCustomValidity("");
                    if (checkbox) checkbox.disabled = false;

                }
            } catch (error) {
                console.error(error);
                alert("Error al verificar el stock.");
                input.value = "";
                if (checkbox) checkbox.disabled = true;
            }
        }
    </script>
    <script>
        function calcularTotal() {
            let precioHabitacion = parseFloat(document.querySelector("#habitacion option:checked")?.getAttribute(
                'data-precio')) || 0;

            let fechaEntrada = new Date(document.getElementById('fecha_entrada')?.value);
            let fechaSalida = new Date(document.getElementById('fecha_salida')?.value);

            let diffTime = Math.abs(fechaSalida - fechaEntrada);
            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) || 0;

            let totalHabitacion = precioHabitacion * diffDays;

            let totalServicios = 0;
            let filas = document.querySelectorAll('table tbody tr');

            filas.forEach(fila => {
                let checkbox = fila.querySelector('input[type="checkbox"]');
                let cantidadInput = fila.querySelector('input[type="number"]');
                let precioElemento = fila.querySelector('td:nth-child(2)');
                let totalSpan = fila.querySelector('td:nth-child(4) span');
                let totalInput = fila.querySelector('td:nth-child(4) input');

                if (!checkbox || !cantidadInput || !precioElemento || !totalSpan || !totalInput) return;

                let precio = parseFloat(precioElemento.textContent.replace(' Lps.', '').trim().replace(',', '')) ||
                    0;

                if (checkbox.checked) {
                    fila.classList.remove('fila-desactivada');
                    cantidadInput.disabled = false;
                    let cantidad = parseInt(cantidadInput.value) || 0;
                    let totalServicio = cantidad * precio;
                    totalServicios += totalServicio;

                    totalSpan.textContent = totalServicio.toFixed(2);
                    totalInput.value = totalServicio.toFixed(2);
                } else {
                    fila.classList.add('fila-desactivada');
                    cantidadInput.disabled = true;
                    cantidadInput.value = "0";
                    totalSpan.textContent = '0.00';
                    totalInput.value = '0.00';
                }
            });

            let total = totalHabitacion + totalServicios;
            document.getElementById('total').value = isNaN(total) ? '0.00' : total.toFixed(2);
        }

        function toggleServicio(checkbox) {
            calcularTotal();
        }

        document.getElementById('fecha_entrada')?.addEventListener('change', calcularTotal);
        document.getElementById('fecha_salida')?.addEventListener('change', calcularTotal);
        document.getElementById('habitacion')?.addEventListener('change', calcularTotal);

        document.querySelectorAll('input[name^="servicios"][type="checkbox"]').forEach(input => {
            input.addEventListener('change', function() {
                toggleServicio(this);
            });
        });

        document.querySelectorAll('input[name^="servicios"][name$="[cantidad]"]').forEach(input => {
            input.addEventListener('input', calcularTotal);
        });

        window.onload = calcularTotal;
    </script>

    <script>
        function AgregarCodigoAutomatico() {
            const key = 'RESV';
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
                    document.getElementById('numeroreservacion').value = data.codigo;
                    document.getElementById('numeroreservacionlabel').innerText = "N°:" + data.codigo;
                })
                .catch(error => console.error('Error:', error));
        }
        AgregarCodigoAutomatico();
        document.getElementById('btnAgregarCliente').addEventListener('click', function() {
            var enlace = document.createElement('a');
            enlace.href = '{{ route('huespedes.create') }}';
            enlace.target = '_blank';
            document.body.appendChild(enlace);
            enlace.click();
            document.body.removeChild(enlace);
        });
    </script>
@stop
