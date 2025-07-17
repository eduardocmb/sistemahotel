@extends('adminlte::page')

@section('title', 'Editar Turno')

@section('content')
    <div class="container mt-5">
        <h2>Editar Turno</h2>

        <form id="editForm" action="{{ route('turnos.update', $turno->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="editCodigo">Código</label>
                <input readonly type="text" class="form-control @error('codigo') is-invalid @enderror" name="codigo" id="editCodigo"
                oninput="this.value = this.value.toUpperCase()" value="{{ old('codigo', $turno->codigo) }}" required>
                @error('codigo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="editTurno">Turno</label>
                <input type="text" oninput="this.value = this.value.toUpperCase()" class="form-control @error('turno') is-invalid @enderror" name="turno" id="editTurno"
                value="{{ old('turno', $turno->turno) }}" required>
                @error('turno')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="{{ route('turnos.index') }}" class="btn btn-warning">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
@endsection
