@extends('adminlte::page')

@section('title', 'Editar Categoría')

@section('content')
    <div class="container mt-5">
        <h2>Editar Categoría</h2>

        <form id="editForm" action="{{ route('categorias.update', $categoria->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="editCategoria">Categoría</label>
                <input type="text" class="form-control @error('categoria') is-invalid @enderror" name="categoria" id="editCategoria"
                oninput="this.value = this.value.toUpperCase()" value="{{ old('categoria', $categoria->categoria) }}" required>
                @error('categoria')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="{{ route('categorias.index') }}" class="btn btn-warning">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
@endsection
