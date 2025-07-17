@extends('adminlte::page')

@section('title', ':::Facturacion:::')

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
                <button id="BtnNuevo" type="button" class="rounded btn btn-primary"><i class="fas fa-plus"></i>
                    Nuevo</button>
                <button id="BtnGuardar" type="button" class="ml-2 rounded btn btn-primary"><i class="fas fa-save"></i>
                    Guardar</button>
                <button id="BtnBuscar" type="button" class="rounded btn btn-info ml-2"><i class="fas fa-search"></i>
                    Buscar</button>
                <button id="BtnActualizar" type="button" class="rounded btn btn-success ml-2"><i class="fas fa-sync"></i>
                    Actualizar</button>
                <button id="BtnEliminar" type="button" class="rounded btn btn-danger ml-2"><i class="fas fa-trash"></i>
                    Eliminar</button>
                <button id="BtnImprimir" type="button" class="rounded btn btn-secondary ml-2"><i class="fas fa-print"></i>
                    Imprimir</button>
                <button id="BtnAnular" type="button" class="rounded text-light btn btn-warning ml-2"><i
                        class="fas fa-ban"></i>
                    Anular</button>
                <button id="BtnCancelar" type="button" style="background-color: #a107fa"
                    class="rounded text-light btn ml-2"><i class="fas fa-times"></i> Cancelar</button>
                <a id="BtnSalir" href="{{ route('dashboard') }}" class="rounded btn btn-dark ml-2 disabled-link">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>

        </div>
        <div class="card-body">
            <form action="{{ route('facturacionesin.store') }}" id="formId" method="POST">
                @csrf
                <div class="form-row">
                    <div class="col-md-12">
                        <div id="error" class="mt-2"></div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="cliente">Cliente</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="cliente" placeholder="Cliente">
                            <div class="input-group-append">
                                <button id="BtnBuscarCliente" class="btn btn-outline-secondary" type="button"><i
                                        class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-2">
                        <label>&nbsp;</label>
                        <div>
                            <a href="{{ route('huespedes.create') }}" target="_blank" id="btnAgregarCliente"
                                class="btn btn-success disabled-link"><i class="fas fa-user-plus"></i></a>
                            <button id="btnCancelarCliente" type="button" class="btn btn-danger"><i
                                    class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="tipoPago">Tipo de Pago</label>
                        <select id="tipoPago" name="tipopago" class="form-control">
                            <option value="">ELEGIR...</option>
                            <option value="EFECTIVO">EFECTIVO</option>
                            <option value="TARJETA">TARJETA</option>
                            <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Número</label>
                        <input type="hidden" class="form-control" id="numero" name="numero">
                        <h3 class="text-center text-light"><label id="numerolabel" style="background-color: #20c997"
                                class="w-100 p-1 rounded">------</label></h3>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>RTN</label>
                        <input type="hidden" class="form-control" id="rtn" name="rtn">
                        <h3 class="text-center text-light">
                            <label id="rtnlabel" style="background-color: #20c997"
                                class="w-100 p-1 rounded">-------------</label>
                        </h3>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Código</label>
                        <input type="hidden" class="form-control" id="codigo" name="codigocliente">
                        <h3 class="text-center text-light">
                            <label id="codigolabel" style="background-color: #20c997"
                                class="w-100 p-1 rounded">---------</label>
                        </h3>
                    </div>

                    <div class="form-group col-md-2">
                        <label>Fecha</label>
                        <input type="hidden" class="form-control" value="{{ \Carbon\Carbon::today()->format('d/m/Y') }}"
                            id="fecha" name="fecha">
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

                <!-- Agregar productos -->
                <div class="card mb-4">
                    <div style=" background-color: #20c997" class="card-header text-white">AGREGAR PRODUCTO</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                    </div>
                                    <input autocomplete="off" type="text" id="mysearch" class="form-control"
                                        placeholder="Buscar un producto">
                                </div>
                                <ul id="showlist" tabindex='1' class="list-group"></ul>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label for="codigo">Código:</label>
                                <input type="text" id="codigoprod" readonly name="codigo" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="producto">Producto:</label>
                                <input type="text" id="producto" readonly name="producto" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="presentacion">Presentación:</label>
                                <input type="text" id="presentacion" readonly name="presentacion"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-2">
                                <label for="cantidad">Cant:</label>
                                <input type="number" id="cantidad" name="cantidad"
                                    class="form-control @error('cantidad') is-invalid @enderror" min="1">
                                @error('cantidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-1">
                                <label for="isv">ISV:</label>
                                <input type="text" readonly id="isv" name="isv" class="form-control"
                                    min="1">
                            </div>
                            <div class="col-md-4">
                                <label for="precio_unitario">Precio Unit:</label>
                                <input type="number" id="precio_unitario" name="precio_unitario"
                                    class="form-control @error('precio_unitario') is-invalid @enderror" step="0.01">
                                @error('precio_unitario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="totalP">Total:</label>
                                <input readonly type="number" id="totalP" name="totalP" class="form-control"
                                    step="0.01">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-success w-100" id="agregar_producto">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-danger w-100" id="cancelar_agregar">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de productos -->
                <div class="card mb-4">
                    <div style=" background-color: #20c997" class="card-header text-white">PRODUCTOS AGREGADOS</div>
                    <div class="card-body">
                        <table class="table table-bordered" id="tabla_productos">
                            <thead>
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
                                @if (session()->has('productos'))
                                    @foreach (session('productos') as $producto)
                                        <tr>
                                            <td>{{ $producto['codigo'] }}</td>
                                            <td>{{ $producto['producto'] }}</td>
                                            <td>{{ $producto['cantidad'] }}</td>
                                            <td>{{ $producto['precio_unitario'] }}</td>
                                            <td>{{ $producto['total'] }}</td>
                                            <td>
                                                <button type="button" class="btn btn-danger">Eliminar</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
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
                        <input type="hidden" class="form-control" id="descuento" name="descuento">
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
    <script src="{{ asset('search/facturacion/js/search.js') }}" type="module"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            let clienteTable = $('#tablaClientes').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('datatable.getHuespedes') }}",
                    data: function(d) {
                        d.searchType = $("input[name='searchType']:checked").val();
                        d.searchQuery = $('#searchInput').val();
                    }
                },
                columns: [{
                        data: 'codigo_cliente'
                    },
                    {
                        data: 'nombre_completo'
                    },
                    {
                        data: 'rtn'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-info btn-sm" onclick="selectClient('${row.codigo_cliente}', '${row.nombre_completo}', '${row.rtn}', '${data.codigo_cliente}')">
                                    Seleccionar
                                </button>
                            `;
                        }
                    }
                ]
            });

            window.selectClient = function(id, nombre, rtn, codigo) {
                $('#buscarClienteModal').modal('hide');
                $('#rtn').val(rtn);
                $('#rtnlabel').text(rtn);
                $('#codigo').val(codigo);
                $('#codigolabel').text(codigo);
                $('#cliente').val(nombre);
            };

            $('#BtnBuscarCliente').on('click', function() {
                $('#buscarClienteModal').modal('show');
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnCancelarAgregar = document.getElementById('cancelar_agregar');

            btnCancelarAgregar.addEventListener('click', function () {
                document.getElementById('codigoprod').value = '';
                document.getElementById('producto').value = '';
                document.getElementById('presentacion').value = '';
                document.getElementById('cantidad').value = '';
                document.getElementById('isv').value = '';
                document.getElementById('precio_unitario').value = '';
                document.getElementById('totalP').value = '';
            });
        });
    </script>
    <script>
        const btnNuevo = document.getElementById("BtnNuevo");
        const btnGuardar = document.getElementById("BtnGuardar");
        const btnBuscar = document.getElementById("BtnBuscar");
        const btnActualizar = document.getElementById("BtnActualizar");
        const btnEliminar = document.getElementById("BtnEliminar");
        const btnImprimir = document.getElementById("BtnImprimir");
        const btnAnular = document.getElementById("BtnAnular");
        const btnCancelar = document.getElementById("BtnCancelar");
        const btnSalir = document.getElementById("BtnSalir");
        const scltTipoPago = document.getElementById('tipoPago');
        const btnBuscarCliente = document.getElementById('BtnBuscarCliente');
        const btnAgregarCliente = document.getElementById('btnAgregarCliente');
        const btnCancelarCliente = document.getElementById('btnCancelarCliente');
        const btnAgregarProducto = document.getElementById('agregar_producto');
        const txtcodigo = document.getElementById('codigoprod');
        const txtproducto = document.getElementById('producto');
        const txtpresentacion = document.getElementById('presentacion');
        const txtisv = document.getElementById('isv');
        const txttotal = document.getElementById('totalP');

        const errorContainer = document.getElementById('error');

        function setLoadForm() {
            document.getElementById("BtnSalir").setAttribute("href", "{{ route('dashboard') }}");
            document.getElementById("BtnSalir").classList.remove("disabled-link");
            document.getElementById("btnAgregarCliente").classList.add("disabled-link");
            document.getElementById("btnAgregarCliente").removeAttribute("href");
            btnNuevo.disabled = false;
            btnGuardar.disabled = true;
            btnBuscar.disabled = false;
            btnActualizar.disabled = true;
            btnEliminar.disabled = true;
            btnImprimir.disabled = true;
            btnAnular.disabled = true;
            btnCancelar.disabled = true;
            btnSalir.disabled = false;
            btnBuscarCliente.disabled = true;
            btnAgregarCliente.disabled = true;
            btnCancelarCliente.disabled = true;
            btnAgregarProducto.disabled = true;

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
            document.getElementById('LblImpExen').textContent = '0.00';
            document.getElementById('importeExento').value = '0.00';
            document.getElementById('LblImpExon').textContent = '0.00';
            document.getElementById('importeExonerado').value = '0.00';
            document.getElementById('LblImpGrav15').textContent = '0.00';
            document.getElementById('importeGrav15').value = '0.00';
            document.getElementById('LblImpGrav18').textContent = '0.00';
            document.getElementById('importeGrav18').value = '0.00';
            document.getElementById('LblIsv15').textContent = '0.00';
            document.getElementById('isv15').value = '0.00';
            document.getElementById('LblIsv18').textContent = '0.00';
            document.getElementById('isv18').value = '0.00';
            document.getElementById('LblDescto').textContent = '0.00';
            document.getElementById('descuento').value = '0.00';
            document.getElementById('LblTotal').textContent = '0.00';
            document.getElementById('total').value = '0.00';

        }
        setLoadForm();

        async function verificarAperturaCaja() {
            try {
                errorContainer.innerHTML = '';

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
                    errorContainer.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>No se pudo obtener el correlativo.</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `;
                }

            } catch (error) {
                errorContainer.innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Ocurrió un error inesperado: ${error.message}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
                console.error('Error:', error);
            }
        }

        btnNuevo.addEventListener('click', function() {
            errorContainer.innerHTML = '';
            verificarAperturaCaja();
            document.getElementById("btnAgregarCliente").setAttribute("href", "{{ route('huespedes.create') }}");
            document.getElementById("btnAgregarCliente").classList.remove("disabled-link");
            document.getElementById("BtnSalir").removeAttribute("href");
            btnNuevo.disabled = true;
            btnGuardar.disabled = false;
            btnBuscar.disabled = true;
            btnActualizar.disabled = true;
            btnEliminar.disabled = true;
            btnImprimir.disabled = true;
            btnAnular.disabled = true;
            btnCancelar.disabled = false;
            btnSalir.disabled = true;
            btnBuscarCliente.disabled = false;
            btnAgregarCliente.disabled = false;
            btnCancelarCliente.disabled = false;
            btnAgregarProducto.disabled = false;
            const inputsReadonly = Array.from(document.querySelectorAll('input:not([type="hidden"])[readonly]'));
            inputsReadonly.forEach(input => {
                input.readOnly = false;
            });
            scltTipoPago.disabled = false;
            txtcodigo.readOnly = true;
            txtproducto.readOnly = true;
            txtpresentacion.readOnly = true;
            txtisv.readOnly = true;
            txttotal.readOnly = true;
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
                        console.warn(
                            'Ya existe un mensaje de error mostrado. No se añadieron nuevos mensajes.');
                    }



                } catch (error) {
                    console.error(error.message);
                }
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
        });

        btnCancelar.addEventListener('click', function() {
            setLoadForm();
        });

        btnCancelarCliente.addEventListener('click', function() {
            $('#rtn').val("");
            $('#rtnlabel').text("-------------");
            $('#dnilabel').text("-------------");
            $('#codigo').val("");
            $('#codigolabel').text("---------");
            $('#cliente').val("");
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

                const totalCompra = parseFloat(document.getElementById('total').value) || 0;
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

                await verificarExistenciasProductos();
                return true;
            }


            async function verificarExistenciasProductos() {
                const tablaProductos = document.getElementById('tabla_productos');
                const filas = tablaProductos.querySelectorAll('tbody tr');
                let errores = 0;

                for (const fila of filas) {
                    try {
                        const codigoProducto = fila.querySelector('td:nth-child(1)')?.textContent.trim();
                        const cantidad = parseInt(fila.querySelector('td:nth-child(3)')?.textContent.trim(),
                        10);

                        if (!codigoProducto || isNaN(cantidad)) {
                            displayError(`Datos inválidos: Código o cantidad no válidos en la fila.`);
                            errores++;
                            continue;
                        }

                        const response = await fetch(`/verificar-tipo-producto/${codigoProducto}`);
                        if (!response.ok) {
                            throw new Error(`Error en la solicitud: ${response.status}`);
                        }

                        const tipoProducto = await response.text();

                        if (tipoProducto !== "SERVICIO") {
                            const esValido = await checkExistencias(codigoProducto, cantidad);
                            if (!esValido) {
                                displayError(
                                    `Stock insuficiente para el producto con código: ${codigoProducto}`);
                                errores++;
                            }
                        }
                    } catch (error) {
                        console.error('Error durante la verificación:', error.message);
                        displayError(
                            `Error al verificar el producto con código: ${fila.querySelector('td:nth-child(1)')?.textContent.trim()}`
                            );
                        errores++;
                    }
                }

                if (errores > 0) {
                    throw new Error("Errores encontrados durante la verificación de existencias.");
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
                form.submit();
                confirmModal.hide();
                var enlace = document.createElement('a');
                enlace.href =
                    '{{ route('facturaciones.index') }}';
                enlace.target = '_blank';
                document.body.appendChild(enlace);
                enlace.click();
                document.body.removeChild(enlace);
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
    </script>
@stop

<!-- Modal para buscar cliente -->
<div class="modal fade" id="buscarClienteModal" tabindex="-1" role="dialog"
    aria-labelledby="buscarClienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buscarClienteModalLabel">Buscar Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Tabla de resultados -->
                <table id="tablaClientes" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>RTN</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="BtnSeleccionarCliente">Seleccionar
                    Cliente</button>
            </div>
        </div>
    </div>
</div>


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
                            <input type="text" readonly class="form-control" id="modalProd" name=""
                                required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="modalCantidad">Cantidad</label>
                            <input type="number" class="form-control" id="modalCantidad" name="" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="modalPrecio">Precio Unitario</label>
                            <input type="number" class="form-control" id="modalPrecio" name="" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="modalDescuento">Descuento</label>
                            <input type="number" class="form-control" id="modalDescuento" name="" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="modalDescuento">%</label>
                            <input type="number" class="form-control" id="modalDescuentoPorcentaje" name=""
                                required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="modalTotal">Total</label>
                            <input type="text" class="form-control" id="modalTotal" name="" readonly>
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
<!-- Modal de confirmación -->
