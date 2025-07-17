@extends('adminlte::page')

@section('title', 'Editar Departamento')

@section('content')
    <div class="container mt-5">
        <h2>Editar Departamento</h2>
        <a class="btn btn-secondary my-2 btn-sm" href="{{ route('departamentos.index') }}">
            <i class="fas fa-arrow-left"></i> Volver al Listado
        </a>

        <form action="{{ route('departamentos.update', $departamento->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="codigo_departamento">Código</label>
                <input type="text" class="form-control @error('codigo_departamento') is-invalid @enderror"
                       name="codigo_departamento" id="codigo_departamento" value="{{ old('codigo_departamento', $departamento->codigo) }}"
                       readonly>
                @error('codigo_departamento')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="departamento">Departamento</label>
                <input oninput="this.value = this.value.toUpperCase()" type="text" class="form-control @error('departamento') is-invalid @enderror"
                       name="departamento" id="departamento" value="{{ old('departamento', $departamento->departamento) }}">
                @error('departamento')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="{{ route('departamentos.index') }}" class="text-light btn btn-warning">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
@endsection
