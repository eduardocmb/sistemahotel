@extends('adminlte::page')

@section('title', 'Eliminar Huésped')

@section('content_header')
    <h1>Eliminar Huésped</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Confirmar eliminación del huésped - <span class="font-weight-bold text-danger">{{$huesped->nombre_completo}}</span></h3>
                </div>
                <div class="card-body">
                    <p class="text-danger">¿Estás seguro de que deseas eliminar este huésped? Esta acción no se puede deshacer.</p>

                    <div class="mb-4">
                        <p><strong>Código de Huésped:</strong> {{$huesped->codigo_cliente}}</p>
                        <p><strong>Nombre Completo:</strong> {{$huesped->nombre_completo}}</p>
                        <p><strong>Identificación:</strong> {{$huesped->identificacion}}</p>
                        <p><strong>RTN:</strong> {{$huesped->rtn}}</p>
                        <p><strong>Teléfono:</strong> {{$huesped->telefono}}</p>
                        <p><strong>Correo Electrónico:</strong> {{$huesped->email}}</p>
                    </div>

                    <form action="{{ route('huespedes.destroy', $huesped->id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                        <a href="{{ route('huespedes.index') }}" class="btn text-light btn-warning">
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
