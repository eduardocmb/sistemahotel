@extends('adminlte::page')

@section('title', 'Registrar Uso de Insumo')

@section('content_header')
    <h1>Registrar Uso de Insumo</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>¡Error!</strong> Por favor, corrige los errores en el formulario.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Datos del Uso de Insumo</h3>
        </div>
        <form id="form-uso-insumo" action="{{ route('uso_insumos.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="insumo_id">Insumo</label>
                    <select name="insumo_id" id="insumo_id" class="form-control @error('insumo_id') is-invalid @enderror">
                        <option value="">Seleccione un insumo</option>
                        @foreach ($insumos as $insumo)
                            <option value="{{ $insumo->id }}" {{ old('insumo_id') == $insumo->id ? 'selected' : '' }}>
                                {{ $insumo->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('insumo_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="cantidad_usada">Cantidad Usada</label>
                    <input type="number" name="cantidad_usada" id="cantidad_usada"
                        class="form-control @error('cantidad_usada') is-invalid @enderror"
                        value="{{ old('cantidad_usada') }}" min="1" placeholder="Ingrese la cantidad usada">
                    @error('cantidad_usada')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="ubicacion">Ubicación</label>
                    <input type="text" name="ubicacion" id="ubicacion"
                        class="form-control @error('ubicacion') is-invalid @enderror" value="{{ old('ubicacion') }}"
                        placeholder="Ejemplo: Habitación 101, Piscina">
                    @error('ubicacion')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="fecha_uso">Fecha de Uso</label>
                    <input type="date" name="fecha_uso" id="fecha_uso"
                        class="form-control @error('fecha_uso') is-invalid @enderror" value="{{ old('fecha_uso') }}">
                    @error('fecha_uso')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción (Opcional)</label>
                    <textarea name="descripcion" id="descripcion" rows="3"
                        class="form-control @error('descripcion') is-invalid @enderror" placeholder="Ingrese una breve descripción del uso">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="card-footer">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmModal">
                    <i class="fas fa-save"></i> Registrar
                </button>
                <a href="{{ route('uso_insumos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>

    <!-- Modal de Confirmación -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de que desea guardar este registro? Una vez guardado, no podrá modificar el insumo
                        usado, la cantidad ni la fecha.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirm-save">Guardar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        document.getElementById('confirm-save').addEventListener('click', function () {
            document.getElementById('form-uso-insumo').submit();
        });
    </script>
@stop
