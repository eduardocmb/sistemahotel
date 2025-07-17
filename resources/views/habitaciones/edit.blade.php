@extends('adminlte::page')

@section('title', 'Editar Habitación')

@section('content_header')
    <h1>Editar Habitación - <span class="text-primary font-weight-bold">N°{{$habitacion->numero_habitacion}}</span></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Editar Habitación</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('habitaciones.update', $habitacion->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="numero_habitacion">Número de Habitación</label>
                                    <input type="text" readonly name="numero_habitacion" id="numero_habitacion"
                                           class="text-uppercase form-control @error('numero_habitacion') is-invalid @enderror"
                                           value="{{ old('numero_habitacion', $habitacion->numero_habitacion) }}" required>
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
                                           value="{{ old('tipo_habitacion', $habitacion->tipo_habitacion) }}" required
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
                                              oninput="this.value = this.value.toUpperCase()">{{ old('descripcion', $habitacion->descripcion) }}</textarea>
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
                                           value="{{ old('precio_diario', $habitacion->precio_diario) }}" step="0.01" required>
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
                                        <option {{$habitacion->estado == "DISPONIBLE" ? 'selected':''}} value="DISPONIBLE"
                                                {{ old('estado', $habitacion->estado) == 'DISPONIBLE' ? 'selected' : '' }}>DISPONIBLE</option>
                                        <option {{$habitacion->estado == "OCUPADA" ? 'selected':''}} value="OCUPADA"
                                                {{ old('estado', $habitacion->estado) == 'OCUPADA' ? 'selected' : '' }}>OCUPADA</option>
                                        <option {{$habitacion->estado == "MANTENIMIENTO" ? 'selected':''}} value="MANTENIMIENTO"
                                                {{ old('estado', $habitacion->estado) == 'MANTENIMIENTO' ? 'selected' : '' }}>MANTENIMIENTO</option>
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
                                           value="{{ old('capacidad', $habitacion->capacidad) }}" required>
                                    @error('capacidad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="observaciones">Observaciones</label>
                                    <textarea  oninput="this.value = this.value.toUpperCase()" class="form-control" name="observaciones" id="observaciones" rows="4">{{$habitacion->observaciones}}</textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Actualizar Habitación
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
