@extends('adminlte::page')

@section('title', ':::Facturacion Reservaciones:::')

@section('content_header')
    <h1>Facturacion</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
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
                </div>
            </div>
            <div class="btn-group" role="group" aria-label="Acciones">
                <button id="BtnGuardar" type="button" class="rounded btn btn-primary"><i class="fas fa-save"></i>
                    Guardar</button>
                <button id="BtnBuscar" type="button" class="rounded btn btn-info ml-2"><i class="fas fa-search"></i>
                    Buscar</button>
                <button id="BtnActualizar" type="button" class="rounded btn btn-success ml-2"><i class="fas fa-sync"></i>
                    Actualizar</button>
                <button id="BtnEliminar" type="button" class="rounded btn btn-danger ml-2"><i class="fas fa-trash"></i>
                    Eliminar</button>
                <button id="BtnImprimir" type="button" class="rounded btn btn-secondary ml-2"><i class="fas fa-print"></i>
                    Imprimir</button>
                <button id="BtnAnular" data-toggle="modal" data-target="#confirmAnularModal" type="button"
                    class="rounded text-light btn btn-warning ml-2"><i class="fas fa-ban"></i>
                    Anular</button>
                <button id="BtnCancelar" type="button" style="background-color: #a107fa"
                    class="rounded text-light btn ml-2"><i class="fas fa-times"></i> Cancelar</button>
                <a id="BtnSalir" href="{{ route('dashboard') }}" class="rounded btn btn-dark ml-2 disabled-link">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>

        </div>
        <div class="card-body">
            <form action="{{ route('facturacionreservacionesin.store') }}" id="formId" method="POST">
                @csrf
                <input type="hidden" name="reservacion_id" value="{{ $reservacion->id }}">
                <input type="hidden" name="reservacion_entrada" value="{{ $reservacion->fecha_entrada }}">
                <input type="hidden" name="reservacion_salida" value="{{ $reservacion->salida }}">
                <input type="hidden" name="num_reservacion" value="{{ $reservacion->numero }}">

                <div class="form-row">
                    <div class="col-md-12">
                        <div id="error" class="mt-2"></div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="cliente">Cliente</label>
                        <div class="input-group">
                            <input readonly value="{{ $cliente->nombre_completo }}" type="text" class="form-control"
                                id="cliente" placeholder="Cliente">
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="tipoPago">Tipo de Pago</label>
                        <select id="tipoPago" name="tipopago" class="form-control">
                            <option value="">ELEGIR...</option>
                            <option value="EFECTIVO">EFECTIVO</option>
                            <option value="TARJETA">TARJETA</option>
                            <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Número</label>
                        <input type="hidden" class="form-control" id="numero" name="numero">
                        <h3 class="text-center text-light"><label id="numerolabel" style="background-color: #20c997"
                                class="w-100 p-1 rounded">------</label></h3>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>RTN</label>
                        <input type="hidden" value="{{ $cliente->rtn }}" class="form-control" id="rtn"
                            name="rtn">
                        <h3 class="text-center text-light">
                            <label id="rtnlabel" style="background-color: #20c997"
                                class="w-100 p-1 rounded">{{ $cliente->rtn }}</label>
                        </h3>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Código</label>
                        <input value="{{ $cliente->codigo_cliente }}" type="hidden" class="form-control"
                            id="codigo" name="codigocliente">
                        <h3 class="text-center text-light">
                            <label id="codigolabel" style="background-color: #20c997"
                                class="w-100 p-1 rounded">{{ $cliente->codigo_cliente }}</label>
                        </h3>
                    </div>

                    <div class="form-group col-md-2">
                        <label>Fecha</label>
                        <input type="hidden" class="form-control"
                            value="{{ \Carbon\Carbon::today()->format('d/m/Y') }}" id="fecha" name="fecha">
                        <h3 class="text-center text-light">
                            <label style="background-color: #20c997"
                                class="w-100 p-1 rounded">{{ \Carbon\Carbon::today()->format('d/m/Y') }}</label>
                        </h3>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Caja</label>
                        <input type="hidden" class="form-control" id="caja" name="caja">
                        <h3 class="text-center text-light">
                            <label id="cajalabel" style="background-color: #20c997" class="w-100 p-1 rounded">---</label>
                        </h3>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Turno</label>
                        <input type="hidden" class="form-control" id="turno" name="turno">
                        <h3 class="text-center text-light">
                            <label id="turnolabel" style="background-color: #20c997"
                                class="w-100 p-1 rounded">-----</label>
                        </h3>
                    </div>
                </div>

                <!-- Tabla de productos -->
                <div class="card mb-4">
                    <div style=" background-color: #20c997" class="card-header text-white">PRODUCTOS AGREGADOS</div>
                    <div class="card-body">
                        <table class="table table-bordered" id="tabla_productos">
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th>Dias</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Descuento</th>
                                    <th>Total</th>
                                    @if ($existe_flag == false)
                                        <th>Acciones</th>
                                    @endif
                                </tr>
                            </thead>
                            @php
                                $fecha_entrada = \Carbon\Carbon::parse($reservacion->fecha_entrada);
                                $fecha_salida = \Carbon\Carbon::parse($reservacion->salida);
                                $dias = $fecha_entrada->diffInDays($fecha_salida);
                                if ($dias == 0) {
                                    $dias = 1;
                                }
                                $precio = (float) $habitacion->precio_diario;
                                $total = $dias * $precio;
                            @endphp

                            <tbody>
                                <tr data-index="1">
                                    <td><input type="hidden" name="habitacion_id" value="{{ $habitacion->id }}">
                                        Habitación N°: {{ $habitacion->numero_habitacion }}</td>
                                    <td><input name="dias_estadia" type="hidden"
                                            value="{{ \Carbon\Carbon::parse($reservacion->fecha_entrada)->diffInDays(\Carbon\Carbon::parse($reservacion->salida)) }}">
                                        {{ \Carbon\Carbon::parse($reservacion->fecha_entrada)->diffInDays(\Carbon\Carbon::parse($reservacion->salida)) }}
                                    </td>
                                    <td>----</td>
                                    <td>{{ $habitacion->precio_diario }}</td>
                                    <td>{{ $existe_flag === false ? '0.00' : $detallesfactura[0]->descto }}</td>
                                    <td>{{ $existe_flag === false ? $total : $total-$detallesfactura[0]->descto}}</td>

                                    @if (!$existe_flag)
                                        <td>
                                            <input type="hidden" name="index[1]" value="1">
                                            <input type="hidden" name="cantidad[1]" value="1">
                                            <input type="hidden" name="descuento[1]" value="0">
                                            <input type="hidden" name="total[1]"
                                                value="{{ number_format($total, 2) }}">
                                            <button type="button"
                                                class="btn btn-warning text-light btn-sm editar-servicio"
                                                data-nombre="{{ $habitacion->numero_habitacion }}"
                                                data-dia="{{ $dias }}" data-index="1"
                                                data-precio="{{ $habitacion->precio_diario }}"
                                                data-total="{{ number_format($total, 2) }}" id="btneditar[1]">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                        </td>
                                    @endif

                                </tr>
                                @php $servicioIndex = 2; @endphp
                                @foreach ($serviciosadquiridos as $servicio)
                                    <tr data-index="{{ $servicioIndex }}">
                                        <td>{{ $servicio->nombre }}</td>
                                        <td>----</td>
                                        <td>{{ $servicio->cantidad }}</td>
                                        <td>{{ $servicio->precio_venta }}</td>
                                        <td>{{ !$existe_flag || !isset($detallesfactura[$servicioIndex - 2]) ? '0.00' : $detallesfactura[$servicioIndex - 2]->descto }}
                                        </td>
                                        <td>{{ !$existe_flag || !isset($detallesfactura[$servicioIndex - 2]) ? $servicio->subtotal : $detallesfactura[$servicioIndex - 2]->total }}
                                        </td>

                                        @if (!$existe_flag)
                                            <td>
                                                <input data-isv="{{ $servicio->porcentaje }}" type="hidden"
                                                    name="index[{{ $servicioIndex }}]" value="{{ $servicioIndex }}">
                                                <input type="hidden" name="servicio_id[{{ $servicioIndex }}]"
                                                    value="{{ $servicio->producto_id }}">
                                                <input type="hidden" name="cantidad[{{ $servicioIndex }}]"
                                                    value="{{ $servicio->cantidad }}">
                                                <input type="hidden" name="descuento[{{ $servicioIndex }}]"
                                                    value="0.00">
                                                <input type="hidden" name="total[{{ $servicioIndex }}]"
                                                    value="{{ $servicio->subtotal }}">

                                                <button type="button"
                                                    class="btn btn-warning text-light btn-sm editar-servicio"
                                                    data-nombre="{{ $servicio->nombre }}"
                                                    data-index="{{ $servicioIndex }}"
                                                    data-cantidad="{{ $servicio->cantidad }}"
                                                    data-precio="{{ $servicio->precio_venta }}"
                                                    data-total="{{ $servicio->precio_venta }}"
                                                    id="btneditar[{{ $servicioIndex }}]">
                                                    <i class="fas fa-edit"></i> Editar
                                                </button>
                                            </td>
                                        @else
                                            <td>
                                                <input data-isv="{{ $servicio->porcentaje }}" type="hidden"
                                                    name="index[{{ $servicioIndex }}]" value="{{ $servicioIndex }}">
                                                <input type="hidden" name="servicio_id[{{ $servicioIndex }}]"
                                                    value="{{ $servicio->producto_id }}">
                                                <input type="hidden" name="cantidad[{{ $servicioIndex }}]"
                                                    value="{{ $servicio->cantidad }}">
                                                <input type="hidden" name="descuento[{{ $servicioIndex }}]"
                                                    value="{{ isset($detallesfactura[$servicioIndex - 2]) ? $detallesfactura[$servicioIndex - 2]->descto : '0.00' }}">
                                                <input type="hidden" name="total[{{ $servicioIndex }}]"
                                                    value="{{ isset($detallesfactura[$servicioIndex - 2]) ? $detallesfactura[$servicioIndex - 2]->total : $servicio->subtotal }}">
                                            </td>
                                        @endif

                                    </tr>
                                    @php $servicioIndex++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="form-row mt-3">
                    <div class="form-group col-md-3">
                        <label>Importe Exonerado</label>
                        <input type="hidden" class="form-control" id="importeExonerado" name="importeExonerado">
                        <h3 class="text-center text-light">
                            <label id="LblImpExon" style="background-color: #20c997"
                                class="w-100 p-1 rounded">0.00</label>
                        </h3>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Importe Exento</label>
                        <input type="hidden" class="form-control" id="importeExento" name="importeExento">
                        <h3 class="text-center text-light">
                            <label id="LblImpExen" style="background-color: #20c997"
                                class="w-100 p-1 rounded">0.00</label>
                        </h3>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Importe Grav 15%</label>
                        <input type="hidden" class="form-control" id="importeGrav15" name="importeGrav15">
                        <h3 class="text-center text-light">
                            <label id="LblImpGrav15" style="background-color: #20c997"
                                class="w-100 p-1 rounded">0.00</label>
                        </h3>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Importe Grav 18%</label>
                        <input type="hidden" class="form-control" id="importeGrav18" name="importeGrav18">
                        <h3 class="text-center text-light">
                            <label id="LblImpGrav18" style="background-color: #20c997"
                                class="w-100 p-1 rounded">0.00</label>
                        </h3>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>ISV 15%</label>
                        <input type="hidden" class="form-control" id="isv15" name="isv15">
                        <h3 class="text-center text-light">
                            <label id="LblIsv15" style="background-color: #20c997"
                                class="w-100 p-1 rounded">0.00</label>
                        </h3>
                    </div>
                    <div class="form-group col-md-3">
                        <label>ISV 18%</label>
                        <input type="hidden" class="form-control" id="isv18" name="isv18">
                        <h3 class="text-center text-light">
                            <label id="LblIsv18" style="background-color: #20c997"
                                class="w-100 p-1 rounded">0.00</label>
                        </h3>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Descuento</label>
                        <input type="hidden" class="form-control" id="descuento" name="descuento_general">
                        <h3 class="text-center text-light">
                            <label id="LblDescto" style="background-color: #20c997"
                                class="w-100 p-1 rounded">0.00</label>
                        </h3>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Total</label>
                        <input type="hidden" class="form-control" id="total" name="total">
                        <h3 class="text-center text-light">
                            <label id="LblTotal" style="background-color: #20c997"
                                class="w-100 p-1 rounded">0.00</label>
                        </h3>
                    </div>
                </div>
                <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmModalLabel">Confirmación</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Total de la compra: <strong id="totalCompra">0.00</strong> Lps</p>
                                <div class="row">
                                    <div class="col-12">
                                        <div id="errorConfirm"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="receivedAmount">Dinero recibido:</label>
                                    <input type="hidden" name="montorecibido" id="montorecibido">
                                    <input type="number" id="receivedAmount" class="form-control"
                                        placeholder="Ingresa la cantidad recibida">
                                </div>
                                <input type="hidden" name="cambioadar" id="cambioadar">
                                <p>El cambio a devolver es: <strong id="changeAmount">0.00</strong> Lps</p>
                                <p>¿Estás seguro de que deseas guardar la compra?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="button" id="confirmSave" class="btn btn-primary">Confirmar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('search/facturacion/css/index.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap4.min.css">
    <style>
        #BtnCancelar:hover {
            background-color: #7f00c9 !important;
        }

        .disabled-link {
            pointer-events: none;
            color: rgb(223, 223, 223);
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>
    <script>
        const btnGuardar = document.getElementById("BtnGuardar");
        const btnBuscar = document.getElementById("BtnBuscar");
        const btnActualizar = document.getElementById("BtnActualizar");
        const btnEliminar = document.getElementById("BtnEliminar");
        const btnImprimir = document.getElementById("BtnImprimir");
        const btnAnular = document.getElementById("BtnAnular");
        const btnCancelar = document.getElementById("BtnCancelar");
        const btnSalir = document.getElementById("BtnSalir");
        const scltTipoPago = document.getElementById('tipoPago');
        const txtcliente = document.getElementById('cliente');

        const errorContainer = document.getElementById('error');

        function setLoadForm() {
            document.getElementById("BtnSalir").setAttribute("href", "{{ route('dashboard') }}");
            document.getElementById("BtnSalir").classList.remove("disabled-link");
            btnGuardar.disabled = true;
            btnBuscar.disabled = false;
            btnActualizar.disabled = true;
            btnEliminar.disabled = true;
            btnImprimir.disabled = true;
            btnAnular.disabled = false;
            btnCancelar.disabled = true;
            btnSalir.disabled = false;

            const inputsVisibles = Array.from(document.querySelectorAll('input:not([type="hidden"]):not([readonly])'));
            inputsVisibles.forEach(input => {
                input.readOnly = true;
            });
            scltTipoPago.disabled = true;
            document.getElementById('numero').value = "";
            document.getElementById('numerolabel').innerText = "------";
            document.getElementById('caja').value = "";
            document.getElementById('cajalabel').innerText = "------";
            document.getElementById('turno').value = "";
            document.getElementById('turnolabel').innerText = "------";

            document.getElementById('tabla_productos').innerHTML = `<thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Descuento</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>`;
            document.getElementById('LblImpExen').textContent = '';
            document.getElementById('importeExento').value = '';
            document.getElementById('LblImpExon').textContent = '';
            document.getElementById('importeExonerado').value = '';
            document.getElementById('LblImpGrav15').textContent = '';
            document.getElementById('importeGrav15').value = '';
            document.getElementById('LblImpGrav18').textContent = '';
            document.getElementById('importeGrav18').value = '';
            document.getElementById('LblIsv15').textContent = '';
            document.getElementById('isv15').value = '';
            document.getElementById('LblIsv18').textContent = '';
            document.getElementById('isv18').value = '';
            document.getElementById('LblDescto').textContent = '';
            document.getElementById('descuento').value = '';
            document.getElementById('LblTotal').textContent = '';
            document.getElementById('total').value = '';
        }
        //setLoadForm();

        async function verificarAperturaCaja() {
            try {
                const responseApertura = await fetch('/verificar-apertura-caja');
                const dataApertura = await responseApertura.json();

                if (!dataApertura.apertura_abierta) {
                    errorContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>No se encontró una apertura de caja abierta para el usuario: "{{ \Illuminate\Support\Facades\Auth::user()->username }}" con la fecha de hoy.</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                    setLoadForm();
                    return;
                }

                document.getElementById("caja").value = dataApertura.caja_id;
                document.getElementById("cajalabel").innerText = dataApertura.caja;
                document.getElementById('turno').value = dataApertura.turno_id;
                document.getElementById('turnolabel').innerText = dataApertura.turno;

                const responseCais = await fetch('/verificar-cais-sar/CAIS');
                const dataCais = await responseCais.json();

                if (!dataCais) {
                    errorContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>No se encontró un CAI activo.</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                    setLoadForm();
                    return;
                }

                const responseCorrelativo = await fetch('/traer-next-correlativo-fact/SAR');
                const dataCorrelativoText = await responseCorrelativo.text();

                if (dataCorrelativoText) {
                    document.getElementById('numero').value = dataCorrelativoText;
                    document.getElementById('numerolabel').innerText = dataCorrelativoText;
                } else {
                    displayError('No se pudo obtener el correlativo.');
                }

            } catch (error) {
                console.error('Error:', error);
            }
        }

        @if ($existe_flag)
            document.getElementById("BtnSalir").setAttribute("href", "{{ route('dashboard') }}");
            document.getElementById("BtnSalir").classList.remove("disabled-link");
            btnGuardar.disabled = true;
            btnBuscar.disabled = false;
            btnActualizar.disabled = true;
            btnEliminar.disabled = true;
            btnImprimir.disabled = false;
            btnAnular.disabled = false;
            btnCancelar.disabled = true;
            btnSalir.disabled = false;

            const inputsVisibles = Array.from(document.querySelectorAll('input:not([type="hidden"]):not([readonly])'));
            inputsVisibles.forEach(input => {
                input.readOnly = true;
            });
            scltTipoPago.disabled = true;
            document.getElementById('numero').value = "";
            document.getElementById('numerolabel').innerText = "------";
            document.getElementById('caja').value = "";
            document.getElementById('cajalabel').innerText = "{{ $caja }}";
            document.getElementById('turno').value = "";
            document.getElementById('turnolabel').innerText = '{{ $turno }}'
            document.getElementById('numerolabel').textContent = '{{ $factura->factnum }}';
            document.getElementById('tipoPago').value = '{{ $factura->pago }}';
            document.getElementById('LblImpExen').textContent = '{{ $factura->impoexen }}';
            document.getElementById('importeExento').value = '{{ $factura->impoexen }}';
            document.getElementById('LblImpExon').textContent = '{{ $factura->impoexon }}';
            document.getElementById('importeExonerado').value = '{{ $factura->impoexon }}';
            document.getElementById('LblImpGrav15').textContent = '{{ $factura->impograv15 }}';
            document.getElementById('importeGrav15').value = '{{ $factura->impograv15 }}';
            document.getElementById('LblImpGrav18').textContent = '{{ $factura->impograv18 }}';
            document.getElementById('importeGrav18').value = '{{ $factura->impograv18 }}';
            document.getElementById('LblIsv15').textContent = '{{ $factura->isv15 }}';
            document.getElementById('isv15').value = '{{ $factura->isv15 }}';
            document.getElementById('LblIsv18').textContent = '{{ $factura->isv18 }}';
            document.getElementById('isv18').value = '{{ $factura->isv18 }}';
            document.getElementById('LblDescto').textContent = '{{ $factura->descto }}';
            document.getElementById('descuento').value = '0.00';
            document.getElementById('LblTotal').textContent = '{{ $factura->total }}';
            document.getElementById('total').value = '0.00';
        @elseif (!$existe_flag)
            CargarDatosReservacion();
        @endif

        function CargarDatosReservacion() {
            errorContainer.innerHTML = '';
            verificarAperturaCaja();
            document.getElementById("BtnSalir").removeAttribute("href");
            btnGuardar.disabled = false;
            btnBuscar.disabled = true;
            btnActualizar.disabled = true;
            btnEliminar.disabled = true;
            btnImprimir.disabled = true;
            btnAnular.disabled = true;
            btnCancelar.disabled = false;
            btnSalir.disabled = true;
            const inputsReadonly = Array.from(document.querySelectorAll('input:not([type="hidden"])[readonly]'));
            inputsReadonly.forEach(input => {
                input.readOnly = false;
            });
            txtcliente.readOnly = true;
            scltTipoPago.disabled = false;

            getExpireCaiData();
            async function getExpireCaiData() {
                const url = "/get-expiredate-rest-currentcai";
                try {
                    const response = await fetch(url);
                    if (!response.ok) {
                        throw new Error(`Response status: ${response.status}`);
                    }

                    const json = await response.json();
                    if (parseInt(json.restantes) <= 0) {
                        const errorContainer = document.getElementById('error');
                        errorContainer.innerHTML = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> Has alcanzado el límite de facturación disponible.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        setLoadForm();
                        return;
                    }

                    if (new Date(json.cai.fechalimite) < new Date(
                            "{{ \Carbon\Carbon::today()->toDateString() }}")) {
                        const errorContainer = document.getElementById('error');
                        errorContainer.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> La fecha límite de facturación fue en: <strong>${json.cai.fechalimite}</strong>.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `;
                        setLoadForm();
                        return;
                    }

                    const today = new Date("{{ \Carbon\Carbon::today()->toDateString() }}");
                    const limitDate = new Date(json.cai.fechalimite);
                    const thirtyOneDaysLater = new Date(today);
                    thirtyOneDaysLater.setDate(today.getDate() + 31);

                    const errorContainer = document.getElementById('error');
                    let errorMessages = '';

                    if (errorContainer.innerHTML.trim() === '') {
                        if (limitDate <= thirtyOneDaysLater) {
                            errorMessages += `
                        <div class="alert text-light alert-warning alert-dismissible fade show" role="alert">
                            <strong>Advertencia!</strong> La fecha límite de facturación está próxima: ${json.cai.fechalimite}.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `;
                        }

                        if (parseInt(json.restantes) <= 100) {
                            errorMessages += `
                            <div class="alert text-light alert-warning alert-dismissible fade show" role="alert">
                                <strong>Advertencia!</strong> Estás llegando al límite de facturación, te quedan: (${parseInt(json.restantes)})
                                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                        }

                        if (errorMessages) {
                            errorContainer.innerHTML = errorMessages;
                        }
                    } else {
                        console.warn('Ya existe un mensaje de error mostrado. No se añadieron nuevos mensajes.');
                    }
                } catch (error) {
                    console.error(error.message);
                }

                getInfoHotel();
                async function getInfoHotel() {
                    const url = '{{ route('getInfoHotel') }}';
                    try {
                        const response = await fetch(url, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        });

                        if (!response.ok) {
                            const data = await response.json();
                            console.error('Error:', data.message);

                            const _errorContainer = document.getElementById('error');
                            _errorContainer.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> ${data.message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `;
                            setLoadForm();
                            return;
                        }
                    } catch (error) {
                        console.error('Error en la solicitud:', error);
                    }
                }
            }
            let totalFactura = 0;
            document.querySelectorAll('input[name^="total"]').forEach((input) => {
                //  console.log(input.value);

                totalFactura += parseFloat(input.value.replace(',', '')) || 0;
            });
            document.getElementById('LblTotal').textContent = totalFactura.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        };

        btnCancelar.addEventListener('click', function() {
            setLoadForm();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formId');
            const errorContainer = document.getElementById('error');
            const confirmModal = new bootstrap.Modal(document.getElementById(
                'confirmModal'));

            function displayError(message) {
                if (!errorContainer.innerHTML) {
                    errorContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>${message}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                }
            }

            btnGuardar.addEventListener('click', async function(event) {
                getExpireCaiData();
                async function getExpireCaiData() {
                    const url = "/get-expiredate-rest-currentcai";
                    try {
                        const response = await fetch(url);
                        if (!response.ok) {
                            throw new Error(`Response status: ${response.status}`);
                        }

                        const json = await response.json();

                        if (parseInt(json.restantes) <= 0) {
                            const errorContainer = document.getElementById('error');
                            errorContainer.innerHTML = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> Has alcanzado el límite de facturación disponible.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;
                            setLoadForm();
                            event.preventDefault();
                            return;
                        }

                        if (new Date(json.cai.fechalimite) < new Date(
                                "{{ \Carbon\Carbon::today()->toDateString() }}")) {
                            const errorContainer = document.getElementById('error');
                            errorContainer.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> La fecha límite de facturación fue en: <strong>${json.cai.fechalimite}</strong>.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `;
                            setLoadForm();
                            event.preventDefault();
                            return;
                        }

                    } catch (error) {
                        console.error(error.message);
                    }
                }
                const tablaProductos = document.querySelector('#tabla_productos tbody');
                const tieneProductos = tablaProductos && tablaProductos.rows.length > 0;
                errorContainer.innerHTML = "";
                document.getElementById('tipoPago');
                const validacionExitosa =
                    await validarDatos();
                if (!validacionExitosa || !tieneProductos) {
                    event.preventDefault();
                    return;
                }

                const totalCompra = parseFloat(document.getElementById('LblTotal').textContent.replace(
                    /,/g, '')) || 0;
                document.getElementById('total').value = totalCompra;
                const totalCompraText = document.getElementById('totalCompra');
                const receivedAmountInput = document.getElementById('receivedAmount');
                const changeAmountText = document.getElementById('changeAmount');

                totalCompraText.textContent = totalCompra.toFixed(2);
                receivedAmountInput.addEventListener('input', function(event) {
                    const input = event.target;
                    const valor = input.value.trim();

                    if (valor === "") return;

                    const valorNumerico = parseFloat(valor);

                    if (isNaN(valorNumerico) || valorNumerico <= 0) {
                        input.value = 1;
                        return;
                    }

                    if (valorNumerico < totalCompra) {
                        mostrarErrorConfirm(
                            "El monto recibido es menor al total de la compra.");
                    } else {
                        document.getElementById('errorConfirm').innerHTML = "";
                    }

                    const recibido = parseFloat(receivedAmountInput.value) || 0;
                    const cambio = calcularCambio(totalCompra, recibido);

                    changeAmountText.textContent = cambio > 0 ? cambio.toFixed(2) : "0.00";
                    document.getElementById('montorecibido').value = parseFloat(
                        receivedAmountInput.value) || 0;
                    document.getElementById('cambioadar').value = cambio > 0 ? cambio.toFixed(
                        2) : "0.00";
                });

                function mostrarErrorConfirm(mensaje) {
                    document.getElementById('errorConfirm').innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>${mensaje}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                }

                function calcularCambio(total, recibido) {
                    return recibido - total;
                }

                function showConfirmModal() {
                    $('#confirmModal').modal('show');
                    receivedAmountInput.value = '';
                    changeAmountText.textContent = "0.00";
                    if (document.getElementById('tipoPago').value === "TARJETA" || document
                        .getElementById('tipoPago').value === "TRANSFERENCIA") {
                        receivedAmountInput.value = document.getElementById('total').value;
                        receivedAmountInput.readOnly = true;
                    } else {
                        receivedAmountInput.value = "";
                        receivedAmountInput.readOnly = false;
                    }
                }
                showConfirmModal();
            });

            async function validarDatos() {
                let validator = 0;

                const txtCliente = document.getElementById('rtn');
                if (!txtCliente.value.trim()) {
                    displayError("¡DEBE SELECCIONAR UN CLIENTE!");
                    validator++;
                }

                const txtRtn = document.getElementById('rtn');
                if (!txtRtn.value.trim()) {
                    displayError("¡INGRESAR RTN DEL CLIENTE!");
                    txtRtn.focus();
                    validator++;
                }

                const codigocliente = document.getElementById('codigolabel');
                if (!codigocliente.textContent.trim()) {
                    displayError("¡INGRESAR CODIGO DEL CLIENTE!");
                    validator++;
                }

                const cmbPago = document.getElementById('tipoPago');
                if (!cmbPago.value.trim()) {
                    displayError("¡SELECCIONAR FORMA DE PAGO!");
                    cmbPago.focus();
                    validator++;
                }

                const lblnum = document.getElementById('numero');
                if (!lblnum.value.trim()) {
                    displayError("NO SE HA CARGADO EL NUMERO DE FACTURA!");
                    validator++;
                }

                const tablaProductos = document.getElementById('tabla_productos');
                const filas = tablaProductos.querySelectorAll('tbody tr');
                if (filas.length === 0) {
                    displayError("¡NO SE HAN AGREGADO PRODUCTOS A LA FACTURA!");
                    validator++;
                }

                if (validator > 0) {
                    return false;
                }

                return true;
            }


            async function verificarExistenciasProductos() {
                const tablaProductos = document.getElementById('tabla_productos');
                const filas = tablaProductos.querySelectorAll('tbody tr');
                let validator = 0;

                for (const fila of filas) {
                    try {
                        const codigoProducto = fila.querySelector('td:nth-child(1)')?.textContent.trim();
                        const cantidad = parseInt(fila.querySelector('td:nth-child(3)')?.textContent.trim(),
                            10);

                        if (!codigoProducto || isNaN(cantidad)) {
                            displayError(
                                `Error en los datos de la fila: Código de producto o cantidad inválidos.`);
                            validator++;
                            continue;
                        }

                        const esValido = await checkExistencias(codigoProducto, cantidad);
                        if (!esValido) {
                            displayError(
                                `No hay suficiente stock para el producto con código: ${codigoProducto}`);
                            validator++;
                        }
                    } catch (error) {
                        console.error('Error durante la verificación de existencias:', error.message);
                        displayError(`Error al verificar existencias del producto.`);
                        validator++;
                    }
                }

                if (validator > 0) {
                    throw new Error("Errores encontrados en la verificación de existencias.");
                }
            }

            async function checkExistencias(codigoProducto, cantidad) {
                try {
                    const existencias = await ExistenciasProducto(codigoProducto);
                    if (isNaN(existencias)) {
                        throw new Error(
                            `Las existencias del producto ${codigoProducto} no son un número válido.`);
                    }

                    return existencias >= cantidad;
                } catch (error) {
                    console.error(`Error en checkExistencias para el producto ${codigoProducto}:`, error
                        .message);
                    return false;
                }
            }

            async function ExistenciasProducto(code) {
                try {
                    const responseCant = await fetch(`/verificar-existencias-productos/${code}`);

                    if (!responseCant.ok) {
                        throw new Error(
                            `Error al obtener existencias para el producto ${code}: ${responseCant.statusText}`
                        );
                    }

                    const data = await responseCant.json();

                    const existencias = parseInt(data, 10);
                    if (isNaN(existencias)) {
                        throw new Error(`El valor de existencias para el producto ${code} no es válido.`);
                    }

                    return existencias;
                } catch (error) {
                    console.error('Error en ExistenciasProducto:', error.message);
                    throw error;
                }
            }

            document.getElementById('confirmSave').addEventListener('click', function() {
                totalizarFactura3();
                form.submit();
                confirmModal.hide();
                @if ($existe_flag)
                    var enlace = document.createElement('a');
                    enlace.href =
                        '{{ route('reservaciones.indexin') }}';
                    enlace.target = '_blank';
                    document.body.appendChild(enlace);
                    enlace.click();
                    document.body.removeChild(enlace);
                @endif
            });
        });

        BtnBuscar.addEventListener('click', function() {
            var enlace = document.createElement('a');
            enlace.href = '{{ route('verFacturas') }}';
            enlace.target = '_blank';
            document.body.appendChild(enlace);
            enlace.click();
            document.body.removeChild(enlace);
        });


        // ----- SCRIPT SEPARADOR -----


        $(document).ready(function() {
            var filaSeleccionada;
            const formEditar = document.getElementById('formEditar');
            formEditar.reset();
            $('.editar-servicio').on('click', function() {
                $('#modalDescuentoPorcentaje').val('');
                $('#modalTotal').val('');


                filaSeleccionada = $(this).closest('tr');
                var indx = $(this).data('index');
                var nombre = filaSeleccionada.find('td:nth-child(1)').text().trim();
                var dias = filaSeleccionada.find('td:nth-child(2)').text().trim();
                var cantidad = filaSeleccionada.find('td:nth-child(3)').text();
                var precio = filaSeleccionada.find('td:nth-child(4)').text();
                var dscto = filaSeleccionada.find('td:nth-child(5)').text();
                var total = filaSeleccionada.find('td:nth-child(6)').text();
                if (indx == "1") {
                    document.getElementById('divCantidad').classList.add('d-none');
                    document.getElementById('divDias').classList.remove('d-none');
                    total = (parseFloat(dias) * parseFloat(precio)) - parseFloat(dscto);
                } else if (indx != "1") {
                    document.getElementById('divDias').classList.add('d-none');
                    document.getElementById('divCantidad').classList.remove('d-none');
                    total = (parseFloat(cantidad) * parseFloat(precio)) - parseFloat(dscto);
                }
                formEditar.setAttribute('data-index', indx);
                $('#modalProd').val(nombre);
                document.getElementById('modalProd').readOnly = true;
                $('#modalCantidad').val(cantidad);
                document.getElementById('modalCantidad').readOnly = true;
                $('#modalPrecio').val(precio);
                document.getElementById('modalPrecio').readOnly = true;
                $('#modalDias').val(dias);
                document.getElementById('modalDias').readOnly = true;
                $('#modalDescuento').val(dscto);
                $('#modalTotal').val(total);
                document.getElementById('modalTotal').readOnly = true;
                $('#editarModal').modal('show');
            });

            function validarNumero(event) {
                const valor = event.target.value;
                if (isNaN(valor) || valor < 0) {
                    event.target.value = '';
                }
            }
        });
        document.addEventListener("DOMContentLoaded", function() {
            const modalCantidad = document.getElementById("modalCantidad");
            const modalPrecio = document.getElementById("modalPrecio");
            const modalDescuento = document.getElementById("modalDescuento");
            const modalTotal = document.getElementById("modalTotal");
            const errorDivModal = document.getElementById("errorModal");

            let filaSeleccionada;
            let indx;

            document.querySelectorAll(".editar-servicio").forEach((boton) => {
                boton.addEventListener("click", function() {
                    filaSeleccionada = this.closest("tr");
                    indx = filaSeleccionada.dataset.index;

                    const dias = filaSeleccionada.querySelector("td:nth-child(1)").textContent
                        .trim();
                    const cantidad = filaSeleccionada.querySelector("td:nth-child(2)").textContent
                        .trim();
                    const precio = filaSeleccionada.querySelector("td:nth-child(3)").textContent
                        .trim();
                    const descuento = filaSeleccionada.querySelector("td:nth-child(4)").textContent
                        .trim();

                    modalCantidad.value = cantidad || "0";
                    modalPrecio.value = precio || "0";
                    modalDescuento.value = descuento || "0";
                    modalTotal.value = filaSeleccionada.querySelector("td:nth-child(5)").textContent
                        .trim() || "0";

                    if (indx == "1") {
                        document.getElementById("divDias").classList.remove("d-none");
                        document.getElementById("divCantidad").classList.add("d-none");
                    } else {
                        document.getElementById("divDias").classList.add("d-none");
                        document.getElementById("divCantidad").classList.remove("d-none");
                    }

                    $("#editarModal").modal("show");

                });
            });

            modalCantidad.addEventListener("input", actualizarDescuento);
            modalPrecio.addEventListener("input", actualizarDescuento);
            modalDescuento.addEventListener("input", actualizarDescuento);

            document.getElementById("guardarCambios").addEventListener("click", function() {
                if (!filaSeleccionada) {
                    mostrarError("No se seleccionó ninguna fila.");
                    return;
                }

                const cantidad = parseFloat(modalCantidad.value) || 0;
                const dias = parseFloat(document.getElementById("modalDias").value) || 1;
                const precio = parseFloat(modalPrecio.value) || 0;
                const descuento = parseFloat(modalDescuento.value) || 0;
                const total = parseFloat(modalTotal.value) || 0;

                if (indx == "1") {
                    // Para la fila de habitación: actualizar solo precio, descuento y total
                    filaSeleccionada.querySelector("td:nth-child(4)").textContent = precio.toFixed(2);
                    filaSeleccionada.querySelector("td:nth-child(5)").textContent = descuento.toFixed(2);
                    filaSeleccionada.querySelector("td:nth-child(6)").textContent = total.toFixed(2);

                    // También puedes actualizar los inputs ocultos si es necesario
                    const descuentoInput = filaSeleccionada.querySelector(`input[name="descuento[1]"]`);
                    const totalInput = filaSeleccionada.querySelector(`input[name="total[1]"]`);

                    if (descuentoInput) descuentoInput.value = descuento.toFixed(2);
                    if (totalInput) totalInput.value = total.toFixed(2);

                } else {
                    // Para las demás filas (servicios normales)
                    filaSeleccionada.querySelector("td:nth-child(3)").textContent = cantidad.toFixed(2);
                    filaSeleccionada.querySelector("td:nth-child(4)").textContent = precio.toFixed(2);
                    filaSeleccionada.querySelector("td:nth-child(5)").textContent = descuento.toFixed(2);
                    filaSeleccionada.querySelector("td:nth-child(6)").textContent = total.toFixed(2);

                    const cantidadInput = filaSeleccionada.querySelector(`input[name="cantidad[${indx}]"]`);
                    const descuentoInput = filaSeleccionada.querySelector(
                        `input[name="descuento[${indx}]"]`);
                    const totalInput = filaSeleccionada.querySelector(`input[name="total[${indx}]"]`);

                    if (cantidadInput) cantidadInput.value = cantidad.toFixed(2);
                    if (descuentoInput) descuentoInput.value = descuento.toFixed(2);
                    if (totalInput) totalInput.value = total.toFixed(2);
                }

                $("#editarModal").modal("hide");
                totalizarFactura3();
            });

            function actualizarDescuento() {
                const cantidad = parseFloat(modalCantidad.value) || 1;
                const dias = parseFloat(document.getElementById("modalDias").value) || 1;
                const precio = parseFloat(modalPrecio.value) || 0;
                const subtotal = indx == "1" ? dias * precio : cantidad * precio;
                let descuento = parseFloat(modalDescuento.value) || 0;

                if (descuento > subtotal) {
                    descuento = subtotal;
                    modalDescuento.value = subtotal.toFixed(2);
                }

                if (descuento < 0) {
                    descuento = 0;
                    modalDescuento.value = "0.00";
                }

                const total = subtotal - descuento;
                modalTotal.value = total.toFixed(2);
            }

            function mostrarError(mensaje) {
                errorDivModal.innerHTML = `<div class="alert alert-danger">${mensaje}</div>`;
            }

            function limpiarError() {
                errorDivModal.innerHTML = "";
            }
            totalizarFactura3();
        });

        const botnAnular = document.getElementById('confirmAnularBtn') || 0;
        const factnumInput = document.getElementById('factnumInput');

        if (botnAnular != 0) {
            botnAnular.addEventListener('click', async function() {
                const factnum = botnAnular.getAttribute('data-factnum');
                const url = `/anular/factura/${factnum}/reservacionin`;
                const form = document.getElementById('anularFacturaForm');
                factnumInput.value = factnum;
                form.action = url;

                $('#confirmAnularModal').modal('show');
            });
        }

        $('#confirmAnularModal').on('hidden.bs.modal', function() {
            factnumInput.value = '';
        });

        function totalizarFactura3() {
            @if (!$existe_flag)
                const tabla = document.getElementById('tabla_productos').querySelector('tbody');
                const filas = tabla.querySelectorAll('tr');

                let importeExento = 0;
                let importeExonerado = 0;
                let importeGrav15 = 0;
                let importeGrav18 = 0;
                let descuentoTotal = 0;

                filas.forEach((fila, index) => {
                    const celdas = fila.querySelectorAll('td');
                    if (celdas.length < 6) return; // 👈 Omitir filas inválidas

                    let cantidad = parseFloat(celdas[2].textContent) || 0; // td 3
                    const precioUnitario = parseFloat(celdas[3].textContent) || 0; // td 4
                    const descuento = parseFloat(celdas[4].textContent) || 0; // td 5

                    if (index === 0) {
                        cantidad = parseFloat(celdas[1].textContent || 0);
                    }

                    const total = (cantidad * precioUnitario) - descuento;

                    // Actualizar el total en td 6
                    celdas[5].textContent = total.toFixed(2);

                    // Buscar el input hidden con data-isv
                    const isvInput = fila.querySelector('input[data-isv]');
                    const isv = isvInput ? parseFloat(isvInput.dataset.isv) || 0 : 0;

                    if (isv === 0) {
                        importeExento += total;
                    } else if (isv === 15) {
                        importeGrav15 += total;
                    } else if (isv === 18) {
                        importeGrav18 += total;
                    } else if (isv === -1) {
                        importeExonerado += total;
                    }

                    descuentoTotal += descuento;
                });

                const isv15 = importeGrav15 - (importeGrav15 / 1.15);
                const isv18 = importeGrav18 - (importeGrav18 / 1.18);
                const totalFactura = importeExento + importeExonerado + importeGrav15 + importeGrav18;

                document.getElementById('LblImpExen').textContent = importeExento.toFixed(2);
                document.getElementById('importeExento').value = importeExento.toFixed(2);
                document.getElementById('LblImpExon').textContent = importeExonerado.toFixed(2);
                document.getElementById('importeExonerado').value = importeExonerado.toFixed(2);
                document.getElementById('LblImpGrav15').textContent = (importeGrav15 / 1.15).toFixed(2);
                document.getElementById('importeGrav15').value = (importeGrav15 / 1.15).toFixed(2);
                document.getElementById('LblImpGrav18').textContent = importeGrav18.toFixed(2);
                document.getElementById('importeGrav18').value = importeGrav18.toFixed(2);
                document.getElementById('LblIsv15').textContent = isv15.toFixed(2);
                document.getElementById('isv15').value = isv15.toFixed(2);
                document.getElementById('LblIsv18').textContent = isv18.toFixed(2);
                document.getElementById('isv18').value = isv18.toFixed(2);
                document.getElementById('LblDescto').textContent = descuentoTotal.toFixed(2);
                document.getElementById('descuento').value = descuentoTotal.toFixed(2);
                document.getElementById('LblTotal').textContent = totalFactura.toFixed(2);
                document.getElementById('total').value = totalFactura.toFixed(2);
            @endif
        }
        // ----- SCRIPT SEPARADOR -----
        document.addEventListener('DOMContentLoaded', function() {
            @if ($existe_flag)
                document.getElementById('BtnImprimir').addEventListener('click', function() {
                    $('#modalReimprimirFactura').modal('show');
                });
                document.getElementById('etiquetaImprimir').readOnly = false;
            @endif

            document.getElementById('guardarImprimir').addEventListener('click', function() {
                var etiqueta = document.getElementById('etiquetaImprimir')
                    .value;
                if (etiqueta) {
                    var enlace = document.createElement('a');
                    enlace.href =
                        '{{ route('facturas.rptfacturareservacionIn', $existe_flag ? $factura->id : '') }}' +
                        '?etiqueta=' + encodeURIComponent(etiqueta);
                    document.body.appendChild(enlace);
                    enlace.click();
                    document.body.removeChild(enlace);
                } else {
                    alert('Por favor, ingrese una etiqueta antes de continuar.');
                }
            });
        });
    </script>
@stop
<div class="modal fade" id="confirmAnularModal" tabindex="-1" role="dialog"
    aria-labelledby="confirmAnularModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmAnularModalLabel">Confirmación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Desea anular esta factura?
            </div>
            <div class="modal-footer">
                @if (isset($detallesfactura[0]))
                    <form id="anularFacturaForm" method="GET">
                        <button type="submit" data-factnum="{{ $detallesfactura[0]->cabfacturainterno_id }}"
                            class="btn btn-danger" id="confirmAnularBtn">Anular</button>
                        <input type="hidden" name="factnum" value="{{ $detallesfactura[0]->factnum }}"
                            id="factnumInput">
                    </form>
                @else
                    <p class="text-danger">No se encontró información de la factura.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalReimprimirFactura" tabindex="-1" role="dialog"
    aria-labelledby="modalReimprimirFacturaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalReimprimirFacturaLabel">
                    <i class="fas fa-print"></i> REIMPRIMIR FACTURA
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <label for="etiquetaImprimir" style="font-size: h2;">
                    <strong>ETIQUETA:</strong>
                </label>
                <input type="text" name="etiquetaImprimir" id="etiquetaImprimir" class="form-control mt-3"
                    placeholder="Ingrese etiqueta">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" id="guardarImprimir" class="btn btn-primary">
                    <i class="fas fa-check"></i> Aceptar
                </button>
            </div>
        </div>
    </div>
</div>
{{--
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if ($existe_flag)
            document.getElementById('BtnImprimir').addEventListener('click', function() {
                $('#modalReimprimirFactura').modal('show');
            });
            document.getElementById('etiquetaImprimir').readOnly = false;
        @endif

        document.getElementById('guardarImprimir').addEventListener('click', function() {
            var etiqueta = document.getElementById('etiquetaImprimir')
                .value;
            if (etiqueta) {
                var enlace = document.createElement('a');
                enlace.href =
                    '{{ route('facturas.rptfacturareservacionIn', $existe_flag ? $factura->id : '') }}' +
                    '?etiqueta=' + encodeURIComponent(etiqueta);
                document.body.appendChild(enlace);
                enlace.click();
                document.body.removeChild(enlace);
            } else {
                alert('Por favor, ingrese una etiqueta antes de continuar.');
            }
        });
    });
</script> --}}
<!-- Modal de edición -->
<div class="modal fade" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarModalLabel">Editar Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditar">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="errorModal" class="mt-2"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="modalProd">Producto</label>
                            <input type="text" readonly class="form-control" id="modalProd" name="producto"
                                required>
                        </div>
                        <div class="form-group col-md-12" id="divDias">
                            <label for="modalDias">Dias</label>
                            <input type="text" class="form-control" id="modalDias" name="dias" required>
                        </div>
                        <div class="form-group col-md-12" id="divCantidad">
                            <label for="modalCantidad">Cantidad</label>
                            <input type="number" class="form-control" id="modalCantidad" name="cantidad" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="modalPrecio">Precio Unitario</label>
                            <input type="number" class="form-control" id="modalPrecio" name="precio" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="modalDescuento">Descuento</label>
                            <input type="number" class="form-control" id="modalDescuento" name="descuento"
                                required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="modalTotal">Total</label>
                            <input type="text" class="form-control" id="modalTotal" name="total" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="guardarCambios">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
