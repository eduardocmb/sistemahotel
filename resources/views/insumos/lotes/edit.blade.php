@extends('adminlte::page')

@section('title', 'Editar Lote de Producto')

@section('content_header')
    <h1>Editar Lote de <span class="font-weight-bold text-primary">{{ $producto->nombre }}</span> con Id: {{$lote->id}}</h1>
    <a class="btn btn-secondary my-2 btn-sm" href="{{ route('lotes.insumos.index', $producto->codigo) }}">
        <i class="fas fa-arrow-left"></i> Volver al Listado
    </a>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
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
                <div class="card-header">
                    <h3 class="card-title">Formulario de Edición de Lote</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('loteinsumos.update', $lote->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input value="{{old('codigoprod', $producto->codigo)}}" name="codigoprod" type="text" hidden>

                        <div class="form-group">
                            <label for="fecha">Fecha de Ingreso</label>
                            <input readonly type="date" class="form-control" id="fecha" name="fecha" required value="{{ old('fecha', $lote->fecha) }}">
                            @error('fecha')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="precio_compra">Precio de Compra</label>
                            <input type="number" class="form-control" id="precio_compra" name="precio_compra" step="0.01" min="0" required value="{{ old('precio_compra', $lote->precio_compra) }}">
                            @error('precio_compra')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="cantidad">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required value="{{ old('cantidad', $lote->cantidad) }}">
                            @error('cantidad')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="precio_compra">Utilidad por {{$presentacion->nombre ." / ".$presentacion->contiene}}</label>
                            <input readonly type="number" class="form-control" id="utilidad" value="{{old('utilidad')}}" name="utilidad" step="0.01" min="0" required>
                            @error('precio_compra')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="fecha_vencimiento">Fecha de Vencimiento (Opcional)</label>
                            <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" value="{{ old('fecha_vencimiento', $lote->fecha_vencimiento) }}">
                            @error('fecha_vencimiento')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
@stop

@section('js')
    <script>
        const cant = document.getElementById('cantidad');
        const compra = document.getElementById('precio_compra');
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
    compra.addEventListener('input', validarNumero);
    cant.addEventListener('input', validarNumero);

    function calcularUtilidad() {
        var cantidad = document.getElementById('cantidad').value;
        var precio_compra = document.getElementById('precio_compra').value;
        var precio_venta = {{$producto->precio_venta}}
        var presentacionContiene = {{$presentacion->contiene}}

        if (cantidad && precio_compra && precio_venta && presentacionContiene) {
            var costoUnitario = (precio_compra / cantidad) * presentacionContiene;
            var utilidad = precio_venta - costoUnitario;

            document.getElementById('utilidad').value = utilidad.toFixed(2);
        }
    }

    document.getElementById('cantidad').addEventListener('input', calcularUtilidad);
    document.getElementById('precio_compra').addEventListener('input', calcularUtilidad);
    calcularUtilidad();
    </script>
@stop
