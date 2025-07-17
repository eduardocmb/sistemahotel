@extends('adminlte::page')

@section('title', 'Editar Reservación')

@section('content_header')
    <div class="row">
        <div class="col-3">
            <h1>Editar Reservación</h1>
        </div>
        <div class="col-6">
            @if ($reservacion->estado == 'FINALIZADA' || $reservacion->estado == 'CANCELADA')
                <button id="BtnImprimir" type="button" class="rounded btn btn-secondary mx-1"><i class="fas fa-print"></i>
                    Imprimir Factura de Reservación</button>
            @endif
        </div>
    </div>
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
        <form action="{{ route('reservaciones.update', $reservacion->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div class="container form-group">
                        <small class="rounded p-2 text-primary bg-primary">Datos Generales del Cliente</small>
                    </div>
                    <h5 class="font-weight-bold">N°:{{ $reservacion->numero }}</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <label for="cliente_id">NOMBRE DEL CLIENTE</label>
                        <select disabled class="form-control" id="cliente_id" name="cliente_id" required>
                            <option value="">Seleccione un cliente</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}" data-dni="{{ $cliente->identificacion }}"
                                    data-telefono="{{ $cliente->telefono }}" data-email="{{ $cliente->email }}"
                                    {{ old('cliente_id', $reservacion->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->codigo_cliente }} - {{ $cliente->nombre_completo }}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 my-1">
                        <label for="cliente_dni">N° IDENTIFICACIÓN</label>
                        <input type="text" value="{{ old('cliente_dni', $reservacion->cliente->identificacion) }}"
                            class="form-control" name="cliente_dni" id="cliente_dni" readonly>
                    </div>
                    <div class="col-md-3 my-1">
                        <label for="telefono">TELÉFONO</label>
                        <input type="text" value="{{ old('cliente_telefono', $reservacion->cliente->telefono) }}"
                            class="form-control" id="cliente_telefono" name="cliente_telefono" readonly>
                    </div>
                    <div class="col-md-5 my-1">
                        <label for="email">CORREO ELECTRÓNICO</label>
                        <input class="form-control" value="{{ old('cliente_email', $reservacion->cliente->email) }}"
                            name="cliente_email" id="cliente_email" readonly></input>
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
                            <input readonly type="date" class="form-control" id="fecha_entrada" name="fecha_entrada"
                                value="{{ old('fecha_entrada', $reservacion->fecha_entrada) }}" required>
                            @error('fecha_entrada')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="salida">Fecha de Salida</label>
                            <input
                                {{ $reservacion->estado == 'CONFIRMADA' || $reservacion->estado == 'PENDIENTE' ? '' : 'readonly' }}
                                type="date" class="form-control" id="fecha_salida" name="salida"
                                value="{{ old('salida', $reservacion->salida) }}" required>
                            @error('salida')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mt-3">
                            <label for="habitacion">Habitación</label>
                            <select disabled class="form-control" id="habitacion" name="habitacion_id">
                                <option value="">Seleccione una habitación.</option>
                                @foreach ($habitaciones as $habitacion)
                                    <option value="{{ $habitacion->id }}"
                                        {{ old('habitacion_id', $reservacion->habitacion_id) == $habitacion->id ? 'selected' : '' }}
                                        data-precio="{{ $habitacion->precio_diario }}">
                                        Habitación N°: {{ $habitacion->numero_habitacion }} -
                                        {{ number_format($habitacion->precio_diario, 2) }} Lps / día
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
                                <option value="CONFIRMADA"
                                    {{ old('estado', $reservacion->estado) == 'CONFIRMADA' ? 'selected' : '' }}>CONFIRMADA
                                </option>
                                @if ($facturada_flag == false)
                                    <option value="PENDIENTE"
                                        {{ old('estado', $reservacion->estado) == 'PENDIENTE' ? 'selected' : '' }}>
                                        PENDIENTE
                                    </option>
                                @endif
                                <option value="CANCELADA"
                                    {{ old('estado', $reservacion->estado) == 'CANCELADA' ? 'selected' : '' }}>CANCELADA
                                </option>
                                <option value="FINALIZADA"
                                    {{ old('estado', $reservacion->estado) == 'FINALIZADA' ? 'selected' : '' }}>FINALIZADA
                                </option>

                            </select>
                            @error('estado')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-12">
                            <div class="form-group mt-3">
                                <h5><strong>Servicios Adicionales</strong></h5>
                                <div class="text-info">
                                    <h5>Nota: Los servicios previamente adquiridos no se pueden eliminar.</h5>
                                </div>
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
                                            @php $index = 1; @endphp
                                            @foreach ($servicios as $servicio)
                                                @php
                                                    $servicioAdquirido = $serviciosadquiridos->firstWhere(
                                                        'producto_id',
                                                        $servicio->id,
                                                    );
                                                    $cantidad = $servicioAdquirido ? $servicioAdquirido->cantidad : 0;
                                                    $total = $servicioAdquirido ? $servicioAdquirido->total : 0;
                                                    $esAdquirido = $servicioAdquirido !== null;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="servicios[{{ $index }}][codigo]"
                                                            value="{{ $servicio->codigo }}">
                                                        <input type="hidden"
                                                            name="servicios[{{ $index }}][nombre_servicio]"
                                                            value="{{ $servicio->nombre }}">
                                                        {{ $servicio->nombre }} <span
                                                            class="text-danger">{{ $servicio->del === 'S' ? '(Eliminado)' : '' }}</span>
                                                        <input type="hidden" value="{{ $servicio->id }}"
                                                            name="servicios[{{ $index }}][id]" class="servicio-id"
                                                            data-servicio-id="{{ $servicio->id }}">
                                                    </td>
                                                    <td>
                                                        <input type="hidden"
                                                            name="servicios[{{ $index }}][precio_servicio]"
                                                            value="{{ $servicio->precio_venta }}">
                                                        {{ $servicio->precio_venta }} Lps.
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                            name="servicios[{{ $index }}][cantidad]"
                                                            class="form-control" min="0"
                                                            value="{{ old('servicios.' . $index . '.cantidad', $cantidad) }}"
                                                            {{ $esAdquirido ? 'readonly' : '' }}
                                                            oninput="verificarCantidadTabla(this, {{ $servicio->id }}, {{ $servicio->tipo_producto == 'PRODUCTO FINAL' ? 'true' : 'false' }})">
                                                    </td>
                                                    <td>
                                                        <input type="hidden"
                                                            name="servicios[{{ $index }}][total]"
                                                            value="{{ old('servicios.' . $index . '.total', $total) }}">

                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <span>{{ $total }} Lps.</span>
                                                            @if ($esAdquirido)
                                                                <button type="button" class="btn btn-success btn-sm ml-2"
                                                                    data-toggle="modal"
                                                                    data-target="#modalAumentar{{ $servicio->id }}">
                                                                    +
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>


                                                    <td>
                                                        <input type="checkbox"
                                                            name="servicios[{{ $index }}][seleccionado]"
                                                            value="1" {{ $esAdquirido ? 'checked disabled' : '' }}>
                                                    </td>
                                                </tr>

                                                @if ($servicioAdquirido)
                                                    <div class="modal fade" id="modalAumentar{{ $servicio->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="modalLabel{{ $servicio->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-success text-white">
                                                                    <h5 class="modal-title"
                                                                        id="modalLabel{{ $servicio->id }}">
                                                                        Aumentar cantidad - {{ $servicio->nombre }}
                                                                    </h5>
                                                                    <button type="button" class="close text-white"
                                                                        data-dismiss="modal" aria-label="Cerrar">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <label>¿Cuántas unidades desea agregar?</label>
                                                                    <input type="number" class="form-control"
                                                                        min="1"
                                                                        id="aumento-cantidad-{{ $servicio->id }}"
                                                                        oninput="verificarCantidadDisponible(this, {{ $servicio->id }}, {{ $servicio->tipo_producto == 'PRODUCTO FINAL' ? 'true' : 'false' }})">

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Cancelar</button>
                                                                    <button type="button" class="btn btn-success"
                                                                        id="btnGuardarCambios-{{ $servicio->id }}"
                                                                        onclick="sumarCantidad({{ $servicio->id }}, {{ $servicio->precio_venta }})">
                                                                        Guardar cambios
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @php $index++; @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label for="total">Total</label>
                            <input type="number" readonly class="form-control" id="total" name="total"
                                step="0.01" value="{{ old('total', $reservacion->total) }}" required>
                            @error('total')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-8 mt-3">
                            <label for="observaciones">Observaciones</label>
                            <textarea oninput="this.value = this.value.toUpperCase()" class="form-control" id="observaciones"
                                name="observaciones" rows="3">{{ old('observaciones', $reservacion->observaciones) }}</textarea>
                            @error('observaciones')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Actualizar
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
            setTimeout(() => {
                document.getElementById('total').value = total.toFixed(2);
            }, 001);
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

        document.getElementById('BtnImprimir').addEventListener('click', function() {
            var enlace = document.createElement('a');
            enlace.href = '{{ route('rptReservacionIn', $reservacion->numero) }}';
            enlace.target = '_blank';
            document.body.appendChild(enlace);
            enlace.click();
            document.body.removeChild(enlace);
        });
        calcularTotal();
    </script>
    <script>
        async function verificarCantidadDisponible(input, servicioId, esProductoFinal) {
            const cantidadExtra = parseInt(input.value);
            const btnGuardar = document.querySelector(`#btnGuardarCambios-${servicioId}`);

            if (isNaN(cantidadExtra) || cantidadExtra <= 0) {
                input.setCustomValidity("Cantidad inválida");
                btnGuardar.disabled = true;
                return;
            }

            const inputId = document.querySelector(`input.servicio-id[data-servicio-id="${servicioId}"]`);
            const row = inputId.closest('tr');
            const inputCantidad = row.querySelector(`input[name^="servicios"][name$="[cantidad]"]`);
            const cantidadActual = parseInt(inputCantidad.value) || 0;
            const nuevaCantidad = cantidadActual + cantidadExtra;

            // Si NO es producto final, no validamos stock
            if (!esProductoFinal) {
                input.setCustomValidity("");
                btnGuardar.disabled = false;
                return;
            }

            try {
                const response = await fetch(`/existencias-prod/${servicioId}/`);

                if (!response.ok) throw new Error("Error al consultar el stock.");

                const stockTexto = await response.text();
                const stockDisponible = parseInt(stockTexto);

                if (nuevaCantidad > stockDisponible) {
                    input.setCustomValidity(`Error, solo hay: ${stockDisponible} unidades disponibles.`);
                    input.reportValidity();
                    btnGuardar.disabled = true;
                } else {
                    input.setCustomValidity("");
                    btnGuardar.disabled = false;
                }
            } catch (error) {
                console.error(error);
                alert("No se pudo verificar el stock disponible.");
                btnGuardar.disabled = true;
            }
        }
    </script>

    <script>
        function sumarCantidad(servicioId, precio) {
            const inputAumento = document.getElementById(`aumento-cantidad-${servicioId}`);
            const cantidadExtra = parseInt(inputAumento.value);

            if (isNaN(cantidadExtra) || cantidadExtra <= 0) {
                alert("Ingrese una cantidad válida.");
                return;
            }

            const inputId = document.querySelector(`input.servicio-id[data-servicio-id="${servicioId}"]`);
            if (!inputId) {
                alert("No se pudo encontrar el servicio.");
                return;
            }

            const row = inputId.closest('tr');
            const inputCantidad = row.querySelector(`input[name^="servicios"][name$="[cantidad]"]`);
            const inputTotal = row.querySelector(`input[name^="servicios"][name$="[total]"]`);
            const spanTotal = row.querySelector('td:nth-child(4) span');

            let cantidadActual = parseInt(inputCantidad.value) || 0;
            let nuevaCantidad = cantidadActual + cantidadExtra;
            let nuevoTotal = nuevaCantidad * precio;

            inputCantidad.value = nuevaCantidad;
            inputTotal.value = nuevoTotal.toFixed(2);
            spanTotal.innerText = `${nuevoTotal.toFixed(2)} Lps.`;

            $(`#modalAumentar${servicioId}`).modal('hide');

            calcularTotal();
        }
    </script>
@stop
