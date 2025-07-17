@extends('adminlte::page')

@section('title', 'Agregar Nuevo Gasto')

@section('content_header')
    <h1>Agregar Nuevo Gasto</h1>
    <a class="btn btn-secondary my-2 btn-sm" href="{{ route('gastos.index') }}">
        <i class="fas fa-arrow-left"></i> Volver al Listado
    </a>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Registrar un nuevo gasto</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('gastos.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="tipo">Tipo</label>
                                    <input type="text" class="form-control @error('tipo') is-invalid @enderror"
                                        id="tipo" name="tipo" value="{{ old('tipo') }}"
                                        oninput="this.value = this.value.toUpperCase()">
                                    @error('tipo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="monto">Monto</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('monto') is-invalid @enderror" id="monto"
                                        name="monto" value="{{ old('monto') }}" min="0">
                                    @error('monto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fecha">Fecha</label>
                                    <input disabled readonly type="date" class="form-control @error('fecha') is-invalid @enderror"
                                        id="fecha" name="fecha"
                                        value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                                    @error('fecha')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion"
                                        rows="3" oninput="this.value = this.value.toUpperCase()">{{ old('descripcion') }}</textarea>
                                    @error('descripcion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Registrar
                            </button>
                            <a href="{{ route('gastos.index') }}" class="btn btn-warning text-light">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                        </div>
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
    <script>
        function validarNumero(event) {
            const input = event.target;
            const valor = input.value;

            if (valor === "") {
                return;
            }

            const valorNumerico = parseFloat(valor);
            if (isNaN(valorNumerico) || valorNumerico <= 0) {
                input.value = 1;
            }
        }

        document.getElementById('monto').addEventListener('input', validarNumero);
    </script>
@stop
