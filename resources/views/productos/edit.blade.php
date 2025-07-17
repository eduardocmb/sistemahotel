@extends('adminlte::page')

@section('title', 'Editar Producto')

@section('content_header')
    <h1>Editar Producto</h1>
    <a class="btn btn-secondary my-2 btn-sm" href="{{ route('productos.index') }}">
        <i class="fas fa-arrow-left"></i> Volver al Listado
    </a>
@stop

@section('content')
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
        <form action="{{ route('productos.update', $producto->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-header">
                <div class="">
                    <div class="container form-group">
                        <small class="rounded p-2 text-primary bg-primary">Datos Generales del Producto</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="codigo_Producto" class="d-inline-flex align-items-center">
                                Código del Producto
                            </label>
                            <input type="text" class="form-control @error('codigo_Producto') is-invalid @enderror"
                                id="codigo_Producto"
                                {{ old('chkGenerarAuto', $producto->chkGenerarAuto ?? '') ? 'readonly' : '' }} readonly
                                name="codigo_Producto" value="{{ old('codigo_Producto', $producto->codigo) }}"
                                maxlength="9" oninput="this.value = this.value.toUpperCase()">
                            @error('codigo_Producto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="nombre">Nombre del Producto</label>
                        <input type="text" oninput="this.value = this.value.toUpperCase()" class="form-control"
                            id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
                        @error('nombre')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-5 mt-3">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" oninput="this.value = this.value.toUpperCase()" id="descripcion" name="descripcion"
                            rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
                        @error('descripcion')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mt-3">
                        <label for="stock_minimo">Stock Mínimo</label>
                        <input type="number" class="form-control" id="stock_minimo" name="stock_minimo"
                            value="{{ old('stock_minimo', $producto->stock_minimo) }}" step="0.01" required>
                        <div class="text-info">Le avisaremos cuando hayan pocos.</div>
                        @error('stock_minimo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-3">
                        <label for="tipo_producto">Tipo de Producto</label>
                        <select class="form-control" id="tipo_producto" name="tipo_producto" required>
                            <option value="">Seleccione un Tipo</option>
                            <option
                                {{ old('tipo_producto', $producto->tipo_producto) == 'PRODUCTO FINAL' ? 'selected' : '' }}
                                value="productofinal">
                                PRODUCTO FINAL</option>
                            <option {{ old('tipo_producto', $producto->tipo_producto) == 'SERVICIO' ? 'selected' : '' }}
                                value="servicio">SERVICIO
                            </option>
                        </select>
                        @error('tipo_producto')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-3">
                        <label for="stock_minimo">Precio Venta</label>
                        <input type="number" class="form-control" id="precio_venta" name="precio_venta"
                            value="{{ old('precio_venta', $producto->precio_venta) }}" step="0.01" required>
                        @error('precio_venta')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-3">
                        <label for="impuesto_id">Impuesto</label>
                        <select class="form-control" id="impuesto_id" name="impuesto_id" required>
                            <option value="">Seleccione un impuesto</option>
                            @foreach ($impuestos as $impuesto)
                                <option value="{{ $impuesto->id }}"
                                    {{ old('impuesto_id', $producto->impuesto_id) == $impuesto->id ? 'selected' : '' }}>
                                    {{ $impuesto->nombre }} ({{ $impuesto->porcentaje }}%)
                                </option>
                            @endforeach
                        </select>
                        @error('impuesto_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-3">
                        <label for="categoria">Categoría</label>
                        <select class="form-control" id="categoria" name="categoria" required>
                            <option value="">Seleccione una categoría</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}"
                                    {{ old('categoria', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->categoria }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card my-2">
                <div class="card-header">
                    <small class="bg-primary p-2 rounded text-primary">Selección de Unidad</small>
                </div>
                <div class="card-body">
                    <div id="unidad-container" class="row">
                        <div class="col-md-6">
                            <label for="unidadproductoentrada_id">Unidad de Entrada</label>
                            <select class="form-control" id="unidadproductoentrada_id" name="unidadproductoentrada_id">
                                <option value="">Seleccione una unidad</option>
                                @foreach ($unidadproductos as $unidad)
                                    <option value="{{ $unidad->id }}"
                                        {{ old('unidadproductoentrada_id', $producto->unidad_entrada_id) == $unidad->id ? 'selected' : '' }}>
                                        {{ $unidad->nombre }} / {{ $unidad->contiene }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unidadproductoentrada_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="unidadproductosalida_id">Unidad de Salida</label>
                            <select class="form-control" id="unidadproductosalida_id" name="unidadproductosalida_id">
                                <option value="">Seleccione una unidad</option>
                                @foreach ($unidadproductos as $unidad)
                                    <option value="{{ $unidad->id }}"
                                        {{ old('unidadproductosalida_id', $producto->unidad_salida_id) == $unidad->id ? 'selected' : '' }}>
                                        {{ $unidad->nombre }} / {{ $unidad->contiene }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unidadproductosalida_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="m-2">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="{{ route('productos.index') }}" class="btn text-light btn-warning">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div> <!-- Cierre del card -->
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipoProductoSelect = document.getElementById('tipo_producto');
            const unidadContainer = document.getElementById('unidad-container');
            const stockMinimoInput = document.getElementById('stock_minimo');

            tipoProductoSelect.addEventListener('change', function() {
                if (this.value === 'servicio') {
                    unidadContainer.style.display = 'none';

                    stockMinimoInput.readOnly = true;
                    stockMinimoInput.value = '0';
                } else {
                    unidadContainer.style.display = 'flex';

                    stockMinimoInput.readOnly = false;
                }
            });

            tipoProductoSelect.dispatchEvent(new Event('change'));
        });
    </script>
    <script>
        const precioVentaInput = document.getElementById("precio_venta");
        const stock = document.getElementById('stock_minimo')

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

        precioVentaInput.addEventListener("input", validarNumero);
        txtcant.addEventListener('input', validarNumero);
        stock.addEventListener('input', validarNumero);
    </script>
@stop
