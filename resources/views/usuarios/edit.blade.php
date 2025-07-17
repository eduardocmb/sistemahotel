@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1>Editar Usuario</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

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
        <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div class="container form-group">
                        <small class="rounded p-2 text-primary bg-primary">Datos Generales del Usuario</small>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="username">Nombre de Usuario</label>
                        <input type="text" readonly class="form-control @error('username') is-invalid @enderror" name="username" id="username" maxlength="20" required value="{{ old('username', $usuario->username) }}">
                        @error('username')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="name">Nombre Completo</label>
                        <input type="text" oninput="this.value = this.value.toUpperCase()" class="form-control @error('name') is-invalid @enderror" name="name" id="name" required value="{{ old('name', $usuario->name) }}">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" required value="{{ old('email', $usuario->email) }}">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="password">Contraseña</label>
                        <div class="input-group">
                            <input type="password" value="{{old('password')}}" class="form-control @error('password') is-invalid @enderror" name="password" id="password">
                            <div class="input-group-append">
                                <span class="input-group-text" id="togglePassword">
                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                </span>
                            </div>
                        </div>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="idrol">Rol</label>
                        <select class="form-control @error('idrol') is-invalid @enderror" name="idrol" id="idrol" required>
                            <option value="">Seleccione un rol</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->codigo }}" {{ old('idrol', $usuario->idrol) == $rol->codigo ? 'selected' : '' }}>
                                    {{ $rol->rol }}
                                </option>
                            @endforeach
                        </select>
                        @error('idrol')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <input type="checkbox"  name="cambiarClave" id="cambiarClave">
                        <label for="cambiarClave">Cambiar Contraseña en el próximo inicio de sesión</label>
                    </div>
                </div>
            </div>

            <div class="m-4">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Actualizar
                </button>
                <a href="{{ route('usuarios.index') }}" class="btn text-light btn-warning">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        .password-input {
            position: relative;
        }

        .password-input .input-group-text {
            cursor: pointer;
        }

        .password-input .eye-icon-closed {
            animation: closeEye 0.5s forwards;
        }

        @keyframes closeEye {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(180deg);
            }
        }
    </style>
@stop

@section('js')
<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function () {
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;

        eyeIcon.classList.toggle('fa-eye');
        eyeIcon.classList.toggle('fa-eye-slash');
    });
</script>
@stop
