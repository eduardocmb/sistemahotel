@extends('adminlte::page')

@section('title', 'Agregar Nuevo Cliente')

@section('content_header')
    <h1>Agregar Nuevo Huésped</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Agregar nuevo huésped</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('huespedes.store') }}" method="POST">
                        @csrf

                        <!-- Fila para Código de Cliente y Nombre Completo -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codigo_cliente" class="d-inline-flex align-items-center">
                                        Código de Huésped
                                        <div class="form-check ml-5">
                                            <input type="checkbox"
                                                class="form-check-input @error('codigo_cliente') is-invalid @enderror"
                                                value="Generar Automáticamente" {{old('chkGenerarAuto') ? 'checked':''}} name="chkGenerarAuto" id="chkGenerarAuto">
                                            <label class="form-check-label" for="chkGenerarAuto">Generar
                                                Automáticamente</label>
                                        </div>
                                    </label>
                                    <input type="text" class="form-control @error('codigo_cliente') is-invalid @enderror"
                                        id="codigo_cliente" {{old('chkGenerarAuto') ? 'readonly':''}} name="codigo_cliente" value="{{ old('codigo_cliente') }}"
                                        maxlength="9" oninput="this.value = this.value.toUpperCase()">
                                    @error('codigo_cliente')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre_completo">Nombre Completo</label>
                                    <input type="text"
                                        class="form-control @error('nombre_completo') is-invalid @enderror"
                                        id="nombre_completo" name="nombre_completo" value="{{ old('nombre_completo') }}"
                                        oninput="this.value = this.value.toUpperCase()">
                                    @error('nombre_completo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Fila para Tipo de Identificación y Identificación -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo_id">Tipo de Identificación</label>
                                    <select class="form-control @error('tipo_id') is-invalid @enderror"
                                        id="tipo_id" name="tipo_id">
                                        <option value="">Seleccione una opción</option>
                                        <option {{old('tipo_id')== "DNI"? 'selected':''}} value="DNI">DNI</option>
                                        <option {{old('tipo_id')== "PASAPORTE"? 'selected':''}} value="PASAPORTE">PASAPORTE</option>
                                        <option {{old('tipo_id')== "CARNET DE EXTRANJERO"? 'selected':''}} value="CARNET DE EXTRANJERO">CARNET DE EXTRANJERO</option>
                                    </select>
                                    @error('tipo_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="identificacion">Identificación</label>
                                    <input type="text" class="form-control @error('identificacion') is-invalid @enderror"
                                        id="identificacion" name="identificacion" value="{{ old('identificacion') }}"
                                        oninput="this.value = this.value.toUpperCase()">
                                    @error('identificacion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="rtn">RTN</label>
                                    <input type="text" max="14" class="form-control @error('rtn') is-invalid @enderror"
                                        id="rtn" name="rtn" value="{{ old('rtn') }}"
                                        oninput="this.value = this.value.toUpperCase()">
                                    @error('rtn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Fila para Teléfono y Dirección -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                                        id="telefono" name="telefono" value="{{ old('telefono') }}">
                                    @error('telefono')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="direccion">Dirección</label>
                                    <textarea class="form-control @error('direccion') is-invalid @enderror"
                                        id="direccion" name="direccion" oninput="this.value = this.value.toUpperCase()">{{ old('direccion') }}</textarea>
                                    @error('direccion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Fila para Correo Electrónico -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Correo Electrónico</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button id="btnguardar" type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Registrar
                            </button>
                            <a href="{{ route('huespedes.index') }}" class="btn text-light btn-warning">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                        </div>
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
    const chk = document.getElementById('chkGenerarAuto');
    const txtcodigo_cliente = document.getElementById('codigo_cliente');
    const btnguardar = document.getElementById('btnguardar');
    function AgregarCodigoAutomatico() {
        if (chk.checked) {
            const key = 'CLIE';
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
                txtcodigo_cliente.value = "CLI" + data.codigo;
            })
            .catch(error => console.error('Error:', error));
            txtcodigo_cliente.readOnly = true;
        }else{
            txtcodigo_cliente.readOnly = false;
            txtcodigo_cliente.value = "";
        }
    }

    chk.addEventListener('change', AgregarCodigoAutomatico);
</script>
<script>
    function validarNumero(event) {
    const input = event.target;
    const valor = input.value;

    if (valor === "") {
        return;
    }

    if(valor === 0){
        return;
    }

    const valorNumerico = parseFloat(valor);
    if (isNaN(valorNumerico) || valorNumerico < 0) {
        input.value = 1;
    }
}
</script>
@stop
