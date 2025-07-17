@extends('adminlte::page')

@section('title', 'Editar CAI')

@section('content_header')
    <h1>Editar CAI</h1>
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
        <form action="{{ route('cais.update', $cai->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-header">
                <h5 class="font-weight-bold">Datos del CAI</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="cai">CAI</label>
                        <input readonly type="text" class="form-control" id="cai" name="cai" maxlength="50"
                               value="{{ old('cai', $cai->cai) }}" required>
                        @error('cai')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="prefijo">Prefijo</label>
                        <input readonly type="text" class="form-control" id="prefijo" name="prefijo" maxlength="50"
                               value="{{ old('prefijo', $cai->prefijo) }}" required>
                        @error('prefijo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="numeroinicial">Número Inicial</label>
                        <input readonly type="text" class="form-control" id="numeroinicial" name="numeroinicial" maxlength="20"
                               value="{{ old('numeroinicial', $cai->numeroinicial) }}" required>
                        @error('numeroinicial')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="facturainicial">Factura Inicial</label>
                        <input readonly type="text" class="form-control" id="facturainicial" readonly name="facturainicial" maxlength="50"
                               value="{{ old('facturainicial', $cai->facturainicial) }}" required>
                        @error('facturainicial')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="numerofinal">Número Final</label>
                        <input readonly type="text" class="form-control" id="numerofinal" name="numerofinal" maxlength="20"
                               value="{{ old('numerofinal', $cai->numerofinal) }}" required>
                        @error('numerofinal')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="facturafinal">Factura Final</label>
                        <input readonly type="text" class="form-control" id="facturafinal" readonly name="facturafinal" maxlength="50"
                               value="{{ old('facturafinal', $cai->facturafinal) }}" required>
                        @error('facturafinal')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="fecharecibido">Fecha Recibido</label>
                        <input readonly type="date" class="form-control" id="fecharecibido" name="fecharecibido"
                               value="{{ old('fecharecibido', $cai->fecharecibido) }}" required>
                        @error('fecharecibido')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="fechalimite">Fecha Límite</label>
                        <input readonly type="date" class="form-control" id="fechalimite" name="fechalimite"
                               value="{{ old('fechalimite', $cai->fechalimite) }}" required>
                        @error('fechalimite')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="estado">Estado</label>
                        <select class="form-control" id="estado" name="estado" required>
                            <option value="ACTIVO" {{ old('estado', $cai->estado) == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                            <option value="CANCELADO" {{ old('estado', $cai->estado) == 'CANCELADO' ? 'selected' : '' }}>CANCELADO</option>
                            <option value="VENCIDO" {{ old('estado', $cai->estado) == 'VENCIDO' ? 'selected' : '' }}>VENCIDO</option>
                        </select>
                        @error('estado')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="{{ route('cais.index') }}" class="btn text-light btn-warning">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </div>
        </form>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar este CAI? Esta acción no se puede deshacer.
                    <p class="mt-2"><strong>CAI:</strong> {{ $cai->cai }}</p>
                    <p><strong>Prefijo:</strong> {{ $cai->prefijo }}</p>
                    <p><strong>Estado:</strong> {{ $cai->estado }}</p>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('cais.destroy', $cai->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
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
