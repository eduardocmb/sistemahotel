@extends('adminlte::page')

@section('title', 'Editar Insumo')

@section('content_header')
    <h1>Editar Insumo</h1>
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
        <form action="{{ route('insumos.update', $insumo->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-header">
                <div class="">
                    <div class="container form-group">
                        <small class="rounded p-2 text-primary bg-primary">Datos Generales del Insumo</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="codigo_insumo" class="d-inline-flex align-items-center">
                                Código del Insumo
                            </label>
                            <input type="text" class="form-control @error('codigo_insumo') is-invalid @enderror"
                                id="codigo_insumo" {{ old('chkGenerarAuto', $insumo->chkGenerarAuto ?? '') ? 'readonly':'' }} readonly name="codigo_insumo" value="{{ old('codigo_insumo', $insumo->codigo) }}"
                                maxlength="9" oninput="this.value = this.value.toUpperCase()">
                            @error('codigo_insumo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="nombre">Nombre del Insumo</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $insumo->nombre) }}"
                            required>
                        @error('nombre')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $insumo->descripcion) }}</textarea>
                        @error('descripcion')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-3">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad"
                            value="{{ old('cantidad', $insumo->cantidad) }}" step="0.01" required>
                        @error('cantidad')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-3">
                        <label for="precio_unitario">Precio de Compra</label>
                        <input type="number" class="form-control" id="precio_unitario" name="precio_unitario"
                            value="{{ old('precio_unitario', $insumo->precio_compra) }}" step="0.01" required>
                        @error('precio_unitario')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-3">
                        <label for="precio_venta">Precio de Venta</label>
                        <input type="number" class="form-control" id="precio_venta" name="precio_venta"
                            value="{{ old('precio_venta', $insumo->precio_venta) }}" step="0.01" required>
                        @error('precio_venta')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-3">
                        <label for="utilidad">Utilidad</label>
                        <input type="number" readonly class="form-control" id="utilidad" name="utilidad"
                            value="{{ old('utilidad', $insumo->utilidad) }}" step="0.01" required>
                        @error('utilidad')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mt-3">
                        <label for="stock_minimo">Stock Mínimo</label>
                        <input type="number" class="form-control" id="stock_minimo" name="stock_minimo"
                            value="{{ old('stock_minimo', $insumo->stock_minimo) }}" step="0.01" required>
                        <div class="text-info">Le avisaremos cuando hayan pocos.</div>
                        @error('stock_minimo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mt-3">
                        <label for="impuesto_id">Impuesto</label>
                        <select class="form-control" id="impuesto_id" name="impuesto_id" required>
                            <option value="">Seleccione un impuesto</option>
                            @foreach ($impuestos as $impuesto)
                                <option value="{{ $impuesto->id }}"
                                    {{ old('impuesto_id', $insumo->impuesto_id) == $impuesto->id ? 'selected' : '' }}>
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
                                    {{ old('categoria', $insumo->categoria_id) == $categoria->id ? 'selected' : '' }}>
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
                    <small class="bg-primary p-2 rounded text-primary">Selección de Unidad y Proveedor</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="unidadinsumo_id">Unidad de Insumo</label>
                            <select class="form-control" id="unidadinsumo_id" name="unidadinsumo_id" required>
                                <option value="">Seleccione una unidad</option>
                                @foreach ($unidadinsumos as $unidad)
                                    <option value="{{ $unidad->id }}"
                                        {{ old('unidadinsumo_id', $insumo->unidadinsumo_id) == $unidad->id ? 'selected' : '' }}>
                                        {{ $unidad->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unidadinsumo_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="proveedor_id">Proveedor</label>
                            <select class="form-control" id="proveedor_id" name="proveedor_id" required>
                                <option value="">Seleccione un proveedor</option>
                                @foreach ($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}"
                                        {{ old('proveedor_id', $insumo->proveedor_id) == $proveedor->id ? 'selected' : '' }}>
                                        {{ $proveedor->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('proveedor_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card my-2">
                <div class="card-header">
                    <small class="bg-primary p-2 rounded text-primary">Fechas de Compra y Vencimiento</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="fecha_compra">Fecha de Compra</label>
                            <input type="date" class="form-control" id="fecha_compra" name="fecha_compra"
                                value="{{ old('fecha_compra', $insumo->fecha_compra) }}" required>
                            @error('fecha_compra')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_vencimiento">Fecha de Vencimiento</label>
                            <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento"
                                value="{{ old('fecha_vencimiento', $insumo->fecha_vencimiento) }}">
                            @error('fecha_vencimiento')
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
                <a href="{{ route('insumos.index') }}" class="btn text-light btn-warning">
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
    const txtcant = document.getElementById('cantidad');
    const precioCompraInput = document.getElementById("precio_unitario");
    const precioVentaInput = document.getElementById("precio_venta");
    const utilidadInput = document.getElementById("utilidad");
    const txtstock = document.getElementById('stock_minimo');

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

    function calcularUtilidad() {
        const precioCompra = parseFloat(precioCompraInput.value) || 0;
        const precioVenta = parseFloat(precioVentaInput.value) || 0;

        const utilidad = precioVenta - precioCompra;
        utilidadInput.value = utilidad > 0 ? utilidad.toFixed(2) : 0;
    }

    precioCompraInput.addEventListener("input", validarNumero);
    precioVentaInput.addEventListener("input", validarNumero);
    precioVentaInput.addEventListener("input", validarNumero);
    precioCompraInput.addEventListener("input", calcularUtilidad);
    txtstock.addEventListener("input", validarNumero);
    txtcant.addEventListener('input', validarNumero);
    calcularUtilidad();
</script>
@stop
