@extends('adminlte::page')

@section('title', 'Gestión de Productos')

@section('content_header')
    <h1>Gestión de Productos</h1>
    <a class="my-2 btn btn-primary btn-sm" href="{{ route('productos.create') }}">
        <i class="fas fa-plus-circle"></i> Agregar Nuevo Producto
    </a>
    <a class="my-2 btn btn-info btn-sm" href="{{ route('comprasproductos.index') }}">
        <i class="fas fa-truck"></i> Comprar Productos
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
                    <h3 class="card-title">Listado de Productos</h3>
                </div>
                <div class="card-body">
                    <table id="tablaProductos" class="table table-bordered table-striped dt-responsive nowrap"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Cantidad</th>
                                <th>Precio Venta</th>
                                <th>Categoría</th>
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
    @if (session('nuevoProducto'))
        <script>
            $('#modalAgregarLote').modal('show');
        </script>
    @endif
    <script>
        var editUrlTemplate = "{{ route('productos.edit', ['producto' => '__id__']) }}";
        var deleteUrlTemplate = "{{ route('productos.show', ['producto' => '__id__']) }}";

        $(function() {
            $('#tablaProductos').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
                ajax: "{{ route('datatable.getProductos') }}",
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
                        data: "precio_venta"
                    },
                    {
                        data: "categoria"
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let lotesUrl = "{{ route('lotes.productos.index', ':codigo') }}"
                                .replace(':codigo', row.codigo);
                            let editUrl = "{{ route('productos.edit', ':id') }}".replace(':id', row
                                .id);
                            let deleteUrl = "{{ route('productos.show', ':id') }}".replace(':id',
                                row.id);

                            let lotesButton = '';
                            if (row.tipo_producto === "PRODUCTO FINAL") {
                                lotesButton = `
                                    <a href="${lotesUrl}" class="btn btn-info btn-sm">
                                        <i class="fas fa-boxes"></i> Lotes
                                    </a>
                                `;
                            }

                            return `
                            ${lotesButton}
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


<div class="modal fade" id="modalAgregarLote" tabindex="-1" role="dialog" aria-labelledby="modalAgregarLoteLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarLoteLabel">Producto sin existencias</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                EL PRODUCTO <strong>{{ session('prod') }}</strong> TIENE <strong>0 UNIDADES</strong> EN INVENTARIO,
                ¿DESEA AGREGAR UN NUEVO LOTE?
            </div>
            <div class="modal-footer">
                <a href="{{ session('codprod') ? route('lotes.productos.index', session('codprod')) : '#' }}"
                    class="btn btn-primary">
                    <i class="fas fa-boxes"></i> Agregar nuevo lote
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-clock"></i> Tal vez luego
                </button>
            </div>
        </div>
    </div>
</div>
