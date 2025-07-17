@extends('adminlte::page')

@section('title', 'Eliminar Usuario')

@section('content_header')
    <h1>Eliminar Usuario</h1>
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
            <p>¿Está seguro de que desea eliminar al siguiente usuario?</p>
            <ul>
                <li><strong>Nombre Completo:</strong> {{ $usuario->name }}</li>
                <li><strong>Nombre de Usuario:</strong> {{ $usuario->username }}</li>
                <li><strong>Correo Electrónico:</strong> {{ $usuario->email }}</li>
            </ul>
            <p>Esta acción eliminará permanentemente al usuario de la base de datos.</p>
        </div>

        <div class="m-4">
            <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST">
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
