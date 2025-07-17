@extends('adminlte::page')

@section('title', 'Crear Abono a Cuenta por Pagar')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Registrar Abono a Cuenta por Pagar</h3>
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
            </div>
            <div class="card-body">
                <form action="{{ route('pagoscuentas.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="cuentasporpagar_id">Cuenta por Pagar</label>
                        <input type="text" readonly value="{{ $cuenta->codigo . ' / ' . $proveedor->nombre }}"
                            class="form-control">
                        <input type="hidden" name="cuenta_id" value="{{ $cuenta->id }}">
                    </div>

                    <div class="form-group">
                        <label for="">Saldo Pendiente</label>
                        <input type="text" readonly value="{{ $saldo_pendiente }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="fecha">Fecha</label>
                        <input type="date" name="fecha" id="fecha"
                            class="form-control @error('fecha') is-invalid @enderror" value="{{ old('fecha') }}" required>
                        @error('fecha')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="monto_pagado">Monto Pagado</label>
                        <input type="number" step="0.01" name="monto_pagado" id="monto_pagado"
                            class="form-control @error('monto_pagado') is-invalid @enderror"
                            value="{{ old('monto_pagado') }}" required>
                        @error('monto_pagado')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tipo_pago">Tipo de Pago</label>
                        <select name="tipo_pago" id="tipo_pago"
                            class="form-control @error('tipo_pago') is-invalid @enderror" required>
                            <option value="EFECTIVO" {{ old('tipo_pago') == 'Efectivo' ? 'selected' : '' }}>Efectivo
                            </option>
                            <option value="TRANSFERENCIA" {{ old('tipo_pago') == 'Transferencia' ? 'selected' : '' }}>
                                Transferencia</option>
                            <option value="CHEQUE" {{ old('tipo_pago') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                        </select>
                        @error('tipo_pago')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="notas">Notas</label>
                        <textarea name="notas" id="notas" rows="3" class="form-control @error('notas') is-invalid @enderror">{{ old('notas') }}</textarea>
                        @error('notas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="m-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Registrar
                        </button>
                        <a href="{{ route('cuentas.index') }}" class="btn text-light btn-warning">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="button" id="verPagos" class="btn btn-info">
                            <i class="fas fa-eye"></i> Ver los pagos anteriores a esta cuenta
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-3 d-none" id="tablaPagos">
            <div class="card-header">
                <h4>Pagos Anteriores</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Tipo de Pago</th>
                            <th>Notas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pagos as $pago)
                            <tr>
                                <td>{{ $pago->fecha }}</td>
                                <td>{{ $pago->monto_pagado }}</td>
                                <td>{{ $pago->tipo_pago }}</td>
                                <td>{{ $pago->notas }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('verPagos').addEventListener('click', function () {
            document.getElementById('tablaPagos').classList.toggle('d-none');
        });
    </script>
@stop
