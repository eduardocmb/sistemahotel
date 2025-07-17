@extends('adminlte::page')

@section('title', 'Ingresos por Dia')

@section('content_header')
    <h1>Reporte de Ingresos por dia</h1>
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
            <div id="dateError"></div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filtrar por Dia</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="input-group input-group-sm">
                            <label for="fecha1">Fecha Inicio </label>
                            <input type="date" class="form-control" id="fecha1">
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

                    <table id="tablaIngresoDiario" class="table table-striped table-bordered dt-responsive nowrap"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Efectivo</th>
                                <th>Tarjeta</th>
                                <th>Transferencias</th>
                                <th>Total de Ventas</th>
                            </tr>
                        </thead>
                        <tbody>

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
        document.getElementById('btnImprimir').addEventListener('click', function() {
            const startDateInput = document.getElementById("fecha1");
            const startDate = new Date(startDateInput.value);
            var enlace = document.createElement('a');
            const baseUrl = `{{ route('rpt.imprimirIngresosxFecha', ['fecha' => ':fecha']) }}`;
            const finalUrl = baseUrl.replace(':fecha', startDate.toISOString().split("T")[0]);
            enlace.href = finalUrl;
            enlace.target = '_blank';
            document.body.appendChild(enlace);
            enlace.click();
            document.body.removeChild(enlace);
        });
        document.getElementById('btnFiltrar').addEventListener('click', function() {
            const startDateInput = document.getElementById("fecha1");
            const startDate = new Date(startDateInput.value);
            const baseUrl = `{{ route('datatables.getIngresosDiarios', ['fecha' => ':fecha']) }}`;
            const finalUrl = baseUrl.replace(':fecha', startDate.toISOString().split("T")[0]);

            const tableId = '#tablaIngresoDiario';

            if ($.fn.DataTable.isDataTable(tableId)) {
                $(tableId).DataTable().ajax.url(finalUrl)
                    .load();
            } else {
                $(tableId).DataTable({
                    responsive: true,
                    autoWidth: false,
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                    },
                    ajax: {
                        url: finalUrl
                    },
                    columns: [{
                            data: "ventasefe"
                        },
                        {
                            data: "ventaspos"
                        },
                        {
                            data: "transferencias"
                        },
                        {
                            data: "totventas"
                        },
                    ]
                });
            }
        })
    </script>
@stop
