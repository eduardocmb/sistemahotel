@extends('adminlte::page')

@section('title', 'Eliminar Producto')

@section('content_header')
    <h1>Eliminar Producto</h1>
    <a class="btn btn-secondary my-2 btn-sm" href="{{ route('productos.index') }}">
        <i class="fas fa-arrow-left"></i> Volver al Listado
    </a>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="text-danger">¿Estás seguro que deseas eliminar este Producto?</h5>
            <p><strong>Código:</strong> {{ $producto->codigo }}</p>
            <p><strong>Nombre:</strong> {{ $producto->nombre }}</p>
            <p><strong>Descripción:</strong> {{ $producto->descripcion }}</p>

            <form action="{{ route('productos.destroy', $producto->id) }}" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
                <a href="{{ route('productos.index') }}" class="btn btn-secondary">
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
    </script>
@stop
