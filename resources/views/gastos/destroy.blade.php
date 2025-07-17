@extends('adminlte::page')

@section('title', 'Eliminar Gasto')

@section('content_header')
    <h1>Eliminar Gasto</h1>
    <a class="btn btn-secondary my-2 btn-sm" href="{{ route('gastos.index') }}">
        <i class="fas fa-arrow-left"></i> Volver al Listado
    </a>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Confirmar Eliminación</h3>
                </div>
                <div class="card-body">
                    <p>¿Está seguro de que desea eliminar el gasto con los siguientes detalles?</p>
                    <ul>
                        <li><strong>Tipo:</strong> {{ $gasto->tipo }}</li>
                        <li><strong>Monto:</strong> L. {{ number_format($gasto->monto, 2) }}</li>
                        <li><strong>Fecha:</strong> {{ $gasto->fecha }}</li>
                        <li><strong>Descripción:</strong> {{ $gasto->descripcion ?? 'N/A' }}</li>
                    </ul>

                    <form action="{{ route('gastos.destroy', $gasto->id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <div class="mt-4">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                            <a href="{{ route('gastos.index') }}" class="btn btn-warning">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
