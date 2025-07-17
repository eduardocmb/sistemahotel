@extends('adminlte::page')

@section('title', 'Editar Gasto')

@section('content_header')
    <h1>Editar Gasto</h1>
    <a class="btn btn-secondary my-2 btn-sm" href="{{ route('gastos.index') }}">
        <i class="fas fa-arrow-left"></i> Volver al Listado
    </a>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Actualizar datos del gasto</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('gastos.update', $gasto->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="tipo">Tipo de Gasto</label>
                                    <input oninput="this.value = this.value.toUpperCase()" type="text" class="form-control @error('tipo') is-invalid @enderror"
                                           id="tipo" name="tipo" value="{{ old('tipo', $gasto->tipo) }}">
                                    @error('tipo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="monto">Monto</label>
                                    <input type="number" step="0.01" class="form-control @error('monto') is-invalid @enderror"
                                           id="monto" readonly name="monto" value="{{ old('monto', $gasto->monto) }}">
                                    @error('monto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                            <div class="col-4"><div class="form-group">
                                <label for="fecha">Fecha</label>
                                <input type="date" readonly class="form-control @error('fecha') is-invalid @enderror"
                                       id="fecha" name="fecha" value="{{ old('fecha', $gasto->fecha) }}">
                                @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div></div>
                        </div>

                        <!-- Campo Descripción -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea oninput="this.value = this.value.toUpperCase()" class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion">{{ old('descripcion', $gasto->descripcion) }}</textarea>
                                    @error('descripcion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Actualizar
                            </button>
                            <a href="{{ route('gastos.index') }}" class="btn btn-warning text-light">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
