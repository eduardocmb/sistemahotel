@extends('adminlte::page')

@section('title', 'Editar Proveedor')

@section('content')
    <div class="container mt-5">
        <h2>Editar Proveedor</h2>

        <form id="editForm" action="{{ route('proveedores.update', $proveedor->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="codigo_proveedor">Código del Proveedor</label>
                <input type="text" class="form-control @error('codigo_proveedor') is-invalid @enderror"
                       id="codigo_proveedoredit" name="codigo_proveedor" value="{{ old('codigo_proveedor', $proveedor->codigo) }}"
                       readonly maxlength="9" oninput="this.value = this.value.toUpperCase()">
                @error('codigo_proveedor')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="editNombre">Nombre</label>
                <input oninput="this.value = this.value.toUpperCase()" type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" id="editNombre"
                       value="{{ old('nombre', $proveedor->nombre) }}">
                @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="editTelefono">Teléfono</label>
                <input oninput="this.value = this.value.toUpperCase()" type="text" class="form-control @error('telefono') is-invalid @enderror" name="telefono" id="editTelefono"
                       value="{{ old('telefono', $proveedor->telefono) }}">
                @error('telefono')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="editEmail">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="editEmail"
                       value="{{ old('email', $proveedor->email) }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="editDireccion">Dirección</label>
                <textarea oninput="this.value = this.value.toUpperCase()" class="form-control @error('direccion') is-invalid @enderror" name="direccion" id="editDireccion">{{ old('direccion', $proveedor->direccion) }}</textarea>
                @error('direccion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="{{ route('proveedores.index') }}" class="btn btn-warning">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
@endsection
