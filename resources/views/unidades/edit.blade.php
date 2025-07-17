@extends('adminlte::page')

@section('title', 'Editar Unidad')

@section('content_header')
    <h1>Editar Unidad</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Formulario de Edición</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('unidades.update', $unidadinsumo->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                oninput="this.value = this.value.toUpperCase()" name="nombre" id="nombre"
                                value="{{ old('nombre', $unidadinsumo->nombre) }}">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="contiene">Contiene</label>
                            <input type="number" class="form-control @error('contiene') is-invalid @enderror"
                                name="contiene" id="contiene" value="{{ old('contiene', $unidadinsumo->contiene) }}">
                            @error('contiene')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Guardar cambios
                            </button>
                            <a href="{{ route('unidades.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
