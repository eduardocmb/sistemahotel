@extends('adminlte::page')

@section('title', 'Reservaciones por Cliente')

@section('content_header')
    <h1>Reservaciones por Cliente</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('nopermiso'))
        <div class="alert alert-danger" role="alert">
            {{ session('nopermiso') }}
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filtrar Reservaciones por Cliente</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="input-group input-group-sm">
                            <select id="selectCliente" name="selectCliente" class="form-control">
                                <option value="">Todos los clientes</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}"
                                        {{ request('cliente') == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nombre_completo }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button type="button" id="btnFiltrar" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filtrar
                                </button>
                            </div>
                        </div>
                    </div>

                    <button id="btnImprimir" class="btn my-1 btn-sm btn-primary">
                        <i class="fas fa-print"></i> Imprimir
                    </button>

                    <table id="tablaReservacionesXCliente" class="table table-striped table-bordered dt-responsive nowrap"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Habitación</th>
                                <th>Fecha de Entrada</th>
                                <th>Fecha de Salida</th>
                                <th>Total</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí se cargarán los datos de las reservaciones -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>
    <script>
        document.getElementById('btnImprimir').addEventListener('click', function(){
            const cliente = document.getElementById('selectCliente').value;
            if (cliente === "") {
                alert('Seleccione un cliente');
                return false;
            }
            var enlace = document.createElement('a');
            enlace.href = `{{ route('rpt.imprimirReservacionesXCliente', '') }}` +`/`+ cliente;
            enlace.target = '_blank';
            document.body.appendChild(enlace);
            enlace.click();
            document.body.removeChild(enlace);
        });
        document.getElementById('btnFiltrar').addEventListener('click', function() {
            const cliente = document.getElementById('selectCliente').value;
            if (cliente === "") {
                alert('Seleccione un cliente');
                return false;
            }

            const tableId = '#tablaReservacionesXCliente';
            if ($.fn.DataTable.isDataTable(tableId)) {
                $(tableId).DataTable().ajax.url(`{{ route('datatable.getReservacionesXCliente', ':cliente') }}`
                    .replace(
                        ':cliente', cliente)).load();
            } else {
                $(tableId).DataTable({
                    responsive: true,
                    autoWidth: false,
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                    },
                    ajax: {
                        url: `{{ route('datatable.getReservacionesXCliente', ':cliente') }}`.replace(
                            ':cliente',
                            cliente),
                    },
                    columns: [{
                            data: "numero"
                        },
                        {
                            data: "habitacion"
                        },
                        {
                            data: "fecha_entrada"
                        },
                        {
                            data: "salida"
                        },
                        {
                            data: "total"
                        },
                        {
                            data: "estado",
                        }
                    ]
                });
            }
        })
    </script>
@stop
