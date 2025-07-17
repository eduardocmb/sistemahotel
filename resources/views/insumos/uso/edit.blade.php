@extends('adminlte::page')

@section('title', 'Editar Uso de Insumo')

@section('content_header')
    <h1>Editar Uso de Insumo</h1>
@stop

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>¡Error!</strong> Por favor, corrige los errores en el formulario.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Actualizar Datos del Uso de Insumo</h3>
        </div>
        <form action="{{ route('uso_insumos.update', $usoInsumo->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="insumo_id">Insumo</label>
                    <select disabled name="insumo_id" id="insumo_id"
                        class="form-control @error('insumo_id') is-invalid @enderror">
                        @foreach ($insumos as $insumo)
                            <option value="{{ $insumo->id }}"
                                {{ $usoInsumo->insumo_id == $insumo->id ? 'selected' : '' }}>
                                {{ $insumo->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('insumo_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="cantidad_usada">Cantidad Usada</label>
                    <input disabled type="number" name="cantidad_usada" id="cantidad_usada"
                        class="form-control @error('cantidad_usada') is-invalid @enderror"
                        value="{{ old('cantidad_usada', $usoInsumo->cantidad_usada) }}" min="1">
                    @error('cantidad_usada')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="ubicacion">Ubicación</label>
                    <input type="text" name="ubicacion" id="ubicacion"
                        class="form-control @error('ubicacion') is-invalid @enderror"
                        value="{{ old('ubicacion', $usoInsumo->ubicacion) }}">
                    @error('ubicacion')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="fecha_uso">Fecha de Uso</label>
                    <input readonly type="date" name="fecha_uso" id="fecha_uso"
                        class="form-control @error('fecha_uso') is-invalid @enderror"
                        value="{{ old('fecha_uso', $usoInsumo->fecha_uso) }}">
                    @error('fecha_uso')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción (Opcional)</label>
                    <textarea name="descripcion" id="descripcion" rows="3"
                        class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $usoInsumo->descripcion) }}</textarea>
                    @error('descripcion')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar
                </button>
                <a href="{{ route('uso_insumos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
@stop
