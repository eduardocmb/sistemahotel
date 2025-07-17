@extends('adminlte::page')

@section('title', 'Crear Rol')

@section('content_header')
    <h1>Crear Nuevo Rol</h1>
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

    <div class="card">
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="card-header">
                <h3>Datos del Rol</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="codigo" class="d-inline-flex align-items-center">
                            Código del Rol
                            <div class="form-check ml-5">
                                <input type="checkbox" oninput="this.value = this.value.toUpperCase()"
                                    class="form-check-input @error('codigo') is-invalid @enderror"
                                    value="Generar Automáticamente" {{old('chkGenerarAuto') ? 'checked':''}} name="chkGenerarAuto" id="chkGenerarAuto">
                                <label class="form-check-label" for="chkGenerarAuto">Generar
                                    Automáticamente</label>
                            </div>
                        </label>

                        <input type="text" oninput="this.value = this.value.toUpperCase()" class="form-control" id="codigo" name="codigo" value="{{ old('codigo') }}"
                            required>
                        @error('codigo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="rol">Nombre del Rol</label>
                        <input type="text" oninput="this.value = this.value.toUpperCase()" class="form-control" id="rol" name="rol" value="{{ old('rol') }}"
                            required>
                        @error('rol')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-2">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="ver_informacion" name="ver_informacion"
                                value="S" {{ old('ver_informacion') == 'S' ? 'checked' : '' }}>
                            <label class="form-check-label" for="ver_informacion">Ver Información</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="guardar" name="guardar" value="S"
                                {{ old('guardar') == 'S' ? 'checked' : '' }}>
                            <label class="form-check-label" for="guardar">Guardar</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="actualizar" name="actualizar" value="S"
                                {{ old('actualizar') == 'S' ? 'checked' : '' }}>
                            <label class="form-check-label" for="actualizar">Actualizar</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="eliminar" name="eliminar" value="S"
                                {{ old('eliminar') == 'S' ? 'checked' : '' }}>
                            <label class="form-check-label" for="eliminar">Eliminar</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="imprimir" name="imprimir" value="S"
                                {{ old('imprimir') == 'S' ? 'checked' : '' }}>
                            <label class="form-check-label" for="imprimir">Imprimir</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="reimprimir" name="reimprimir" value="S"
                                {{ old('reimprimir') == 'S' ? 'checked' : '' }}>
                            <label class="form-check-label" for="reimprimir">Reimprimir</label>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-2">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="finanzas" name="finanzas" value="S"
                                {{ old('finanzas') == 'S' ? 'checked' : '' }}>
                            <label class="form-check-label" for="finanzas">Finanzas</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Registrar
                </button>
                <a href="{{ route('roles.index') }}" class="btn btn-warning text-light">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    const chk = document.getElementById('chkGenerarAuto');
    const txtxcodigo = document.getElementById('codigo');
    function AgregarCodigoAutomatico() {
        if (chk.checked) {
            const key = 'ROLE';
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
                txtxcodigo.value = "ROL" + data.codigo;
            })
            .catch(error => console.error('Error:', error));
            txtxcodigo.readOnly = true;
        }else{
            txtxcodigo.readOnly = false;
            txtxcodigo.value = "";
        }
    }

    chk.addEventListener('change', AgregarCodigoAutomatico);
</script>
@stop
