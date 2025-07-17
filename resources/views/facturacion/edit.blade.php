@extends('adminlte::page')

@section('title', 'Factura N°: ' . $factura[0]->factnum)

@section('content_header')
    <h1>Facturacion</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
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
                </div>
            </div>
            <div class="btn-group" role="group" aria-label="Acciones">
                <button disabled id="BtnNuevo" type="button" class="rounded btn btn-primary"><i class="fas fa-plus"></i>
                    Nuevo</button>
                <button disabled id="BtnGuardar" type="button" class="ml-2 rounded btn btn-primary"><i
                        class="fas fa-save"></i>
                    Guardar</button>
                <button id="BtnBuscar" type="button" class="rounded btn btn-info ml-2"><i class="fas fa-search"></i>
                    Buscar</button>
                <button id="BtnActualizar" type="button" class="rounded btn btn-success ml-2"><i class="fas fa-sync"></i>
                    Actualizar</button>
                <button id="BtnEliminar" type="button" class="rounded btn btn-danger ml-2"><i class="fas fa-trash"></i>
                    Eliminar</button>
                <button id="BtnImprimir" type="button" class="rounded btn btn-secondary ml-2"><i class="fas fa-print"></i>
                    Imprimir</button>
                <button id="BtnAnular" data-toggle="modal" data-target="#confirmAnularModal" type="button" class="rounded text-light btn btn-warning ml-2"><i
                        class="fas fa-ban"></i>
                    Anular</button>
                <a id="BtnSalir" href="{{ route('dashboard') }}" class="rounded btn btn-dark ml-2 disabled-link">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>

        </div>
        <div class="card-body">
            <form action="{{ route('facturaciones.store') }}" id="formId" method="POST">
                @csrf
                <div class="form-row">
                    <div class="col-md-12">
                        <div id="error" class="mt-2"></div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="cliente">Cliente</label>
                        <div class="input-group">
                            <input value="{{ $factura[0]->cliente }}" readonly type="text" class="form-control"
                                id="cliente" placeholder="Cliente">
                            <div class="input-group-append">
                                <button disabled id="BtnBuscarCliente" class="btn btn-outline-secondary" type="button"><i
                                        class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-2">
                        <label>&nbsp;</label>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="tipoPago">Tipo de Pago</label>
                        <select disabled id="tipoPago" name="tipopago" class="form-control">
                            <option value="">ELEGIR...</option>
                            <option {{ $factura[0]->pago === 'EFECTIVO' ? 'selected' : '' }} value="EFECTIVO">EFECTIVO
                            </option>
                            <option {{ $factura[0]->pago === 'TARJETA' ? 'selected' : '' }} value="TARJETA">TARJETA</option>
                            <option {{ $factura[0]->pago === 'TRANSFERENCIA' ? 'selected' : '' }} value="TRANSFERENCIA">
                                TRANSFERENCIA</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Número</label>
                        <input type="hidden" class="form-control" id="numero" name="numero">
                        <h3 class="text-center text-light"><label id="numerolabel" style="background-color: #20c997"
                                class="w-100 p-1 rounded">{{ $factura[0]->factnum }}</label></h3>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>RTN</label>
                        <input type="hidden" class="form-control" id="rtn" name="rtn">
                        <h3 class="text-center text-light">
                            <label id="rtnlabel" style="background-color: #20c997"
                                class="w-100 p-1 rounded">{{ $factura[0]->rtn }}</label>
                        </h3>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Código</label>
                        <input type="hidden" class="form-control" id="codigo" name="codigocliente">
                        <h3 class="text-center text-light">
                            <label id="codigolabel" style="background-color: #20c997"
                                class="w-100 p-1 rounded">{{ $factura[0]->codigo_cliente }}</label>
                        </h3>
                    </div>

                    <div class="form-group col-md-2">
                        <label>Fecha</label>
                        <input type="hidden" class="form-control" value="{{ $factura[0]->fecha }}" id="fecha"
                            name="fecha">
                        <h3 class="text-center text-light">
                            <label style="background-color: #20c997"
                                class="w-100 p-1 rounded">{{ $factura[0]->fecha }}</label>
                        </h3>
                    </div>
                    <div class="form-group col-md-2">
                        <label>Caja</label>
                        <input type="hidden" class="form-control" id="caja" name="caja">
                        <h3 class="text-center text-light">
                            <label id="cajalabel" style="background-color: #20c997"
                                class="w-100 p-1 rounded">{{ $factura[0]->numcaja }}</label>
                        </h3>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Turno</label>
                        <input type="hidden" class="form-control" id="turno" name="turno">
                        <h3 class="text-center text-light">
                            <label id="turnolabel" style="background-color: #20c997"
                                class="w-100 p-1 rounded">{{ $factura[0]->turno }}</label>
                        </h3>
                    </div>
                </div>

        </div>
    </div>

    <!-- Tabla de productos -->
    <div class="card mb-4">
        <div style=" background-color: #20c997" class="card-header text-white">PRODUCTOS AGREGADOS</div>
        <div class="card-body">
            <table class="table table-bordered" id="tabla_productos">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Descuento</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($factura as $item)
                        <tr>
                            <td>{{ $item->codproducto }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($item->nombre, 20, '...') }}</td>
                            <td>{{ $item->cant }}</td>
                            <td>{{ number_format($item->precio, 2) }}</td>
                            <td>{{ number_format($item->descto, 2) }}</td>
                            <td>{{ number_format($item->precio * $item->cant - $item->descto, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-row mt-3">
        <div class="form-group col-md-3">
            <label>Importe Exonerado</label>
            <input type="hidden" class="form-control" id="importeExonerado" name="importeExonerado">
            <h3 class="text-center text-light">
                <label id="LblImpExon" style="background-color: #20c997"
                    class="w-100 p-1 rounded">{{ number_format($factura[0]->impoexon, 2) }}</label>
            </h3>
        </div>
        <div class="form-group col-md-3">
            <label>Importe Exento</label>
            <input type="hidden" class="form-control" id="importeExento" name="importeExento">
            <h3 class="text-center text-light">
                <label id="LblImpExen" style="background-color: #20c997"
                    class="w-100 p-1 rounded">{{ number_format($factura[0]->impoexen, 2) }}</label>
            </h3>
        </div>
        <div class="form-group col-md-3">
            <label>Importe Grav 15%</label>
            <input type="hidden" class="form-control" id="importeGrav15" name="importeGrav15">
            <h3 class="text-center text-light">
                <label id="LblImpGrav15" style="background-color: #20c997"
                    class="w-100 p-1 rounded">{{ number_format($factura[0]->impograv15, 2) }}</label>
            </h3>
        </div>
        <div class="form-group col-md-3">
            <label>Importe Grav 18%</label>
            <input type="hidden" class="form-control" id="importeGrav18" name="importeGrav18">
            <h3 class="text-center text-light">
                <label id="LblImpGrav18" style="background-color: #20c997"
                    class="w-100 p-1 rounded">{{ number_format($factura[0]->impograv18, 2) }}</label>
            </h3>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-3">
            <label>ISV 15%</label>
            <input type="hidden" class="form-control" id="isv15" name="isv15">
            <h3 class="text-center text-light">
                <label id="LblIsv15" style="background-color: #20c997"
                    class="w-100 p-1 rounded">{{ number_format($factura[0]->isv15, 2) }}</label>
            </h3>
        </div>
        <div class="form-group col-md-3">
            <label>ISV 18%</label>
            <input type="hidden" class="form-control" id="isv18" name="isv18">
            <h3 class="text-center text-light">
                <label id="LblIsv18" style="background-color: #20c997"
                    class="w-100 p-1 rounded">{{ number_format($factura[0]->isv18, 2) }}</label>
            </h3>
        </div>
        <div class="form-group col-md-3">
            <label>Descuento</label>
            <input type="hidden" class="form-control" id="descuento" name="descuento">
            <h3 class="text-center text-light">
                <label id="LblDescto" style="background-color: #20c997"
                    class="w-100 p-1 rounded">{{ number_format($factura[0]->descto, 2) }}</label>
            </h3>
        </div>
        <div class="form-group col-md-3">
            <label>Total</label>
            <input type="hidden" class="form-control" id="total" name="total">
            <h3 class="text-center text-light">
                <label id="LblTotal" style="background-color: #20c997"
                    class="w-100 p-1 rounded">{{ number_format($factura[0]->totalfactura, 2) }}</label>
            </h3>
        </div>
    </div>
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Total de la compra: <strong id="totalCompra">0.00</strong> Lps</p>
                    <div class="row">
                        <div class="col-12">
                            <div id="errorConfirm"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="receivedAmount">Dinero recibido:</label>
                        <input type="hidden" name="montorecibido" id="montorecibido">
                        <input type="number" id="receivedAmount" class="form-control"
                            placeholder="Ingresa la cantidad recibida">
                    </div>
                    <input type="hidden" name="cambioadar" id="cambioadar">
                    <p>El cambio a devolver es: <strong id="changeAmount">0.00</strong> Lps</p>
                    <p>¿Estás seguro de que deseas guardar la compra?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="confirmSave" class="btn btn-primary">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
    </form>
    </div>
    </div>

    <div class="modal fade" id="modalReimprimirFactura" tabindex="-1" role="dialog"
        aria-labelledby="modalReimprimirFacturaLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalReimprimirFacturaLabel">
                        <i class="fas fa-print"></i> REIMPRIMIR FACTURA
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <label for="etiquetaImprimir" style="font-size: h2;">
                        <strong>ETIQUETA:</strong>
                    </label>
                    <input type="text" oninput="this.value = this.value.toUpperCase()" name="etiquetaImprimir"
                        id="etiquetaImprimir" class="form-control mt-3" placeholder="Ingrese etiqueta">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="button" id="guardarImprimir" class="btn btn-primary">
                        <i class="fas fa-check"></i> Aceptar
                    </button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        #BtnCancelar:hover {
            background-color: #7f00c9 !important;
        }

        .disabled-link {
            pointer-events: none;
            color: rgb(223, 223, 223);
        }
    </style>
@stop

@section('js')
    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('BtnImprimir').addEventListener('click', function() {
                $('#modalReimprimirFactura').modal('show');
            });
            document.getElementById('etiquetaImprimir').readOnly = false;
            document.getElementById('guardarImprimir').addEventListener('click', function() {
                var etiqueta = document.getElementById('etiquetaImprimir')
                    .value;
                if (etiqueta) {
                    var enlace = document.createElement('a');
                    enlace.href = '{{ route('facturas.rptfacturasar', $factura[0]->factnum) }}' +
                        '?etiqueta=' + encodeURIComponent(etiqueta);
                    document.body.appendChild(enlace);
                    enlace.click();
                    document.body.removeChild(enlace);
                } else {
                    alert('Por favor, ingrese una etiqueta antes de continuar.');
                }
            });
        });
    </script>
    <script>
        const btnAnular = document.getElementById('confirmAnularBtn');
const factnumInput = document.getElementById('factnumInput');

btnAnular.addEventListener('click', async function () {
    const factnum = btnAnular.getAttribute('data-factnum');
    const url = `/anular/factura/${factnum}/sar`;
    const form = document.getElementById('anularFacturaForm');
    factnumInput.value = factnum;
    form.action = url;

    $('#confirmAnularModal').modal('show');
});

$('#confirmAnularModal').on('hidden.bs.modal', function () {
    factnumInput.value = '';
});

    </script>
@stop
<div class="modal fade" id="confirmAnularModal" tabindex="-1" role="dialog"
    aria-labelledby="confirmAnularModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmAnularModalLabel">Confirmación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Desea anular esta factura?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form id="anularFacturaForm" method="GET">
                    <button type="submit" data-factnum="{{$factura[0]->factnum}}" class="btn btn-danger" id="confirmAnularBtn">Anular</button>
                    <input type="hidden" name="factnum" value="{{$factura[0]->factnum}}" id="factnumInput">
                </form>
            </div>
        </div>
    </div>
</div>

