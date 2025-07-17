@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
@stop

@section('content')

    @if (session('nopermiso'))
        <div class="alert alert-danger" role="alert">
            {{ session('nopermiso') }}
        </div>
    @endif

    <div class="modal fade" id="initialNotificationModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Notificaciones importantes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="notificationList">

                </div>
                <div class="modal-footer">
                    <a href="{{route('notificaciones.index')}}" class="btn btn-success"><i class="fas fa-bell-slash"></i> Gestionar</a>
                    <button type="button" class="btn btn-primary" id="markAsRead">
                        <i class="fas fa-check"></i> Marcar como leído
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>

                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row text-dark row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
            <!-- Card 1 -->
            <div class="col">
                <div class="card" style="height: 250px;">
                    <a href="{{ route('reservaciones.index') }}" class="text-dark">
                        <div class="d-flex justify-content-center align-items-center"
                            style="width: 90px; height: 90px; overflow: hidden; margin: 0 auto;">
                            <img src="{{ asset('imgs/reservacion-128px.png') }}" class="card-img-top"
                                alt="Administrar reservaciones" style="object-fit: cover; width: 100%; height: 100%;">
                        </div>
                        <div class="card-body">
                            <h3 class="card-title fw-bold font-weight-bolder">Gestión de Reservaciones</h3>
                            <p class="card-text">Realiza una nueva reservación o consulta las ya existentes.</p>
                        </div>
                    </a>
                </div>
            </div>
            <!--Card 2-->
            <div class="col">
                <div class="card" style="height: 250px;">
                    <a href="{{ route('huespedes.index') }}" class="text-dark">
                        <div class="d-flex justify-content-center align-items-center"
                            style="width: 90px; height: 90px; overflow: hidden; margin: 0 auto;">
                            <img src="{{ asset('imgs/huespedes-128px.png') }}" class="card-img-top" alt="Huéspedes"
                                style="object-fit: cover; width: 100%; height: 100%;">
                        </div>
                        <div class="card-body">
                            <h3 class="card-title fw-bold font-weight-bolder">Huéspedes</h3>
                            <p class="card-text">Registrar, eliminar, modificar o ver mis huéspedes.</p>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="col">
                <div class="card" style="height: 250px;">
                    <a href="{{ route('habitaciones.index') }}" class="text-dark">
                        <div class="d-flex justify-content-center align-items-center"
                            style="width: 90px; height: 90px; overflow: hidden; margin: 0 auto;">
                            <img src="{{ asset('imgs/habitacion-128px.png') }}" class="card-img-top mt-2"
                                alt="Realizar una venta" style="object-fit: cover; width: 100%; height: 100%;">
                        </div>
                        <div class="card-body">
                            <h3 class="card-title fw-bold font-weight-bolder">Habitaciones</h3>
                            <p class="card-text">Consulta el estado de las habitaciones.</p>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Card 4 -->
            <div class="col">
                <div class="card" style="height: 250px;">
                    <a href="{{ route('productos.index') }}" class="text-dark">
                        <div class="d-flex justify-content-center align-items-center"
                            style="width: 90px; height: 90px; overflow: hidden; margin: 0 auto;">
                            <img src="{{ asset('imgs/inventario-128px.png') }}" class="card-img-top"
                                alt="Checar el inventario" style="object-fit: cover; width: 100%; height: 100%;">
                        </div>
                        <div class="card-body">
                            <h3 class="card-title fw-bold font-weight-bolder">Checar el inventario</h3>
                            <p class="card-text">Registrar, eliminar, modificar, comprar o ver Productos.</p>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Card 5 -->
            <div class="col">
                <div class="card" style="height: 250px;">
                    <a href="{{ route('aperturas_cajas.index') }}" class="text-dark">
                        <div class="d-flex justify-content-center align-items-center"
                            style="width: 90px; height: 90px; overflow: hidden; margin: 0 auto;">
                            <img src="{{ asset('imgs/caja-128px.png') }}" class="card-img-top mt-2"
                                alt="Realizar una venta" style="object-fit: cover; width: 100%; height: 100%;">
                        </div>
                        <div class="card-body">
                            <h3 class="card-title fw-bold font-weight-bolder">Caja</h3>
                            <p class="card-text">Aperturar o Cerrar caja.</p>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Card 6 -->
            <div class="col">
                <div class="card" style="height: 250px;">
                    <a href="{{route('movimientos.index')}}" class="text-dark">
                        <div class="d-flex justify-content-center align-items-center"
                            style="width: 90px; height: 90px; overflow: hidden; margin: 0 auto;">
                            <img src="{{ asset('imgs/movimientos-128px.png') }}" class="card-img-top"
                                alt="Realizar una venta" style="object-fit: cover; width: 100%; height: 100%;">
                        </div>
                        <div class="card-body">
                            <h3 class="card-title fw-bold font-weight-bolder">Movimientos</h3>
                            <p class="card-text">Resumen de todos los movimientos en el hotel.</p>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Card 7 -->
            <div class="col">
                <div class="card" style="height: 250px;">
                    <a href="{{route('empleados.index')}}" class="text-dark">
                        <div class="d-flex justify-content-center align-items-center"
                            style="width: 90px; height: 90px; overflow: hidden; margin: 0 auto;">
                            <img src="{{ asset('imgs/personal-128px.png') }}" class="card-img-top"
                                alt="Control de Personal" style="object-fit: cover; width: 100%; height: 100%;">
                        </div>
                        <div class="card-body">
                            <h3 class="card-title fw-bold font-weight-bolder">Control de Personal</h3>
                            <p class="card-text">Crear, modificar o ver empleados.</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Card 8 -->
            <div class="col">
                <div class="card" style="height: 250px;">
                    <a href="{{ route('usuarios.index') }}" class="text-dark">
                        <div class="d-flex justify-content-center align-items-center"
                            style="width: 90px; height: 90px; overflow: hidden; margin: 0 auto;">
                            <img src="{{ asset('imgs/seguridad-128px.png') }}" class="card-img-top"
                                alt="Realizar una venta" style="object-fit: cover; width: 100%; height: 100%;">
                        </div>
                        <div class="card-body">
                            <h3 class="card-title fw-bold font-weight-bolder">Usuarios</h3>
                            <p class="card-text">Registrar, eliminar, modificar o ver usuarios del sistema.</p>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Card 9 -->
            <div class="col">
                <div class="card" style="height: 250px;">
                    <a href="{{route('configuracion.create')}}" class="text-dark">
                        <div class="d-flex justify-content-center align-items-center"
                            style="width: 90px; height: 90px; overflow: hidden; margin: 0 auto;">
                            <img src="{{ asset('imgs/configuracion-128px.png') }}" class="card-img-top"
                                alt="Realizar una venta" style="object-fit: cover; width: 100%; height: 100%;">
                        </div>
                        <div class="card-body">
                            <h3 class="card-title fw-bold font-weight-bolder">Ajustes</h3>
                            <p class="card-text">Cambiar datos del hotel, configurar la impresora, etcétera.</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <a href="{{route('facturacionesin.index')}}" style="text-decoration: none;" class="text-light">::::</a>
                        <a href="{{route('reservaciones.indexin')}}" style="text-decoration: none;" class="text-light">::R::</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@section('js')
<script>
    const urlprod = '/traer-productos-poca-existencia';
    function fetchNotifications() {
        fetch(urlprod, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                }
            })
            .then(data => {
            })
            .catch(error => {
                console.error('Error en la solicitud:', error);
            });
    }

    setInterval(fetchNotifications, 300000);

    fetchNotifications();
</script>
    <script>
        function actualizarNotificaciones() {
            $.get('/get/notificacionhabitacion', function(data) {
                const badge = document.querySelector('#my-notification .badge');
                badge.textContent = data.total_notificaciones;

                if (data.total_notificaciones > 0) {
                    badge.classList.add('badge-danger');
                } else {
                    badge.classList.remove('badge-danger');
                }
            });
        }

        function mostrarNotificaciones() {
            $.get('/get/notificacionhabitacion', function(data) {
                if (data.notificaciones.length > 0) {
                    const notificationList = data.notificaciones.map(n => {
                        return `

                                    <div class="card mb-2">
                                        <div class="card-header">
                                            <h5 class="card-title">${n.title}</h5>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text">${n.description}</p>
                                        </div>
                                    </div>

                        `;
                    }).join('');
                    $('#notificationList').html(notificationList);
                } else {
                    $('#notificationList').html('<p>No tienes nuevas notificaciones.</p>');
                }

                $('#initialNotificationModal').modal('show');
            });
        }

        $(document).on('click', '#my-notification', function() {
            mostrarNotificaciones();
        });

        $(document).on('click', '#markAsRead', function() {
            $.get('/notificacionhabitacionmarkasread', {}, function() {
                $('#initialNotificationModal').modal('hide');
                actualizarNotificaciones();
            }).fail(function() {
                console.error('Hubo un error al marcar como leído.');
            });
        });

        setInterval(actualizarNotificaciones, 300000);

        $(document).ready(function() {
            actualizarNotificaciones();
        });
        mostrarNotificaciones();
    </script>


    <script>
        const _url = '/notificacionhabitacion';

        function fetchNotifications() {
            fetch(_url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        console.error('Error al obtener las notificaciones:', response.status);
                    }
                })
                .then(data => {
                })
                .catch(error => {
                });
        }

        setInterval(fetchNotifications, 300000);

        fetchNotifications();
    </script>

@stop
