@extends('adminlte::auth.auth-page')

@section('auth_header', "Cambiar Contraseña")

@section('auth_body')
<div class="container">
    <h2>Cambiar Contraseña</h2>
    @if(session('password-change'))
        <div class="alert alert-warning">
            {{ session('password-change') }}
        </div>
    @endif
    @if(session('error'))
    <div class="alert alert-warning">
        {{ session('error') }}
    </div>
@endif

    <form method="POST" action="{{ route('password.change.update', $user->id) }}">
        @csrf
        @method('GET')
        <div class="form-group">
            <label for="current_password">
                <i class="fas fa-lock"></i> Contraseña Actual
            </label>
            <input type="password" id="current_password" name="current_password" class="form-control" >
            @error('current_password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">
                <i class="fas fa-key"></i> Nueva Contraseña
            </label>
            <input type="password" id="password" name="password" class="form-control" >
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">
                <i class="fas fa-key"></i> Confirmar Nueva Contraseña
            </label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" >
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Cambiar Contraseña
        </button>
    </form>
</div>
@stop

@section('auth_footer')

@stop
