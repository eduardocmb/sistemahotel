@extends('adminlte::page')

@section('content')
    <div class="container">
        <h2>¿Estás seguro de que deseas eliminar este lote?</h2>

        <div class="card">
            <div class="card-body">
                <p><strong>Id:</strong> {{ $lote->id }}</p>
                <p><strong>Fecha de Ingreso:</strong> {{ $lote->fecha }}</p>
                <p><strong>Cantidad:</strong> {{ $lote->cantidad }}</p> <!-- Aquí se agrega la cantidad -->

                <!-- Formulario para confirmar la eliminación -->
                <form action="{{ route('lotes.destroy', $lote->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                    <a href="{{ route('lotes.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
@endsection
