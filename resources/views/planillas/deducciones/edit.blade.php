@extends('adminlte::page')

@section('title', 'Editar Deducción')

@section('content_header')
    <h1>Editar Deducción</h1>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Formulario de Edición de Deducción</div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('deducciones.update', $deduccion->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input oninput="this.value = this.value.toUpperCase()" type="text" name="nombre" id="nombre" class="form-control"
                                        placeholder="Nombre de la deducción" value="{{ old('nombre', $deduccion->nombre) }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="tipo">Tipo</label>
                                    <select name="tipo" id="tipo" class="form-control">
                                        <option value="">Seleccione un tipo</option>
                                        <option value="PORCENTAJE" {{ old('tipo', $deduccion->tipo) == 'PORCENTAJE' ? 'selected' : '' }}>PORCENTAJE</option>
                                        <option value="MONTOTOTAL" {{ old('tipo', $deduccion->tipo) == 'MONTOTOTAL' ? 'selected' : '' }}>MONTO TOTAL</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="monto">Monto</label>
                                    <input type="number" step="0.01" name="monto" id="monto" class="form-control"
                                        placeholder="Monto de la deducción" value="{{ old('monto', $deduccion->monto) }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="activo">Estado</label>
                                    <select name="activo" id="activo" class="form-control">
                                        <option value="S" {{ old('activo', $deduccion->activo) == 'S' ? 'selected' : '' }}>ACTIVO</option>
                                        <option value="N" {{ old('activo', $deduccion->activo) == 'N' ? 'selected' : '' }}>INACTIVO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea oninput="this.value = this.value.toUpperCase()" name="descripcion" id="descripcion" class="form-control" rows="3"
                                        placeholder="Descripción de la deducción">{{ old('descripcion', $deduccion->descripcion) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Actualizar</button>
                            <a href="{{ route('deducciones.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
