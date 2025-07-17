@extends('adminlte::page')

@section('title', 'Eliminar Rol')

@section('content_header')
    <h1>Eliminar Rol</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3>¿Estás seguro de que deseas eliminar este rol?</h3>
        </div>
        <div class="card-body">
            <p><strong>Código del Rol:</strong> {{ $rol->codigo }}</p>
            <p><strong>Nombre del Rol:</strong> {{ $rol->rol }}</p>
        </div>
        <div class="card-footer">
            <form action="{{ route('roles.destroy', $rol->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
                <a href="{{ route('roles.index') }}" class="btn btn-warning text-light">
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
@stop
