@extends('adminlte::page')

@section('title', 'Gestión de Compras')

@section('content_header')
    <h1>Gestión de Compras</h1>
    <a class="my-2 btn btn-primary btn-sm" href="{{ route('comprasproductos.create') }}">
        <i class="fas fa-plus"></i> Registrar Nueva Compra
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
                    <h3 class="card-title">Listado de Compras</h3>
                </div>
                <div class="card-body">
                    <table id="tablaCompras" class="table table-bordered table-striped dt-responsive nowrap" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Código Compra</th>
                                <th>Proveedor</th>
                                <th>Usuario</th>
                                <th>Fecha de Compra</th>
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
            $('#tablaCompras').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
                ajax: "{{ route('datatable.getComprasProductos') }}",
                columns: [
                    { data: "id" },
                    { data: "codigocompra" },
                    { data: "proveedor" },
                    { data: "usuario" },
                    { data: "fecha_compra" },
                    { data: "total_compra" },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let editUrl = "{{ route('comprasproductos.edit', ':id') }}".replace(':id', row.id);
                            let deleteUrl = "{{ route('comprasproductos.show', ':id') }}".replace(':id', row.id);
                            return `
                                <a href="${editUrl}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Gestionar
                                </a>
                            `;
                        }
                    }
                ]
            });
        });
    </script>
@stop
