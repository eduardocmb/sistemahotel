@extends('adminlte::page')

@section('title', 'Gestión de Empleados')

@section('content_header')
    <h1>Gestión de Empleados</h1>
    <div class="row">
        <div class="col-3">
            <a class="my-2 btn btn-primary btn-sm" href="{{ route('empleados.create') }}">
                <i class="fas fa-user-plus"></i> Agregar Nuevo Empleado
            </a>
        </div>
        <div class="col-3">
            <a class="my-2 btn btn-success btn-sm" href="{{ route('departamentos.index') }}">
                <i class="fas fa-building"></i> Gestionar Departamentos
            </a>
        </div>
    </div>
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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Listado de Empleados</h3>
                </div>
                <div class="card-body">
                    <table id="tablaEmpleados" class="table table-bordered table-striped dt-responsive nowrap"
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th>DNI</th>
                                <th>Nombre Completo</th>
                                <th>Departamento</th>
                                <th>Salario</th>
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
            $('#tablaEmpleados').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json'
                },
                ajax: "{{ route('datatable.getEmpleados') }}",
                columns: [
                    {
                        data: 'dni'
                    },
                    {
                        data: 'nombrecompleto',
                        render: function(data, type, row) {
                            return data.length > 15 ? data.substring(0, 15) + "..." : data;
                        }
                    },
                    {
                        data: 'departamento'
                    },
                    {
                        data: 'salario',
                        render: function(data, type, row) {
                            return $.fn.dataTable.render.number(',', '.', 2).display(data) +
                            " Lps.";
                        }
                    },
                    {
                        data: 'estado'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let editUrl = "{{ route('empleados.edit', ':id') }}".replace(':id', row
                                .id);
                            let deleteUrl = "{{ route('empleados.show', ':id') }}".replace(':id', row
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
