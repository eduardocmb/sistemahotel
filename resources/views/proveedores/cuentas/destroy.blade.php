@extends('adminlte::page')

@section('title', 'Eliminar Cuenta por Pagar')

@section('content_header')
    <h1>Eliminar Cuenta por Pagar</h1>
@stop

@section('content')
    <div class="alert alert-warning">
        <strong>¡Advertencia!</strong> Estás a punto de eliminar esta cuenta por pagar. Esta acción no se puede deshacer.
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Detalles de la Cuenta por Pagar</div>

                    <div class="card-body">
                        <form action="{{ route('cuentas.destroy', $cuenta->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="codigo" class="form-label">Código</label>
                                    <input type="text" name="codigo" id="codigo" class="form-control"
                                        value="{{ $cuenta->codigo }}" readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="fecha" class="form-label">Fecha</label>
                                    <input type="date" name="fecha" id="fecha" class="form-control"
                                        value="{{ $cuenta->fecha }}" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="proveedor_id" class="form-label">Proveedor</label>
                                    <input type="text" class="form-control" value="{{ $proveedor->nombre }}" readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="numfactura" class="form-label">Número de Factura</label>
                                    <input type="text" name="numfactura" id="numfactura" class="form-control"
                                        value="{{ $cuenta->numfactura }}" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="monto_total" class="form-label">Monto Total</label>
                                    <input type="number" step="0.01" name="monto_total" id="monto_total"
                                        class="form-control" value="{{ $cuenta->monto_total }}" readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                                    <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="form-control"
                                        value="{{ $cuenta->fecha_vencimiento }}" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <input type="text" name="estado" id="estado" class="form-control"
                                        value="{{ $cuenta->estado }}" readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="notas" class="form-label">Notas</label>
                                    <textarea name="notas" id="notas" class="form-control" rows="3" readonly>{{ $cuenta->notas }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-danger">Eliminar Cuenta</button>
                                    <a href="{{ route('cuentas.index') }}" class="btn btn-secondary">Cancelar</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .form-control {
            border-radius: 0.25rem;
        }
    </style>
@stop
