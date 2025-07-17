@extends('adminlte::page')

@section('title', 'Compra de Insumos')

@section('content_header')
    <a href="{{ route('insumo.index') }}" class="ml-2 btn text-light btn-secondary">
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

        <form method="POST" id="formId" action="{{ route('comprasinsumos.store') }}">
            @csrf
            <!-- Datos generales -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">DATOS GENERALES</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="fecha">Fecha:</label>
                            <input type="date" readonly value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                                id="fecha" name="fecha" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label for="tipopago">Pago:</label>
                            <select name="tipo_pago" id="tipopago"
                                class="form-control @error('tipo_pago') is-invalid @enderror">
                                <option value="">SELECCIONE TIPO DE PAGO</option>
                                <option {{ old('tipo_pago') == 'CONTADO' ? 'selected' : '' }} value="CONTADO">CONTADO
                                </option>
                                <option {{ old('tipo_pago') == 'CHEQUE' ? 'selected' : '' }} value="CHEQUE">CHEQUE</option>
                                <option {{ old('tipo_pago') == 'CREDITO' ? 'selected' : '' }} value="CREDITO">CRÉDITO
                                </option>
                            </select>
                            @error('tipo_pago')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="factura">Factura:</label>
                            <input type="text" id="factura" value="{{ old('factura') }}" name="factura"
                                class="form-control @error('factura') is-invalid @enderror">
                            @error('factura')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="numero">Numero:</label>
                            <input type="text" readonly id="numero" name="numero" class="form-control">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-9">
                            <label for="proveedor">Proveedor:</label>
                            <select id="proveedor" name="proveedor"
                                class="form-control @error('proveedor') is-invalid @enderror">
                                <option value="">Seleccione un Proveedor</option>
                                @foreach ($proveedores as $proveedor)
                                    <option {{ old('proveedor') == $proveedor->id ? 'selected' : '' }}
                                        value="{{ old('proveedor', $proveedor->id) }}">{{ $proveedor->nombre }}</option>
                                @endforeach
                            </select>
                            @error('proveedor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mt-4 d-flex align-items-center">
                            <div class="form-check mt-2">
                                <input checked class="form-check-input" type="checkbox" id="precios_impuestos"
                                    name="precios_impuestos" style="transform: scale(1.5);">
                                <label class="form-check-label" for="precios_impuestos">
                                    Precios con Impuesto Incluido
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Agregar productos -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">AGREGAR INSUMO</div>
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
                                    placeholder="Buscar un insumo">
                            </div>
                            <ul id="showlist" tabindex='1' class="list-group"></ul>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label for="codigo">Código:</label>
                            <input type="text" id="codigo" readonly name="codigo" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="producto">Insumo:</label>
                            <input type="text" id="producto" readonly name="producto" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="presentacion">Presentación:</label>
                            <input type="text" id="presentacion" readonly name="presentacion" class="form-control">
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
                            <input type="number" id="precio_unitario" name=""
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
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de productos -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">INSUMOS ORDENADOS</div>
                <div class="card-body">
                    <table class="table table-bordered" id="tabla_productos">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Totales -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 offset-md-8">
                            <div class="d-flex justify-content-between">
                                <span>Subtotal:</span>
                                <span name="subtotal" id="subtotal">0.00</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Impuesto:</span>
                                <span name="impuesto" id="impuesto">0.00</span>
                            </div>
                            <div class="d-flex justify-content-between font-weight-bold">
                                <span>Total:</span>
                                <span name="total" id="total">0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botón de envío -->
            <div class="text-center">
                <button id="btnguardar" type="button" class="my-2 btn btn-primary"><i class="fa fa-save"></i> Guardar
                    Compra</button>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('insumos/search/css/index.css') }}">
@stop

@section('js')
    <script src="{{ asset('insumos/search/js/search.js') }}" type="module"></script>
    <script>
        document.querySelector('form').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        })
    </script>
    <script>
        $(document).on('click', '.editar-producto', function() {
            const index = $(this).data('index');
            const cantidad = $(this).data('cantidad');
            const precio = $(this).data('precio');
            const total = $(this).data('total');
            const prod = $(this).data('nombre');

            $('#modalCantidad').val(cantidad);
            $('#modalPrecio').val(precio);
            $('#modalTotal').val(total);
            $('#modalProd').val(prod);

            $('#editarModal').data('index', index);

            $('#editarModal').modal('show');
        });

        $('#modalCantidad, #modalPrecio').on('input', function() {
            const cantidad = parseFloat($('#modalCantidad').val()) || 0;
            const precio = parseFloat($('#modalPrecio').val()) || 0;
            const total = cantidad * precio;
            $('#modalTotal').val(total.toFixed(2));
        });

        $('#guardarCambios').on('click', function() {
            const index = $('#editarModal').data('index');

            const cantidad = $('#modalCantidad').val();
            const precio = parseFloat($('#modalPrecio').val()).toFixed(2);
            const total = parseFloat($('#modalTotal').val()).toFixed(2);

            const fila = $(`[data-index="${index}"]`).closest('tr');
            fila.find(`#cant\\[${index}\\]`).text(cantidad);
            fila.find(`input[name="cantidad[${index}]"]`).val(cantidad);

            fila.find(`#precio\\[${index}\\]`).text(precio);
            fila.find(`input[name="precio_unitario[${index}]"]`).val(precio);

            fila.find(`#total\\[${index}\\]`).text(total);
            fila.find(`input[name="total[${index}]"]`).val(total);

            const botonEditar = fila.find(`.editar-producto[data-index="${index}"]`);
            botonEditar.data('cantidad', cantidad);
            botonEditar.data('precio', precio);
            botonEditar.data('total', total);

            actualizarTotales();
            $('#editarModal').modal('hide');
        });


        function actualizarTotales() {
            let subtotal = 0;
            let isv = 0;
            let total = 0;
            const filas = document.getElementById('tabla_productos').querySelectorAll('tbody tr');
            const chkincluyeIsv = document.getElementById('precios_impuestos').checked;

            filas.forEach(fila => {
                const totalp = parseFloat(fila.children[4].textContent) || 0;
                const isvProducto = parseFloat(fila.children[0].getAttribute('data-isv')) || 0;

                if (isvProducto > 0) {
                    if (chkincluyeIsv) {
                        isv += totalp - (totalp / (1 + (isvProducto / 100)));
                        subtotal += totalp / (1 + (isvProducto / 100));
                    } else {
                        isv += totalp * (isvProducto / 100);
                        subtotal += totalp;
                    }
                } else {
                    isv += 0;
                    subtotal += totalp;
                }
            });

            total = subtotal + isv;

            document.getElementById('subtotal').textContent = subtotal.toFixed(2);
            document.getElementById('impuesto').textContent = isv.toFixed(2);
            document.getElementById('total').textContent = total.toFixed(2);
        }
    </script>

    <script>
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

            document.getElementById('modalCantidad').addEventListener('input', validarNumero);
            document.getElementById('modalPrecio').addEventListener('input', validarNumero);
            document.getElementById('modalTotal').addEventListener('input', validarNumero);
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnGuardar = document.getElementById('btnguardar');
            const form = document.getElementById('formId');
            const errorContainer = document.getElementById('error');
            const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

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
                const tablaProductos = document.querySelector('#tabla_productos tbody');
                const tieneProductos = tablaProductos && tablaProductos.rows.length > 0;
                const errorContainer = document.getElementById('error');
                let errors = false;

                errorContainer.innerHTML = "";

                if (!tieneProductos) {
                    displayError('No se agregó ningún producto para la compra.');
                    event.preventDefault();
                    return;
                }

                const slcTipoPago = document.getElementById('tipopago');
                if (slcTipoPago.value === "") {
                    errors = true;
                    displayError('El tipo de pago es obligatorio.');
                }

                const fecha = document.getElementById('fecha');
                if (fecha.value === "") {
                    errors = true;
                    displayError('La fecha es obligatoria.');
                }

                const factura = document.getElementById('factura');
                if (factura.value === "") {
                    errors = true;
                    displayError('El número de factura es obligatorio.');
                }

                const sclProveedor = document.getElementById('proveedor');
                if (sclProveedor.value === "") {
                    errors = true;
                    displayError('El proveedor es obligatorio.');
                }

                async function verificarAperturaCaja() {
                    try {
                        const responseApertura = await fetch('/verificar-apertura-caja');
                        const dataApertura = await responseApertura.json();

                        if (!dataApertura.apertura_abierta) {
                            const username =
                                "{{ \Illuminate\Support\Facades\Auth::user()->username }}";
                            errorContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>No se encontró una apertura de caja abierta para el usuario: "${username}" con la fecha de hoy.</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                            return true;
                        }
                        return false;
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
                        return true;
                    }
                }

                const aperturaError = await verificarAperturaCaja();
                errors = errors || aperturaError;

                if (errors) {
                    console.log('Existen errores, no se puede enviar el formulario.');
                    event.preventDefault();
                    return;
                }

                confirmModal.show();
            });


            document.getElementById('confirmSave').addEventListener('click', function() {
                form.submit();
                confirmModal.hide();
            });
        });
    </script>
@stop

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
                    <div class="form-group">
                        <label for="modalProd">Producto</label>
                        <input type="text" readonly class="form-control" id="modalProd" name="prod" required>
                    </div>
                    <div class="form-group">
                        <label for="modalCantidad">Cantidad</label>
                        <input type="number" class="form-control" id="modalCantidad" name="cantidad" required>
                    </div>
                    <div class="form-group">
                        <label for="modalPrecio">Precio Unitario</label>
                        <input type="number" class="form-control" id="modalPrecio" name="precio_unitario" required>
                    </div>
                    <div class="form-group">
                        <label for="modalTotal">Total</label>
                        <input type="text" class="form-control" id="modalTotal" name="total" readonly>
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
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirmación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas guardar la compra?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="confirmSave" class="btn btn-primary">Confirmar</button>
            </div>
        </div>
    </div>
</div>
