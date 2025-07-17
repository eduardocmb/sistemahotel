@extends('adminlte::page')

@section('title', 'Eliminar Habitación')

@section('content_header')
    <h1>Eliminar Habitación</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Confirmar eliminación de la habitación - <span class="font-weight-bold text-danger">N° {{$habitacion->numero_habitacion}}</span></h3>
                </div>
                <div class="card-body">
                    <p class="text-danger">¿Estás seguro de que deseas eliminar esta habitación? Esta acción no se puede deshacer.</p>

                    <div class="mb-4">
                        <p><strong>Número de Habitación:</strong> {{$habitacion->numero_habitacion}}</p>
                        <p><strong>Tipo de Habitación:</strong> {{$habitacion->tipo_habitacion}}</p>
                        <p><strong>Descripción:</strong> {{$habitacion->descripcion}}</p>
                        <p><strong>Precio Diario:</strong> {{$habitacion->precio_diario}} Lps</p>
                        <p><strong>Estado:</strong> {{$habitacion->estado}}</p>
                        <p><strong>Capacidad:</strong> {{$habitacion->capacidad}} personas</p>
                    </div>

                    <form action="{{ route('habitaciones.destroy', $habitacion->id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                        <a href="{{ route('habitaciones.index') }}" class="btn text-light btn-warning">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
@stop

@section('js')
@stop
