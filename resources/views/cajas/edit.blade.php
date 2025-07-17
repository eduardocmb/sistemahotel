@extends('adminlte::page')

@section('title', 'Editar Caja')

@section('content')
    <div class="container mt-5">
        <h2>Editar Caja</h2>

        <form id="editForm" action="{{ route('cajas.update', $caja->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="editNumCaja">Número de Caja</label>
                <input type="text" class="form-control @error('numcaja') is-invalid @enderror" name="numcaja" id="editNumCaja"
                oninput="this.value = this.value.toUpperCase()" value="{{ old('numcaja', $caja->numcaja) }}" required>
                @error('numcaja')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="{{ route('cajas.index') }}" class="btn btn-warning">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
@endsection
