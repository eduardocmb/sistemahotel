@extends('adminlte::page')

@section('title', 'Eliminar Uso de Insumo')

@section('content_header')
    <h1>Eliminar Uso de Insumo</h1>
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

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>¡Error!</strong> Por favor, corrige los errores en el formulario.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title text-danger">¿Estás seguro de que deseas eliminar este Uso de Insumo?</h3>
        </div>
        <form action="{{ route('uso_insumos.destroy', $usoInsumo->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="card-body">
                <p><strong>Insumo:</strong> {{ $insumo->nombre }}</p>
                <p><strong>Cantidad Usada:</strong> {{ $usoInsumo->cantidad_usada }}</p>
                <p><strong>Ubicación:</strong> {{ $usoInsumo->ubicacion }}</p>
                <p><strong>Fecha de Uso:</strong> {{ $usoInsumo->fecha_uso }}</p>
                <p><strong>Descripción:</strong> {{ $usoInsumo->descripcion ?? 'N/A' }}</p>
                <p class="text-danger">Esta acción no se puede deshacer.</p>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash-alt"></i> Eliminar
                </button>
                <a href="{{ route('uso_insumos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@stop
