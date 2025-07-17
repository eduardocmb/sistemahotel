@extends('adminlte::page')

@section('title', 'Editar Rol')

@section('content_header')
    <h1>Editar Rol</h1>
@stop

@section('content')
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
        <form action="{{ route('roles.update', $rol->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-header">
                <h3>Datos del Rol</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="codigo" class="d-inline-flex align-items-center">
                            Código del Rol
                        </label>
                        <input type="text" oninput="this.value = this.value.toUpperCase()" class="form-control" id="codigo" name="codigo" value="{{ old('codigo', $rol->codigo) }}" required readonly>
                        @error('codigo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="rol">Nombre del Rol</label>
                        <input type="text" oninput="this.value = this.value.toUpperCase()" class="form-control" id="rol" name="rol" value="{{ old('rol', $rol->rol) }}" required>
                        @error('rol')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mt-3">
                    @foreach (['ver_informacion', 'guardar', 'actualizar', 'eliminar', 'imprimir', 'reimprimir', 'finanzas'] as $field)
                        <div class="col-md-2">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="{{ $field }}" name="{{ $field }}" value="S" {{ old($field, $rol->$field) == 'S' ? 'checked' : '' }}>
                                <label class="form-check-label" for="{{ $field }}">{{ ucfirst(str_replace('_', ' ', $field)) }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Actualizar
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
@stop
