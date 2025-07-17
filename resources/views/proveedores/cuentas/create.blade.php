@extends('adminlte::page')

@section('title', 'Crear Cuenta por Pagar')

@section('content_header')
    <h1>Registrar Nueva Cuenta por Pagar</h1>
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

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Crear Cuenta por Pagar</div>

                    <div class="card-body">
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

                        <form action="{{ route('cuentas.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="codigo" class="form-label">Código</label>
                                    <input type="text" name="codigo" id="codigo"
                                        class="form-control @error('codigo') is-invalid @enderror"
                                        value="{{ old('codigo') }}" maxlength="9">
                                    @error('codigo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="fecha" class="form-label">Fecha</label>
                                    <input type="date" name="fecha" id="fecha"
                                        class="form-control @error('fecha') is-invalid @enderror"
                                        value="{{ old('fecha') }}">
                                    @error('fecha')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="proveedor_id" class="form-label">Proveedor</label>
                                    <select name="proveedor_id" id="proveedor_id"
                                        class="form-control @error('proveedor_id') is-invalid @enderror">
                                        <option value="">Seleccione un proveedor</option>
                                        @foreach ($proveedores as $proveedor)
                                            <option value="{{ $proveedor->id }}"
                                                {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                                {{ $proveedor->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('proveedor_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="numfactura" class="form-label">Número de Factura</label>
                                    <input type="text" name="numfactura" id="numfactura"
                                        class="form-control @error('numfactura') is-invalid @enderror"
                                        value="{{ old('numfactura') }}" maxlength="50">
                                    @error('numfactura')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="monto_total" class="form-label">Monto Total</label>
                                    <input type="number" step="0.01" name="monto_total" id="monto_total"
                                        class="form-control @error('monto_total') is-invalid @enderror"
                                        value="{{ old('monto_total') }}">
                                    @error('monto_total')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                                    <input type="date" name="fecha_vencimiento" id="fecha_vencimiento"
                                        class="form-control @error('fecha_vencimiento') is-invalid @enderror"
                                        value="{{ old('fecha_vencimiento') }}">
                                    @error('fecha_vencimiento')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select name="estado" id="estado"
                                        class="form-control @error('estado') is-invalid @enderror">
                                        <option value="PENDIENTE" {{ old('estado') == 'PENDIENTE' ? 'selected' : '' }}>
                                            PENDIENTE</option>
                                        <option value="PAGADO" {{ old('estado') == 'PAGADO' ? 'selected' : '' }}>PAGADO
                                        </option>
                                    </select>
                                    @error('estado')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="notas" class="form-label">Notas</label>
                                    <textarea name="notas" id="notas" class="form-control @error('notas') is-invalid @enderror" rows="3">{{ old('notas') }}</textarea>
                                    @error('notas')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="m-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Registrar
                                </button>
                                <a href="{{ route('cuentas.index') }}" class="btn text-light btn-warning">
                                    <i class="fas fa-arrow-left"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .form-control {
            border-radius: 0.25rem;
        }
    </style>
@stop

@section('js')
    <script>
       function agregarcodigo(){
        const txtcodigo = document.getElementById('codigo');
        const key = 'CTPG';
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
                txtcodigo.value = "CTA" + data.codigo;
            })
            .catch(error => console.error('Error:', error));
        txtcodigo.readOnly = true;
       }
       agregarcodigo();
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
        document.getElementById('monto_total').addEventListener('input', validarNumero);
    </script>
@stop
