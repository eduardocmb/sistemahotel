@extends('adminlte::page')

@section('title', 'Eliminar Empleado')

@section('content_header')
    <h1>Eliminar Empleado</h1>
    <a class="btn btn-secondary my-2 btn-sm" href="{{ route('empleados.index') }}">
        <i class="fas fa-arrow-left"></i> Volver al Listado
    </a>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('empleados.destroy', $empleado->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="alert alert-danger">
                    <strong>Advertencia:</strong> ¿Está seguro de que desea eliminar al siguiente empleado? Esta acción es irreversible.
                </div>

                <div class="row">
                    <div class="col-md-6 my-1">
                        <strong>Nombre Completo:</strong> {{ $empleado->nombrecompleto }}
                    </div>
                    <div class="col-md-6 my-1">
                        <strong>N° Identificación:</strong> {{ $empleado->dni }}
                    </div>
                    <div class="col-md-6 my-1">
                        <strong>Teléfono:</strong> {{ $empleado->telefono }}
                    </div>
                    <div class="col-md-6 my-1">
                        <strong>Fecha de Ingreso:</strong> {{ \Carbon\Carbon::parse($empleado->fechaing)->format('d/m/Y') }}
                    </div>
                    <div class="col-md-6 my-1">
                        <strong>Género:</strong> {{ $empleado->genero }}
                    </div>
                    <div class="col-md-6 my-1">
                        <strong>Departamento:</strong> {{ $departamento->departamento }}
                    </div>
                    <div class="col-md-6 my-1">
                        <strong>Tipo de Contratación:</strong> {{ $empleado->tipo }}
                    </div>
                    <div class="col-md-6 my-1">
                        <strong>Salario:</strong> ${{ number_format($empleado->salario, 2) }}
                    </div>
                    <div class="col-md-6 my-1">
                        <strong>Estado:</strong> {{ $empleado->estado }}
                    </div>
                </div>

                <div class="m-4">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </button>
                    <a href="{{ route('empleados.index') }}" class="btn text-light btn-warning">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
@stop
