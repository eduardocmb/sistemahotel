@extends('adminlte::page')

@section('title', 'Gestión de Facturas')

@section('content_header')
    <h1>Gestión de Facturas</h1>
    <a class="btn btn-secondary my-2 btn-sm" href="{{ route('facturaciones.index') }}">
        <i class="fas fa-arrow-left"></i> Volver a Facturacion
    </a>
    <a class="btn btn-success my-2 btn-sm" href="{{ route('detafacturacionesin.create') }}">
        <i class="fas fa-file-invoice"></i> Ver Facturacion de Reservaciones
    </a>
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

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Listado de Facturas</h3>
                </div>
                <div class="card-body">

                    <div class="container">
                        <!-- Navegación de pestañas -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="facturas-tab" data-toggle="tab" href="#facturas"
                                    role="tab" aria-controls="facturas" aria-selected="true">Periodo de Facturación
                                    Actual</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="otra-tab" data-toggle="tab" href="#otra" role="tab"
                                    aria-controls="otra" aria-selected="false">Historial de Facturación</a>
                            </li>
                        </ul>

                        <!-- Contenido de las pestañas -->
                        <div class="tab-content" id="myTabContent">
                            <!-- Tabla de Facturas -->
                            <div class="tab-pane fade show active mt-2" id="facturas" role="tabpanel"
                                aria-labelledby="facturas-tab">
                                <table id="tablaFacturas" class="table table-bordered table-striped dt-responsive nowrap"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>N° FACT</th>
                                            <th>FECHA</th>
                                            <th>CLIENTE</th>
                                            <th>RTN</th>
                                            <th>TOTAL</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                            <!-- Otra Tabla -->
                            <div class="tab-pane fade" id="otra" role="tabpanel" aria-labelledby="otra-tab">
                                <table id="tablaFacturaInt" class="table table-bordered table-striped dt-responsive nowrap"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>REG</th>
                                            <th>N° FACT</th>
                                            <th>FECHA</th>
                                            <th>CLIENTE</th>
                                            <th>RTN</th>
                                            <th>TOTAL</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
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
        $(function() {
            $('#tablaFacturas').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
                ajax: "{{ route('datatable.getFacturasSar') }}",
                columns: [{
                        data: "factnum"
                    },
                    {
                        data: "fecha",
                    },
                    {
                        data: "cliente",
                        render: function(data, type, row) {
                            return data.length > 20 ? data.substring(0, 20) + "..." : data;
                        }
                    },
                    {
                        data: "rtn"
                    },
                    {
                        data: "total"
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let editUrl = "{{ route('facturaciones.edit', ':id') }}".replace(':id',
                                row
                                .factnum);
                            return `
                                <a href="${editUrl}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Seleccionar
                                </a>
                            `;
                        }
                    }
                ]
            });
        });
    </script>
    <script>
        $(function() {
            $('#tablaFacturaInt').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
                ajax: "{{ route('datatable.getFacturasInterno') }}",
                columns: [{
                        data: "id"
                    },
                    {
                        data: "factnum",
                        render: function(data, type, row) {
                            return data.length > 20 ? data.substring(0, 20) + "..." : data;
                        }
                    },
                    {
                        data: "fecha"
                    },
                    {
                        data: "cliente",
                        render: function(data, type, row) {
                            return data.length > 20 ? data.substring(0, 20) + "..." : data;
                        }
                    },
                    {
                        data: "rtn"
                    },
                    {
                        data: "total"
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let editUrl = "{{ route('facturacionesin.edit', ':id') }}".replace(':id',
                                row
                                .id);
                            return `
                                <a href="${editUrl}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Seleccionar
                                </a>
                            `;
                        }
                    }
                ]
            });
        });
    </script>
@stop
