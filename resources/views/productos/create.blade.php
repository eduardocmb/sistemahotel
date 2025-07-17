@extends('adminlte::page')

@section('title', 'Crear Producto')

@section('content_header')
    <h1>Crear Nuevo Producto</h1>
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
        <form action="{{ route('productos.store') }}" method="POST">
            @csrf
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
                            <label for="codigo_producto" class="d-inline-flex align-items-center">
                                Código del producto
                                <div class="form-check ml-5">
                                    <input type="checkbox" oninput="this.value = this.value.toUpperCase()"
                                        class="form-check-input @error('codigo_producto') is-invalid @enderror"
                                        value="Generar Automáticamente" {{ old('chkGenerarAuto') ? 'checked' : '' }}
                                        name="chkGenerarAuto" id="chkGenerarAuto">
                                    <label class="form-check-label" for="chkGenerarAuto">Generar
                                        Automáticamente</label>
                                </div>
                            </label>
                            <input type="text" class="form-control @error('codigo_producto') is-invalid @enderror"
                                id="codigo_producto" {{ old('chkGenerarAuto') ? 'readonly' : '' }} name="codigo_producto"
                                value="{{ old('codigo_producto') }}" maxlength="9"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('codigo_producto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="nombre">Nombre del Producto</label>
                        <input type="text" oninput="this.value = this.value.toUpperCase()" class="form-control"
                            id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-5 mt-3">
                        <label for="descripcion">Descripción</label>
                        <textarea oninput="this.value = this.value.toUpperCase()" class="form-control" id="descripcion" name="descripcion"
                            rows="3">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-3">
                        <label for="tipo_producto">Tipo de Producto</label>
                        <select class="form-control" id="tipo_producto" name="tipo_producto" required>
                            <option value="">Seleccione un Tipo</option>
                            <option {{ old('tipo_producto') == 'productofinal' ? 'selected' : '' }} value="productofinal">
                                PRODUCTO FINAL</option>
                            <option {{ old('tipo_producto') == 'servicio' ? 'selected' : '' }} value="servicio">SERVICIO
                            </option>
                        </select>
                        @error('tipo_producto')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mt-3">
                        <label for="stock_minimo">Stock Mínimo</label>
                        <input type="number" class="form-control" id="stock_minimo" name="stock_minimo"
                            value="{{ old('stock_minimo') }}" step="0.01" required>
                        <div class="text-info">Le avisaremos cuando hayan pocos.</div>
                        @error('stock_minimo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-5 mt-3">
                        <label for="stock_minimo">Precio Venta</label>
                        <input type="number" class="form-control" id="precio_venta" name="precio_venta"
                            value="{{ old('precio_venta') }}" step="0.01" required>
                        @error('precio_venta')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mt-3">
                        <label for="impuesto_id">Impuesto</label>
                        <select class="form-control" id="impuesto_id" name="impuesto_id" required>
                            <option value="">Seleccione un impuesto</option>
                            @foreach ($impuestos as $impuesto)
                                <option value="{{ $impuesto->id }}"
                                    {{ old('impuesto_id') == $impuesto->id ? 'selected' : '' }}>
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
                                    {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
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
                                        {{ old('unidadproductoentrada_id') == $unidad->id ? 'selected' : '' }}>
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
                                        {{ old('unidadproductosalida_id') == $unidad->id ? 'selected' : '' }}>
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
                    <i class="fas fa-save"></i> Registrar
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
    <script>
        const chk = document.getElementById('chkGenerarAuto');
        const txtcodigo = document.getElementById('codigo_producto');
        const btnguardar = document.getElementById('btnguardar');

        function AgregarCodigoAutomatico() {
            if (chk.checked) {
                const key = 'PROD';
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
                        txtcodigo.value = "PRD" + data.codigo;
                    })
                    .catch(error => console.error('Error:', error));
                txtcodigo.readOnly = true;
            } else {
                txtcodigo.readOnly = false;
                txtcodigo.value = "";
            }
        }

        chk.addEventListener('change', AgregarCodigoAutomatico);
    </script>
@stop
