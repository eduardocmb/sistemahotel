@extends('adminlte::page')

@section('title', 'Crear CAI')

@section('content_header')
    <h1>Registrar Nuevo CAI</h1>
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
        <form action="{{ route('cais.store') }}" method="POST">
            @csrf
            <div class="card-header">
                <h5 class="font-weight-bold">Datos del CAI</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="cai">CAI</label>
                        <input type="text" class="form-control" id="cai" name="cai" maxlength="50"
                               value="{{ old('cai') }}" required>
                        @error('cai')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="prefijo">Prefijo</label>
                        <input type="text" class="form-control" id="prefijo" name="prefijo" maxlength="50"
                               value="{{ old('prefijo') }}" required>
                        @error('prefijo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="numeroinicial">Número Inicial</label>
                        <input type="text" class="form-control" id="numeroinicial" name="numeroinicial" maxlength="20"
                               value="{{ old('numeroinicial') }}" required>
                        @error('numeroinicial')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="facturainicial">Factura Inicial</label>
                        <input type="text" class="form-control" id="facturainicial" readonly name="facturainicial" maxlength="50"
                               value="{{ old('facturainicial') }}" required>
                        @error('facturainicial')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="numerofinal">Número Final</label>
                        <input type="text" class="form-control" id="numerofinal" name="numerofinal" maxlength="20"
                               value="{{ old('numerofinal') }}" required>
                        @error('numerofinal')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="facturafinal">Factura Final</label>
                        <input type="text" class="form-control" id="facturafinal" readonly name="facturafinal" maxlength="50"
                               value="{{ old('facturafinal') }}" required>
                        @error('facturafinal')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="fecharecibido">Fecha Recibido</label>
                        <input type="date" class="form-control" id="fecharecibido" name="fecharecibido"
                               value="{{ old('fecharecibido') }}" required>
                        @error('fecharecibido')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="fechalimite">Fecha Límite</label>
                        <input type="date" class="form-control" id="fechalimite" name="fechalimite"
                               value="{{ old('fechalimite') }}" required>
                        @error('fechalimite')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Registrar
                </button>
                <a href="{{ route('cais.index') }}" class="btn text-light btn-warning">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    function validarNumero(event) {
        const input = event.target;
        const valor = input.value;

        input.value = valor.replace(/[^0-9]/g, '');

        if (input.value === "") {
            return;
        }

        const valorNumerico = parseFloat(input.value);
        if (isNaN(valorNumerico) || valorNumerico < 0) {
            input.value = 1;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById("numeroinicial");
        const input2 = document.getElementById("numerofinal");
        input.addEventListener("input", validarNumero);
        input2.addEventListener("input", validarNumero);
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const prefijoInput = document.getElementById('prefijo');
        const numeroInicialInput = document.getElementById('numeroinicial');
        const numeroFinalInput = document.getElementById('numerofinal');
        const facturaInicialInput = document.getElementById('facturainicial');
        const facturaFinalInput = document.getElementById('facturafinal');

        prefijoInput.addEventListener('input', function () {
            const prefijo = prefijoInput.value.trim();
            facturaInicialInput.value = prefijo + facturaInicialInput.value.slice(prefijo.length);
            facturaFinalInput.value = prefijo + facturaFinalInput.value.slice(prefijo.length);
        });

        numeroInicialInput.addEventListener('input', function () {
            facturaInicialInput.value = prefijoInput.value.trim() + numeroInicialInput.value.trim();
        });

        numeroFinalInput.addEventListener('input', function () {
            facturaFinalInput.value = prefijoInput.value.trim() + numeroFinalInput.value.trim();
        });
    });
</script>
@stop
