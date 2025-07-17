@extends('adminlte::page')

@section('title', 'Gestión de Insumos')

@section('content_header')
    <h1>Gestión de Insumos</h1>
    <a class="my-2 btn btn-primary btn-sm" href="{{ route('insumo.create') }}">
        <i class="fas fa-plus-circle"></i> Agregar Nuevo Insumo
    </a>
    <a class="my-2 btn btn-info btn-sm" href="{{ route('comprasinsumos.index') }}">
        <i class="fas fa-truck"></i> Comprar Insumos
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
                    <h3 class="card-title">Listado de Insumos</h3>
                </div>
                <div class="card-body">
                    <table id="tablaInsumos" class="table table-bordered table-striped dt-responsive nowrap"
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
    <script>
        var editUrlTemplate = "{{ route('insumo.edit', ['insumo' => '__id__']) }}";
        var deleteUrlTemplate = "{{ route('insumo.show', ['insumo' => '__id__']) }}";

        $(function() {
            $('#tablaInsumos').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
                ajax: "{{ route('datatable.getInsumos') }}",
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
                            let lotesUrl = "{{ route('lotes.insumos.index', ':codigo') }}"
                                .replace(':codigo', row
                                    .codigo);
                            let editUrl = "{{ route('lotes.insumos.edit', ':id') }}".replace(':id', row.id);
                            let deleteUrl = "{{ route('loteinsumos.show', ':id') }}".replace(':id', row
                                .id);
                            return `
                            <a href="${lotesUrl}" class="btn btn-info btn-sm">
                                    <i class="fas fa-boxes"></i> Lotes
                                </a>
                                <a href="${editUrl}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="${deleteUrl}" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            `;
                        }
                    }
                ]
            });
        });
    </script>
@stop
