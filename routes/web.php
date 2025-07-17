<?php

use App\Http\Controllers\AperturasCajasController;
use App\Http\Controllers\CabeceracomprainsumosController;
use App\Http\Controllers\CabecerafacturaController;
use App\Http\Controllers\CabeceraplanillaController;
use App\Http\Controllers\CabfacturainternoController;
use App\Http\Controllers\CabfacturareservacioninternoController;
use App\Http\Controllers\CabfacturareservacionsarController;
use App\Http\Controllers\CaiController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\CierresCajaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CompraproductoController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\CorrelativoController;
use App\Http\Controllers\CuentasporpagarController;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\DeduccionController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\DetafacturareservacioninternoController;
use App\Http\Controllers\DetafacturareservacionsarController;
use App\Http\Controllers\DetallefacturaController;
use App\Http\Controllers\DetallefacturainternoController;
use App\Http\Controllers\DetalleplanillaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\GastoController;
use App\Http\Controllers\HabitacioneController;
use App\Http\Controllers\InfohotelController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\KardexController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\LoteinsumoController;
use App\Http\Controllers\NotificacionesHabitacionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PagoscuentaController;
use App\Http\Controllers\PapeleraController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PlanillaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ReservacionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SearchControllerVenta;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\UnidadinsumoController;
use App\Http\Controllers\UsoinsumoController;
use App\Http\Controllers\UsuarioController;
use App\Models\cabfacturareservacioninterno;
use App\Models\lote;
use App\Models\NotificacionesHabitacion;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
});

// Ruta de login protegida por el middleware 'guest'
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::get('/register', function () {
    return view('adminlte::auth.register');
})->name('register');

Route::get('password/change/{user_id}', [ChangePasswordController::class, 'showChangePasswordForm'])->name('password.change');
Route::get('password/update/{user_id}', [ChangePasswordController::class, 'update'])->name('password.change.update');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Rutas de notificaciones
    Route::resource('habitacionnotificacion', NotificacionesHabitacionController::class);

    // Rutas de recursos
    Route::resource('reservaciones', ReservacionController::class);
    Route::resource('huespedes', ClienteController::class);
    Route::resource('habitaciones', HabitacioneController::class);
    Route::resource('insumo', InsumoController::class);
    Route::resource('proveedores', ProveedorController::class);
    Route::resource('categorias', CategoriaController::class);
    Route::resource('unidades', UnidadinsumoController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('lotes', LoteController::class);
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('turnos', TurnoController::class);
    Route::resource('cajas', CajaController::class);
    Route::resource('aperturas_cajas', AperturasCajasController::class);
    Route::resource('cierre_cajas', CierresCajaController::class);
    Route::resource('cais', CaiController::class);
    Route::resource('comprasproductos', CompraproductoController::class);
    Route::resource('facturaciones', CabecerafacturaController::class);
    Route::resource('detafacturaciones', DetallefacturaController::class);
    Route::resource('configuracion', ConfiguracionController::class);
    Route::resource('infohotel', InfohotelController::class);
    Route::resource('producto', productoController::class);
    Route::resource('facturacionreservaciones', CabfacturareservacionsarController::class);
    Route::resource('detafacturacionesreservacionesar', DetafacturareservacionsarController::class);
    Route::resource('facturacionesin', CabfacturainternoController::class);
    Route::resource('detafacturacionesin', DetallefacturainternoController::class);
    Route::resource('facturacionreservacionesin', CabfacturareservacioninternoController::class);
    Route::resource('detafacturacionesreservacionesin', DetafacturareservacioninternoController::class);
    Route::resource('gastos', GastoController::class);
    Route::resource('empleados', EmpleadoController::class);
    Route::resource('departamentos', DepartamentoController::class);
    Route::resource('deducciones', DeduccionController::class);
    Route::resource('planillas', CabeceraplanillaController::class);
    Route::resource('planilla', DetalleplanillaController::class);
    Route::resource('notificaciones', NotificacionesHabitacionController::class);
    Route::resource('uso_insumos', UsoinsumoController::class);
    Route::resource('loteinsumos', LoteinsumoController::class);
    Route::resource('comprasinsumos', CabeceracomprainsumosController::class);
    Route::resource('movimientos', KardexController::class);
    Route::resource('cuentas', CuentasporpagarController::class);
    Route::resource('pagoscuentas', PagoscuentaController::class);
    Route::resource('papelera', PapeleraController::class);
    Route::resource('papeleras', PlanController::class);
    // Rutas para los datatables y mas
    Route::get('datatable/getmov/{codprod}', [DatatableController::class, 'getMovimientos'])->name('datatable.getMovimientos');
    Route::get('datatable/usoinsumos', [DatatableController::class, 'getusoInsumos'])->name('datatable.getUsoInsumos');
    Route::get('datatable/notificaciones', [DatatableController::class, 'getNotificaciones'])->name('datatable.getNotificaciones');
    Route::get('datatable/deducciones', [DatatableController::class, 'getDeducciones'])->name('datatable.getDeducciones');
    Route::get('datatable/departamentos', [DatatableController::class, 'getDepartamentos'])->name('datatable.getDepartamentos');
    Route::get('datatables/getempleados', [DatatableController::class, 'getEmpleados'])->name('datatable.getEmpleados');
    Route::get('datatables/getgastos', [DatatableController::class, 'getGastos'])->name('datatable.getGastos');
    Route::get('datatables/getproducto', [DatatableController::class, 'getproducto'])->name('datatable.getproducto');
    Route::get('datatables/getcomprasdeinsumos', [DatatableController::class, 'getComprasInsumos'])->name('datatable.getComprasInsumos');
    Route::get('datatables/getcomprasdeproductos', [DatatableController::class, 'getComprasProductos'])->name('datatable.getComprasProductos');
    Route::get('datatables/cais', [DatatableController::class, 'getCais'])->name('datatable.getCais');
    Route::get('datatables/cajasabiertas', [DatatableController::class, 'getCajasAbiertas'])->name('datatable.getCajasAbiertas');
    Route::get('datatables/cajas', [DatatableController::class, 'getCajas'])->name('datatable.getCajas');
    Route::get('datatables/turnos', [DatatableController::class, 'getTurnos'])->name('datatable.getTurnos');
    Route::get('datatables/roles', [DatatableController::class, 'getRoles'])->name('datatable.getRoles');
    Route::get('datatables/lotes/{cod_prod}', [DatatableController::class, 'getLotes'])->name('datatable.getLotes');
    Route::get('datatables/lotesinsumos/{cod_prod}', [DatatableController::class, 'getLotesInsumos'])->name('datatable.getLotesInsumos');
    Route::get('datatables/unidades', [DatatableController::class, 'getUnidades'])->name('datatable.getUnidades');
    Route::get('datatables/categorias', [DatatableController::class, 'getCategorias'])->name('datatable.getCategorias');
    Route::get('datatables/reservaciones', [DatatableController::class, 'getReservaciones'])->name('datatable.getReservaciones');
    Route::get('datatables/huespedes', [DatatableController::class, 'getHuespedes'])->name('datatable.getHuespedes');
    Route::get('datatables/proveedor', [DatatableController::class, 'getProveedores'])->name('datatable.getProveedores');
    Route::get('datatables/insumos', [DatatableController::class, 'getInsumos'])->name('datatable.getInsumos');
    Route::get('datatables/habitaciones', [DatatableController::class, 'getHabitaciones'])->name('datatable.getHabitaciones');
    Route::get('datatables/productos', [DatatableController::class, 'getProductos'])->name('datatable.getProductos');
    Route::get('datatables/getusers', [DatatableController::class, 'getUsuarios'])->name('datatable.getUsers');
    Route::get('datatables/getfacturassar', [DatatableController::class, 'getFacturasSar'])->name('datatable.getFacturasSar');
    Route::get('datatables/planillas', [DatatableController::class, 'getPlanillas'])->name('datatable.getPlanillas');
    Route::get('datatables/getfacturasinterno', [DatatableController::class, 'getFacturasInterno'])->name('datatable.getFacturasInterno');
    Route::get('datatables/getfacturasreservacionsar', [DatatableController::class, 'getFacturasReservacionSar'])->name('datatable.getFacturasReservacionSar');
    Route::get('datatables/getfacturasreservacioninterno', [DatatableController::class, 'getFacturasReservacionInterno'])->name('datatable.getFacturasReservacionInterno');
    Route::get('datatables/reservaciones-xcliente/{id}', [ReporteController::class, 'ReservacionesPorCliente'])->name('datatable.getReservacionesXCliente');
    Route::get('datatables/reservaciongeneral', [DatatableController::class, 'ReservacionesGeneral'])->name('ReservacionesGeneral');
    Route::get('/datatables/reservaciones-por-fecha/{fecha1}/{fecha2}', [DatatableController::class, 'reservacionesXFecha'])->name('datatable.reservaciones_por_fecha');
    Route::get('/datatables/ingresosreservaciones', [DatatableController::class, 'getIngresosReservaciones'])->name('datatables.getIngresosReservaciones');
    Route::get('/datatables/ingresosdiarios/{fecha}', [DatatableController::class, 'getIngresosDiarios'])->name('datatables.getIngresosDiarios');
    Route::get('/datatables/getingresosmensuales/{mes}', [DatatableController::class, 'getIngresosMensuales'])->name('datatables.getIngresosMensuales');
    Route::get('/datatables/huespedes-frecuentes', [DatatableController::class, 'getHuespedesfrecuentes'])->name('datatables.getHuespedesfrecuentes');
    Route::get('/datatables/reservaciones-por-dia/{dia}', [DatatableController::class, 'getHuespedesPorDia'])->name('datatables.getHuespedesPorDia');
    Route::get('/datatables/huespedesporfecha/{fecha1}/{fecha2}', [DatatableController::class, 'getHuespedesPorIntervalo'])->name('datatables.getHuespedesPorIntervalo');
    Route::get('/datatables/huespedes-con-mas-ingresos', [DatatableController::class, 'getHuespedesConMasIngresos'])->name('datatables.getHuespedesConMasIngresos');
    Route::get('/datatables/existencia-productos', [DatatableController::class, 'getExistenciaProductos'])->name('datatables.getExistenciaProductos');
    Route::get('/datatables/getproductos-mas-vendidos', [DatatableController::class, 'getProductosmasVendidos'])->name('datatables.getProductosmasVendidos');
    Route::get('/datatable/getcuentas', [DatatableController::class, 'getCuentas'])->name('datatable.getCuentas');
    Route::get('/datatables/papelera', [DatatableController::class, 'getTrash'])->name('datatable.papelera');
    //
    Route::get('papelera/deleteall', [PapeleraController::class, 'dstroyall'])->name('papelera.destroyall');
    Route::put('papelera/{id}/restore', [PapeleraController::class, 'restore'])->name('papelera.restore');
    Route::get('/cuentas/{numcta}/abonar', [CuentasporpagarController::class, 'showCta'])->name('cuentas.abonar');
    Route::get('/rpts/get-productos-mas-vendidos', [ProductoController::class, 'productosmasVendidos'])->name('productosmasVendidos');
    Route::get('/rpts/getExistencias-productos', [ProductoController::class, 'existenciasProductos'])->name('existenciasProductos');
    Route::get('/rpts/gethuespedesconmasingresos', [ClienteController::class, 'getHuespedesConMasIngresos'])->name('getHuespedesConMasIngresos');
    Route::get('/rpts/huespedes-fechas', [ClienteController::class, 'getHuespedesXFecha'])->name('getHuespedesXFecha');
    Route::get('/rpts/huespedes-dia', [ClienteController::class, 'getHuespedesXDia'])->name('getHuespedesXDia');
    Route::get('/rpts/huespedes-frecuentes', [ClienteController::class, 'getHuespedeshabituales'])->name('getHuespedeshabituales');
    Route::get('/rpts/ingresospormes/', [AperturasCajasController::class, 'reporteIngresosXMes'])->name('reporteIngresosXMes');
    Route::get('/rpts/ingresospordia/', [AperturasCajasController::class, 'reporteIngresosXDia'])->name('reporteIngresosXDia');
    Route::get('/reservacionesin/{id}/edit', [ReservacionController::class, 'editIn'])->name('reservaciones.editin');
    Route::get('/reservacionesin', [ReservacionController::class, 'indexIn'])->name('reservaciones.indexin');
    Route::get('/reservacionesin/create', [ReservacionController::class, 'createReservacionIn'])->name('reservaciones.createReservacionIn');
    Route::get('/reservaciones/facturasar/{num_reservacion}', [ReservacionController::class, 'facturaReservacionesSar'])->name('rptReservacionSar');
    Route::get('/notificacionhabitacion', [NotificacionesHabitacionController::class, 'crearNotificaciones'])->name('insertNotifications');
    Route::get('/reservaciones/facturasin/{num_reservacion}', [ReservacionController::class, 'facturaReservacionesIn'])->name('rptReservacionIn');
    Route::get('/get/notificacionhabitacion', [NotificacionesHabitacionController::class, 'getNotificaciones'])->name('GetNotifications');
    Route::get('/notificacionhabitacionmarkasread', [NotificacionesHabitacionController::class, 'markAsRead'])->name('MarkAsReadHabitNotif');
    Route::get('reservaciones/{reservacion}/edit', [ReservacionController::class, 'edit'])->name('reservacione.edit');
    Route::get('reservaciones/{reservacion}', [ReservacionController::class, 'show'])->name('reservacione.show');
    Route::get('/reservations/update-status', [ReservacionController::class, 'finishReservations'])->name('reservations.updateStatus');
    Route::get('/insumos/lotes/{producto_id}', [LoteinsumoController::class, 'index'])->name('lotes.insumos.index');
    Route::get('/productos/lotes/{producto_id}', [LoteController::class, 'index'])->name('lotes.productos.index');
    Route::get('/insumos/lotes/create/{producto_codigo}', [LoteinsumoController::class, 'createlote'])->name('lotes.insumos.create');
    Route::get('/productos/lotes/create/{producto_codigo}', [LoteController::class, 'createlote'])->name('lotes.productos.create');
    Route::get('/lote/{lote_id}/editar', [LoteController::class, 'edit2'])->name('lotes.productos.edit');
    Route::get('/loteinsumo/{lote_id}/editar', [LoteinsumoController::class, 'edit2'])->name('lotes.insumos.edit');
    Route::get('/cajas/{turno_id}/{fecha}', [AperturasCajasController::class, 'getCajasAbiertasxFechayTurno'])->name('cajas.getCajasAbiertasxFechayTurno');
    Route::post('myurl', [SearchController::class, 'show']);
    Route::post('searchinsumos', [SearchController::class, 'getinsumos']);
    Route::post('myurlventa', [SearchControllerVenta::class, 'show']);
    Route::get('/verificar-apertura-caja', [AperturasCajasController::class, 'verificarAperturaCaja']);
    Route::get('verificar-existencias-productos/{codprod}', [ProductoController::class, 'verificarExistenciasProductos']);
    Route::get('/existencias-prod/{id}', [ProductoController::class, 'verificarExistenciasProductosPorId']);
    Route::get('verificar-precios-productos/{codprod}', [ProductoController::class, 'verificarPreciosProductos']);
    Route::get('verificar-tipo-producto/{codprod}', [ProductoController::class, 'verificarTipoProducto']);
    Route::get('verificar-unidades-productos/{codprod}', [ProductoController::class, 'getUnidadesInsumo']);
    Route::get('/verificar-cais-sar/{code}', [CorrelativoController::class, 'validateBill']);
    Route::get('/traer-next-correlativo-fact/{valor}', [CorrelativoController::class, 'getNextCorrelativoFact']);
    Route::get('/get-expiredate-rest-currentcai', [CaiController::class, 'getInfoCai']);
    Route::get('/show-facturas-sar', [DetallefacturaController::class, 'verFacturas'])->name('verFacturas');
    Route::get('/get-info-hotel', [InfohotelController::class, 'getInfoHotel'])->name('getInfoHotel');
    Route::get('/get-info-deduccion/{id}', [DeduccionController::class, 'getInfoDeduccion']);
    Route::get('/get-info-empleado/{id}', [EmpleadoController::class, 'getInfoEmpleado']);
    Route::get('/traer-productos-poca-existencia', [ProductoController::class, 'getProductosPocaExistencia']);
    Route::get('/anular/factura/{num}/sar', [CabecerafacturaController::class, 'anularSar']);
    Route::get('/anular/factura/{id}/int', [CabfacturainternoController::class, 'anularInterno']);
    Route::get('/anular/factura/{id}/reservacionin', [CabfacturareservacioninternoController::class, 'anularReservacionInterno']);
    Route::get('/anular/factura/{num}/reservacionsar', [CabfacturareservacionsarController::class, 'anularReservacionSar']);

    // Ruta para generar codigos
    Route::get('/correlativos/get/{key}/{table}/{prefix?}', [CorrelativoController::class, 'generateClientCode']);
    //ruta para reportes
    Route::get('/cierrecaja/{apnum}', [CierresCajaController::class, 'imprimirCierre'])->name('rpt.cierreCaja');
    Route::get('/compras/pdf', [CompraproductoController::class, 'rptGenerarCompra'])->name('compras.rptcompras');
    Route::get('/rpt/factura/{numfactura}', [DetallefacturaController::class, 'imprimirFactura'])->name('facturas.rptfacturasar');
    Route::get('/rpt/facturain/{numfactura}', [DetallefacturainternoController::class, 'imprimirFactura'])->name('facturasin.rptfacturaIn');
    Route::get('/rpt/factura-reservacion/{numfactura}', [DetafacturareservacionsarController::class, 'imprimirFactura'])->name('facturas.rptfacturareservacionsar');
    Route::get('/rpt/factura-reservacionin/{numfactura}', [DetafacturareservacioninternoController::class, 'imprimirFactura'])->name('facturas.rptfacturareservacionIn');
    Route::get('/rpt/imprimir-planilla/{planilla_id}', [DetalleplanillaController::class, 'imprimirPlanilla'])->name('rpt.imprimirPlanilla');
    Route::get('/rpt/reservaciones-activas', [ReporteController::class, 'ReservacionesActivas'])->name('rpt.ReservacionesActivas');
    Route::get('/rpt/imprimir-reservaciones-activas', [ReporteController::class, 'imprimirReservacionesActivas'])->name('rpt.imprimirReservacionesActivas');
    Route::get('/rpt/imprimir-reservaciones-xcliente/{id}', [ReporteController::class, 'imprimirReservacionesXCliente'])->name('rpt.imprimirReservacionesXCliente');
    Route::get('/rpt/reservaciones-xcliente/index', [ReporteController::class, 'ReservacionesXClienteIndex'])->name('ReservacionesXClienteIndex');
    Route::get('/rpt/imprimir-reservaciones-general', [ReporteController::class, 'imprimirReservacionesGeneral'])->name('rpt.imprimirReservacionesGeneral');
    Route::get('/rpt/reservaciones-por-fecha', [ReservacionController::class, 'reservacionPorFecha'])->name('reservaciones-por-fecha');
    Route::get('/rpt/imprimir-reservaciones-por-fechas/{f1}/{f2}', [ReporteController::class, 'imprimirReservacionesPorFecha']);
    Route::get('/rpt/ingresos-reservaciones', [ReservacionController::class, 'ingresosReservaciones'])->name('ingresosReservaciones');
    Route::get('/rpt/imprimirIngresosReservaciones', [ReporteController::class, 'imprimirReservacionesIngresos'])->name('imprimirReservacionesIngresos');
    Route::get('/rpt/imprimirIngresosXFecha/{fecha}', [ReporteController::class, 'imprimirIngresosxFecha'])->name('rpt.imprimirIngresosxFecha');
    Route::get('/rpt/imprimirIngresosPorMes/{mes}', [ReporteController::class, 'imprimirIngresosxMes'])->name('rpt.imprimirIngresosxMes');
    Route::get('/rpt/imprimir/huespedes-frecuentes', [ReporteController::class, 'imprimirHuespedesFrecuentes'])->name('rpt.imprimirHuespedesFrecuentes');
    Route::get('/rpt/imprimirhuespedesxdia/{fecha}', [ReporteController::class, 'imprimirHuespedesXDia'])->name('rpt.imprimirClientesPorDia');
    Route::get('/rpt/imprimirhuespedesxfecha/{fecha1}/{fecha2}', [ReporteController::class, 'imprimirHuespedesXfechas'])->name('rpt.imprimirHuespedesXfechas');
    Route::get('/rpt/imprimirHuespedesmasingresos', [ReporteController::class, 'imprimirHuespedesMasIngresos'])->name('rpt.imprimirHuespedesMasIngresos');
    Route::get('/rpt/imprimirExistenciaProductos', [ReporteController::class, 'imprimirExistenciaProductos'])->name('rpt.imprimirExistenciaProductos');
    Route::get('rpt/imprimirproductosmasvendidos', [ReporteController::class, 'imprimirProductosmasVendidos'])->name('rpt.imprimirProductosmasVendidos');
});
