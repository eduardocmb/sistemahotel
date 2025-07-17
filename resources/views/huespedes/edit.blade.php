@extends('adminlte::page')

@section('title', 'Actualizar Cliente')

@section('content_header')
    <h1>Actualizar Huésped</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Editar datos del huésped - <span class="font-weight-bold text-primary">{{$huesped->nombre_completo}}</span></h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('huespedes.update', $huesped->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Fila para Código de Cliente y Nombre Completo -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codigo_cliente" class="d-inline-flex align-items-center">
                                        Código de Huésped
                                    </label>
                                    <input type="text" class="form-control @error('codigo_cliente') is-invalid @enderror"
                                        id="codigo_cliente" name="codigo_cliente" value="{{ old('codigo_cliente', $huesped->codigo_cliente) }}"
                                        maxlength="9" readonly>
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
                                        id="nombre_completo" name="nombre_completo" value="{{ old('nombre_completo', $huesped->nombre_completo) }}"
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
                                        <option value="" disabled>Seleccione una opción</option>
                                        <option value="DNI" {{ old('tipo_id', $huesped->tipo_id) == 'DNI' ? 'selected' : '' }}>DNI</option>
                                        <option value="PASAPORTE" {{ old('tipo_id', $huesped->tipo_id) == 'PASAPORTE' ? 'selected' : '' }}>PASAPORTE</option>
                                        <option value="CARNET DE EXTRANJERO" {{ old('tipo_id', $huesped->tipo_id) == 'CARNET DE EXTRANJERO' ? 'selected' : '' }}>CARNET DE EXTRANJERO</option>
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
                                        id="identificacion" name="identificacion" value="{{ old('identificacion', $huesped->identificacion) }}"
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
                                        id="rtn" name="rtn" value="{{ old('rtn', $huesped->rtn) }}"
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
                                        id="telefono" name="telefono" value="{{ old('telefono', $huesped->telefono) }}">
                                    @error('telefono')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="direccion">Dirección</label>
                                    <textarea class="form-control @error('direccion') is-invalid @enderror"
                                        id="direccion" name="direccion" oninput="this.value = this.value.toUpperCase()">{{ old('direccion', $huesped->direccion) }}</textarea>
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
                                        id="email" name="email" value="{{ old('email', $huesped->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button id="btnguardar" type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Actualizar
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
