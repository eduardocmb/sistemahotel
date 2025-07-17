@extends('adminlte::page')

@section('title', 'Agregar Nueva Habitación')

@section('content_header')
    <h1>Agregar Nueva Habitación</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Formulario para Nueva Habitación</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('habitaciones.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="numero_habitacion">Número de Habitación</label>
                                    <input type="text" name="numero_habitacion" id="numero_habitacion"
                                           class="text-uppercase form-control @error('numero_habitacion') is-invalid @enderror"
                                           value="{{ old('numero_habitacion') }}" required
                                           oninput="this.value = this.value.toUpperCase()">
                                    @error('numero_habitacion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="tipo_habitacion">Tipo de Habitación</label>
                                    <input type="text" name="tipo_habitacion" id="tipo_habitacion"
                                           class="text-uppercase form-control @error('tipo_habitacion') is-invalid @enderror"
                                           value="{{ old('tipo_habitacion') }}" required
                                           oninput="this.value = this.value.toUpperCase()">
                                    @error('tipo_habitacion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea name="descripcion" id="descripcion"
                                              class="text-uppercase form-control @error('descripcion') is-invalid @enderror"
                                              rows="4"
                                              oninput="this.value = this.value.toUpperCase()">{{ old('descripcion') }}</textarea>
                                    @error('descripcion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="precio_diario">Precio Diario</label>
                                    <input oninput="validatePositiveNumber(this)" type="number" name="precio_diario"
                                           id="precio_diario" class="text-uppercase form-control @error('precio_diario') is-invalid @enderror"
                                           value="{{ old('precio_diario') }}" step="0.01" required>
                                    @error('precio_diario')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="estado">Estado</label>
                                    <select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" required>
                                        <option value="">SELECCIONE UNA OPCIÓN</option>
                                        <option value="DISPONIBLE" {{ old('estado') == 'DISPONIBLE' ? 'selected' : '' }}>DISPONIBLE</option>
                                        <option value="OCUPADA" {{ old('estado') == 'OCUPADA' ? 'selected' : '' }}>OCUPADA</option>
                                        <option value="MANTENIMIENTO" {{ old('estado') == 'MANTENIMIENTO' ? 'selected' : '' }}>MANTENIMIENTO</option>
                                    </select>
                                    @error('estado')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="capacidad">Capacidad</label>
                                    <input type="number" oninput="validatePositiveNumber(this)" name="capacidad"
                                           id="capacidad" class="text-uppercase form-control @error('capacidad') is-invalid @enderror"
                                           value="{{ old('capacidad') }}" required>
                                    @error('capacidad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="observaciones">Observaciones</label>
                                    <textarea class="form-control"  oninput="this.value = this.value.toUpperCase()" name="observaciones" id="observaciones" rows="4"></textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Guardar Habitación
                                    </button>
                                    <a href="{{ route('habitaciones.index') }}" class="btn btn-warning text-light">
                                        <i class="fas fa-arrow-left"></i> Cancelar
                                    </a>
                                </div>
                            </div>
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
    function validatePositiveNumber(input) {
        let value = input.value;
        if (value !== '' && (isNaN(value) || value <= 0)) {
            input.value = 1;
        }
    }
</script>
@stop
