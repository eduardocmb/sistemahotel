@extends('adminlte::page')

@section('title', 'Gestión de Lotes')

@section('content_header')
    <h1>Gestión de Lotes de <span class="font-weight-bold text-primary">{{ $producto->nombre }}</span></h1>
    <a class="my-2 btn btn-primary btn-sm" href="{{ route('lotes.productos.create', $producto->codigo)}}">
        <i class="fas fa-plus-circle"></i> Agregar Nuevo Lote
    </a>
    <a href="{{ route('productos.index') }}" class="ml-2 btn text-light btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
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
                    <h3 class="card-title">Listado de Lotes</h3>
                </div>
                <div class="card-body">
                    <table id="tablaLotes" class="table table-bordered table-striped dt-responsive nowrap"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Precio de Compra</th>
                                <th>Cantidad</th>
                                <th>Fecha de Vencimiento</th>
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
            $('#tablaLotes').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
                ajax: "/datatables/lotes/{{ $producto->codigo }}",
                columns: [{
                        data: "id"
                    },
                    {
                        data: "fecha"
                    },
                    {
                        data: "precio_compra"
                    },
                    {
                        data: "cantidad"
                    },
                    {
                        data: "fecha_vencimiento"
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let editUrl = "{{ route('lotes.productos.edit', ':id') }}".replace(':id', row.id);
                            let deleteUrl = "{{ route('lotes.show', ':id') }}".replace(':id', row
                                .id);
                            return `
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
