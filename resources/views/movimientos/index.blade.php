@extends('adminlte::page')

@section('title', 'Gestión de Movimientos')

@section('content_header')
    <h1>Gestión de Movimientos</h1>
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
                    <h3 class="card-title">Listado de Movimientos</h3>
                    <div class="card-tools">
                        <form method="GET" action="{{ route('movimientos.index') }}">
                            <div class="input-group input-group-sm">
                                <select id="producto" name="producto" class="form-control">
                                    <option value="">Todos los productos</option>
                                    @foreach ($productos as $producto)
                                        <option value="{{ $producto->codigo }}"
                                            {{ request('producto') == $producto->codigo ? 'selected' : '' }}>
                                            {{ $producto->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button type="button" id="btnfiltrar" class="btn btn-primary">
                                        <i class="fas fa-filter"></i> Filtrar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tablaMovimientos" class="table table-bordered table-striped dt-responsive nowrap"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Detalle</th>
                                <th>Tipo</th>
                                <th>Habían</th>
                                <th>Entran</th>
                                <th>Salen</th>
                                <th>Ahora</th>
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
        document.getElementById('btnfiltrar').addEventListener('click', function() {
            const producto = document.getElementById('producto').value;
            if (producto === "") {
                alert('Seleccione un producto');
                return false;
            }

            const tableId = '#tablaMovimientos';
            if ($.fn.DataTable.isDataTable(tableId)) {
                $(tableId).DataTable().ajax.url(`{{ route('datatable.getMovimientos', ':producto') }}`.replace(
                    ':producto', producto)).load();
            } else {
                $(tableId).DataTable({
                    responsive: true,
                    autoWidth: false,
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                    },
                    ajax: {
                        url: `{{ route('datatable.getMovimientos', ':producto') }}`.replace(':producto',
                            producto),
                    },
                    columns: [
                        {
                            data: "fecha"
                        },
                        {
                            data: "detalle"
                        },
                        {
                            data: "movimiento"
                        },
                        {
                            data: "habian"
                        },
                        {
                            data: "entrada"
                        },
                        {
                            data: "salida"
                        },
                        {
                            data: "ahora"
                        }
                    ]
                });
            }
        });
    </script>
@stop
