@extends('adminlte::page')

@section('title', 'Gestión de Planillas')

@section('content_header')
    <h1>Gestión de Planillas</h1>
    <a class="my-2 btn btn-primary btn-sm" href="{{ route('planillas.create') }}">
        <i class="fas fa-plus"></i> Agregar Nueva Planilla
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
                    <h3 class="card-title">Listado de Planillas</h3>
                </div>
                <div class="card-body">
                    <table id="tablaPlanillas" class="table table-bordered table-striped dt-responsive nowrap"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Cliente</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Final</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
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
        $(function() {
            $('#tablaPlanillas').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
                ajax: "{{ route('datatable.getPlanillas') }}",
                columns: [
                    {
                        data: "codigo"
                    },
                    {
                        data: "nombrecompleto",
                        render: function(data, type, row) {
                            return data.length > 20 ? data.substring(0, 20) + "..." : data;
                        }
                    },
                    {
                        data: "fecha_inicio"
                    },
                    {
                        data: "fecha_final"
                    },
                    {
                        data: "total"
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let editUrl = "{{ route('rpt.imprimirPlanilla', ':id') }}".replace(':id', row
                                .codigo);
                            let deleteUrl = "{{ route('planillas.show', ':id') }}".replace(':id',
                                row.id);
                            return `
                                <a href="${editUrl}" target="_blank" class="btn btn-info btn-sm">
                                    <i class="fas fa-print"></i> Imprimir
                                </a>
                                <a href="${deleteUrl}" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </a>
                            `;
                        }
                    }
                ]
            });
        });
    </script>
@stop
