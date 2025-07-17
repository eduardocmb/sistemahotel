@extends('adminlte::page')

@section('title', 'Eliminar Reservación')

@section('content_header')
    <h1>Eliminar Reservación</h1>
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div class="container form-group">
                    <small class="rounded p-2 text-danger bg-danger">Confirmación de Eliminación</small>
                </div>
            </div>
        </div>

        <div class="card-body">
            <p>¿Está seguro de que desea eliminar la siguiente reservación?</p>
            <ul>
                <li><strong>Nombre Completo:</strong> {{ $cliente->nombre_completo }}</li>
                <li><strong>Habitacion:</strong> {{ $hab->numero_habitacion }}</li>
                <li><strong>Fecha de entrada y salida:</strong> {{ $reservacion->fecha_entrada }} | {{ $reservacion->salida }}</li>
            </ul>
            <p>Esta acción eliminará permanentemente la reservación de la base de datos.</p>
        </div>

        <div class="m-4">
            <form action="{{route('reservaciones.destroy', $reservacion->id)}}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
                <a href="{{ route('usuarios.index') }}" class="btn text-light btn-warning">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
