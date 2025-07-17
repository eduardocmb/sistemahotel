@extends('adminlte::page')

@section('title', 'Gestión de Cuentas por Pagar')

@section('content_header')
    <h1>Gestión de Cuentas por Pagar</h1>
    <a class="my-2 btn btn-secondary btn-sm" href="{{ route('proveedores.index') }}">
        <i class="fas fa-arrow-left"></i> Volver a Proveedores
    </a>
    <a class="my-2 btn btn-success btn-sm" href="{{ route('cuentas.create') }}">
        <i class="fas fa-money-bill"></i> Crear Nueva Cuenta
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
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Listado de Cuentas por Pagar</h3>
                </div>
                <div class="card-body">
                    <table id="tablaCuentas" class="table table-bordered table-striped dt-responsive nowrap"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Proveedor</th>
                                <th>Monto Total</th>
                                <th>Fecha Venc.</th>
                                <th>Estado</th>
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
            $('#tablaCuentas').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
                ajax: "{{ route('datatable.getCuentas') }}",
                columns: [{
                        data: "codigo"
                    },
                    {
                        data: "proveedor"
                    },
                    {
                        data: "monto_total"
                    },
                    {
                        data: "fecha_vencimiento"
                    },
                    {
                        data: "estado"
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let editUrl = "{{ route('cuentas.edit', ':id') }}".replace(':id', row
                                .id);
                            let deleteUrl = "{{ route('cuentas.show', ':id') }}".replace(':id', row
                                .id);
                            let abonarUrl = "{{ route('cuentas.abonar', ':cta_number') }}".replace(
                                ':cta_number', row.codigo);
                            return `
                                <a href="${abonarUrl}" class="btn btn-info btn-sm">
                                    <i class="fas fa-piggy-bank"></i> Abonar
                                </a>
                                <a href="${editUrl}" class="btn btn-info btn-sm">
                                    <i class="fas fa-sync-alt"></i> Actualizar
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
