@extends('adminlte::page')

@section('title', 'Eliminar Insumo')

@section('content_header')
    <h1>Eliminar Insumo</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="text-danger">¿Estás seguro que deseas eliminar este insumo?</h5>
            <p><strong>Código:</strong> {{ $insumo->codigo }}</p>
            <p><strong>Nombre:</strong> {{ $insumo->nombre }}</p>
            <p><strong>Descripción:</strong> {{ $insumo->descripcion }}</p>

            <form action="{{ route('insumos.destroy', $insumo->id) }}" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
                <a href="{{ route('insumos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Confirmación de eliminación de insumo cargada.');
    </script>
@stop
