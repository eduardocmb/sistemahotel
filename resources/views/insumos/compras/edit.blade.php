@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <a href="{{ route('insumo.index') }}" class="ml-2 btn text-light btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
    <h1 class="mt-2 ml-2">Gestionar Compra N°: <strong class="text-primary">{{ $cabcompra->codigocompra }}</strong></h1>
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

            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-info text-white">DATOS GENERALES</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label for="fecha">Fecha:</label>
                        <input type="date" readonly value="{{ $cabcompra->fecha_compra }}" id="fecha" name="fecha"
                            class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label for="tipopago">Pago:</label>
                        <select disabled name="tipo_pago" id="tipopago"
                            class="form-control @error('tipo_pago') is-invalid @enderror">
                            <option value="">SELECCIONE TIPO DE PAGO</option>
                            <option {{ old('tipo_pago', $cabcompra->tipo_pago) == 'CONTADO' ? 'selected' : '' }}
                                value="CONTADO">CONTADO</option>
                            <option {{ old('tipo_pago', $cabcompra->tipo_pago) == 'CHEQUE' ? 'selected' : '' }}
                                value="CHEQUE">CHEQUE</option>
                            <option {{ old('tipo_pago', $cabcompra->tipo_pago) == 'CREDITO' ? 'selected' : '' }}
                                value="CREDITO">CRÉDITO</option>
                        </select>
                        @error('tipo_pago')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="factura">Factura:</label>
                        <input readonly type="text" id="factura" value="{{ old('factura', $cabcompra->numfactura) }}"
                            name="factura" class="form-control @error('factura') is-invalid @enderror">
                        @error('factura')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="numero">Numero:</label>
                        <input type="text" value="{{ $cabcompra->codigocompra }}" readonly id="numero" name="numero" class="form-control">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-9">
                        <label for="proveedor">Proveedor:</label>
                        <select disabled id="proveedor" name="proveedor"
                            class="form-control @error('proveedor') is-invalid @enderror">
                            <option value="">Seleccione un Proveedor</option>
                            @foreach ($proveedores as $proveedor)
                                <option
                                    {{ old('proveedor', $cabcompra->proveedor_id) == $proveedor->id ? 'selected' : '' }}
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
        <!-- Tabla de productos -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">PRODUCTOS ORDENADOS</div>
            <div class="card-body">
                <table class="table table-bordered" id="tabla_productos">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $producto)
                        <tr>
                            <td data-isv="{{$producto['isv']}}">{{ $producto['producto_id'] }}</td>
                            <td>{{ $producto['producto'] }}</td>
                            <td>{{ $producto['cantidad'] }}</td>
                            <td>{{ $producto['precio_compra'] }}</td>
                            <td>{{ $producto['total'] }}</td>
                        </tr>
                        @endforeach
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
        <div class="text-center p-4">
            <div class="btn-group" role="group" aria-label="Opciones">
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-print"></i> Imprimir
                </button>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal">
                    <i class="fas fa-trash-alt"></i> Eliminar
                </button>
            </div>
        </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('search/css/index.css') }}">
@stop

@section('js')
    <script src="{{ asset('search/js/search.js') }}" type="module"></script>
    <script>
        document.querySelector('form').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        })
    </script>
<script>
        function actualizarTotales() {
            let subtotal = 0;
            let isv = 0;
            let total = 0;
            const filas = document.getElementById('tabla_productos').querySelectorAll('tbody tr');
            const chkincluyeIsv = document.getElementById('precios_impuestos').checked; // Checkbox para ISV

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
        actualizarTotales();
        document.getElementById('precios_impuestos').addEventListener('change', actualizarTotales);
    </script>
@stop

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este elemento? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
            </div>
        </div>
    </div>
</div>
