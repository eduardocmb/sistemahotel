@extends('adminlte::page')

@section('title', 'Reservaciones por Fecha')

@section('content_header')
    <h1>Reservaciones por Fecha</h1>
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
                    <h3 class="card-title">Filtrar Reservaciones por Fecha</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="input-group input-group-sm">
                            <label for="fecha1">Fecha Inicio </label>
                            <input type="date" class="form-control" id="fecha1">
                            <label for="fecha2">Fecha Final </label>
                            <input type="date" class="form-control" id="fecha2">
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
        function validateDates() {
            const startDate = new Date(document.getElementById("fecha1").value)
            const endDate = new Date(document.getElementById("fecha2").value)

            if (endDate < startDate) {
                document.getElementById("dateError").innerHTML =
                    `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>La fecha final no puede ser anterior a la fecha inicial.</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>`;
                document.getElementById("btnFiltrar").disabled = true
            } else {
                document.getElementById("dateError").innerHTML = ""
                document.getElementById("btnFiltrar").disabled = false
            }
        }

        document.getElementById("fecha1").addEventListener("change", validateDates)
        document.getElementById("fecha2").addEventListener("change", validateDates)

        document.getElementById('btnImprimir').addEventListener('click', function() {
            const startDateInput = document.getElementById("fecha1");
            const endDateInput = document.getElementById("fecha2");
            const filterButton = document.getElementById("btnFiltrar");
            const errorDiv = document.getElementById("dateError");
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            if (endDate < startDate) {

                errorDiv.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>La fecha final no puede ser anterior a la fecha inicial.</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>`;
                filterButton.disabled = true
            } else {
                errorDiv.innerHTML = ""
                filterButton.disabled = false
            }

            var enlace = document.createElement('a');
            enlace.href = `/rpt/imprimir-reservaciones-por-fechas/${startDate.toISOString().split("T")[0]}/${endDate.toISOString().split("T")[0]}`

            enlace.target = '_blank';
            document.body.appendChild(enlace);
            enlace.click();
            document.body.removeChild(enlace);
        });
        document.getElementById('btnFiltrar').addEventListener('click', function() {
            const startDateInput = document.getElementById("fecha1");
            const endDateInput = document.getElementById("fecha2");
            const filterButton = document.getElementById("btnFiltrar");
            const errorDiv = document.getElementById("dateError");
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            if (endDate < startDate) {
                errorDiv.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>La fecha final no puede ser anterior a la fecha inicial.</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>`;
                filterButton.disabled = true
            } else {
                errorDiv.innerHTML = ""
                filterButton.disabled = false
            }

            const tableId = '#tablaReservacionesXCliente';
            if ($.fn.DataTable.isDataTable(tableId)) {
                $(tableId).DataTable().ajax.url(`/datatables/reservaciones-por-fecha/${startDate.toISOString().split("T")[0]}/${endDate.toISOString().split("T")[0]}`)
                .load();
            } else {
                $(tableId).DataTable({
                    responsive: true,
                    autoWidth: false,
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                    },
                    ajax: {
                        url: `/datatables/reservaciones-por-fecha/${startDate.toISOString().split("T")[0]}/${endDate.toISOString().split("T")[0]}`
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
