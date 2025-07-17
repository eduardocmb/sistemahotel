@extends('adminlte::page')

@section('title', 'Reporte de Existencia de Productos')

@section('content_header')
    <h1>Reporte de Existencia de Productos</h1>
@stop

@section('content')
    <button id="btnImprimir" class="btn btn-primary mb-3">
        <i class="fas fa-print"></i> Imprimir
    </button>

    <table id="tablaExistenciaProductos" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Cantidad en Inventario</th>
                <th>Stock Minimo</th>
                <th>Precio Venta</th>
                <th>Categoría</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
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
        $(document).ready(function() {
            $('#tablaExistenciaProductos').DataTable({
                ajax: "{{ route('datatables.getExistenciaProductos') }}",
                columns: [{
                        data: "id"
                    },
                    {
                        data: "nombre",
                        render: function(data, type, row) {
                            return data.length > 20 ? data.substring(0, 20) + "..." : data;
                        }
                    },
                    {
                        data: "total_cantidad"
                    },
                    {
                        data:"stock_minimo"
                    },
                    {
                        data: "precio_venta"
                    },
                    {
                        data: "categoria"
                    },
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
            });

            $('#btnImprimir').click(function() {
                window.open('{{ route('rpt.imprimirExistenciaProductos') }}', '_blank');
            });
        });
    </script>
@stop
