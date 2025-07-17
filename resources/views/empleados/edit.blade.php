@extends('adminlte::page')

@section('title', 'Editar Empleado')

@section('content_header')
    <h1>Editar Empleado</h1>
    <a class="btn btn-secondary my-2 btn-sm" href="{{ route('empleados.index') }}">
        <i class="fas fa-arrow-left"></i> Volver al Listado
    </a>
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
        <form action="{{ route('empleados.update', $empleado->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div class="container form-group">
                        <small class="rounded p-2 text-primary bg-primary">Datos Generales del Empleado</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 my-1">
                        <label for="dni">N° Identificación</label>
                        <input type="text" disabled value="{{ old('dni', $empleado->dni) }}" class="form-control" name="dni" id="dni"
                            required>
                        @error('dni')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 my-1">
                        <label for="nombrecompleto">Nombre Completo</label>
                        <input type="text" oninput="this.value = this.value.toUpperCase()"
                            value="{{ old('nombrecompleto', $empleado->nombrecompleto) }}" class="form-control" name="nombrecompleto"
                            id="nombrecompleto" required>
                        @error('nombrecompleto')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 my-1">
                        <label for="direccion">Dirección</label>
                        <textarea name="direccion" oninput="this.value = this.value.toUpperCase()" class="form-control" id="direccion">{{ old('direccion', $empleado->direccion) }}</textarea>
                        @error('direccion')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 my-1">
                        <label for="fechanac">Fecha de Nacimiento</label>
                        <input type="date" name="fechanac" class="form-control" id="fechanac" value="{{ old('fechanac', $empleado->fechanac) }}">
                        @error('fechanac')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 my-1">
                        <label for="fechaing">Fecha de Ingreso</label>
                        <input type="date" name="fechaing" class="form-control" id="fechaing" value="{{ old('fechaing', $empleado->fechaingreso) }}">
                        @error('fechaing')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 my-1">
                        <label for="telefono">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" id="telefono" value="{{ old('telefono', $empleado->telefono) }}">
                        @error('telefono')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 my-1">
                        <label for="genero">Genero</label>
                        <select class="form-control" name="genero" id="">
                            <option value="">SELECCIONE UN GENERO</option>
                            <option {{old('genero', $empleado->genero) == "MASCULINO" ? 'selected':''}} value="MASCULINO">MASCULINO</option>
                            <option {{old('genero', $empleado->genero) == "FEMENINO" ? 'selected':''}} value="FEMENINO">FEMENINO</option>
                        </select>
                        @error('genero')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 my-1">
                        <label for="departamento_id">Departamento</label>
                        <select class="form-control" id="departamento_id" name="departamento_id" required>
                            <option value="">Seleccione un departamento</option>
                            @foreach ($departamentos as $departamento)
                                <option value="{{ $departamento->id }}"
                                    {{ old('departamento_id', $empleado->departamento_id) == $departamento->id ? 'selected' : '' }}>
                                    {{ $departamento->departamento }}
                                </option>
                            @endforeach
                        </select>
                        @error('departamento_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 my-1">
                        <label for="tipo">Contratación</label>
                        <select class="form-control" name="tipo" id="tipo">
                            <option value="">Seleccione un tipo</option>
                            <option {{ old('tipo', $empleado->trabajotipo) == "PERMANENTE" ? 'selected' : '' }} value="PERMANENTE">PERMANENTE</option>
                            <option {{ old('tipo', $empleado->trabajotipo) == "DIARIO" ? 'selected' : '' }} value="DIARIO">DIARIO</option>
                            <option {{ old('tipo', $empleado->trabajotipo) == "TEMPORAL" ? 'selected' : '' }} value="TEMPORAL">TEMPORAL</option>
                            <option {{ old('tipo', $empleado->trabajotipo) == "POR HORA" ? 'selected' : '' }} value="POR HORA">POR HORA</option>
                        </select>
                        @error('tipo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 my-1">
                        <label for="salario">Salario</label>
                        <input type="number" step="0.01" value="{{ old('salario', $empleado->salario) }}" class="form-control"
                            name="salario" id="salario" required>
                        @error('salario')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 my-1">
                        <label for="estado">Estado</label>
                        <select class="form-control" id="estado" name="estado" required>
                            <option value="">Seleccione un estado</option>
                            <option value="ACTIVO" {{ old('estado', $empleado->estado) == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                            <option value="INACTIVO" {{ old('estado', $empleado->estado) == 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                        </select>
                        @error('estado')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="m-4">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="{{ route('empleados.index') }}" class="btn text-light btn-warning">
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
        document.getElementById('salario').addEventListener('input', validarNumero);
    </script>
@stop
