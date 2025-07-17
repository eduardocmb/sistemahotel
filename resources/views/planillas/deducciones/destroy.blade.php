@extends('adminlte::page')

@section('title', 'Eliminar Deducción')

@section('content_header')
    <h1>Eliminar Deducción</h1>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Confirmar Eliminación de Deducción</div>
                <div class="card-body">
                    <p><strong>Nombre:</strong> {{ $deduccion->nombre }}</p>
                    <p><strong>Tipo:</strong> {{ $deduccion->tipo == 'PORCENTAJE' ? 'Porcentaje' : 'Monto Total' }}</p>
                    <p><strong>Monto:</strong> {{ number_format($deduccion->monto, 2) }}</p>
                    <p><strong>Estado:</strong> {{ $deduccion->activo == 'S' ? 'Activo' : 'Inactivo' }}</p>
                    <p><strong>Descripción:</strong> {{ $deduccion->descripcion }}</p>
                    <hr>
                    <p>¿Estás seguro de que deseas eliminar esta deducción? Esta acción no se puede deshacer.</p>

                    <form action="{{ route('deducciones.destroy', $deduccion->id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <div class="form-group">
                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Eliminar</button>
                            <a href="{{ route('deducciones.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
