<?php

namespace App\Http\Controllers;

use App\Models\aperturasCajas;
use App\Models\cabecerafactura;
use App\Models\cabfacturainterno;
use App\Models\cabfacturareservacioninterno;
use App\Models\cabfacturareservacionsar;
use App\Models\cai;
use App\Models\caja;
use App\Models\categoria;
use App\Models\cierresCaja;
use App\Models\cliente;
use App\Models\compraproducto;
use App\Models\cuentasporpagar;
use App\Models\deduccion;
use App\Models\departamento;
use App\Models\gasto;
use App\Models\habitacione;
use App\Models\insumo;
use App\Models\kardex;
use App\Models\lote;
use App\Models\loteinsumo;
use App\Models\NotificacionesHabitacion;
use App\Models\papelera;
use App\Models\producto;
use App\Models\proveedor;
use App\Models\reservacion;
use App\Models\unidadinsumo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\role;
use App\Models\turno;
use App\Models\usoinsumo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DatatableController extends Controller
{
    public function getReservaciones()
    {
        $reservacion = DB::table('reservacions')
            ->join('clientes', 'reservacions.cliente_id', '=', 'clientes.id')
            ->join('habitaciones', 'reservacions.habitacion_id', '=', 'habitaciones.id')
            ->select(
                'reservacions.id',
                'reservacions.numero',
                'clientes.nombre_completo as cliente',
                'habitaciones.numero_habitacion as habitacion',
                'reservacions.fecha_entrada',
                'reservacions.salida',
                'reservacions.total',
                'reservacions.estado'
            )
            ->get();

        return datatables()->of($reservacion)->toJson();
    }

    public function getIngresosReservaciones()
    {
        $datos = DB::table('cabfacturareservacionsars')
            ->select('numreservacion', 'fecha', 'cliente', 'descto', 'isv15', 'isv18', 'total')
            ->union(
                DB::table('cabfacturareservacioninternos')
                    ->select('numreservacion', 'fecha', 'cliente', 'descto', 'isv15', 'isv18', 'total')
            )
            ->get();
        return datatables()->of($datos)->toJson();
    }

    public function reservacionesXFecha($fecha1, $fecha2)
    {
        $reservacion = DB::table('reservacions')
            ->join('clientes', 'reservacions.cliente_id', '=', 'clientes.id')
            ->join('habitaciones', 'reservacions.habitacion_id', '=', 'habitaciones.id')
            ->select(
                'reservacions.id',
                'reservacions.numero',
                'clientes.nombre_completo as cliente',
                'habitaciones.numero_habitacion as habitacion',
                'reservacions.fecha_entrada',
                'reservacions.salida',
                'reservacions.total',
                'reservacions.estado'
            )
            ->whereBetween('reservacions.fecha_entrada', [$fecha1, $fecha2])
            ->get();
        return datatables()->of($reservacion)->toJson();
    }

    public function ReservacionesGeneral()
    {
        $reservaciones = reservacion::all();
        return datatables()->of($reservaciones)->toJson();
    }

    public function getHuespedes()
    {
        $clientes = Cliente::where('del', 'N')->get();
        return datatables()->of($clientes)->toJson();
    }

    public function getTrash(){
        $trash = papelera::all();
        return datatables()->of($trash)->toJson();
    }

    public function getHabitaciones()
    {
        $habitaciones = habitacione::where('del', 'N')->get();
        return datatables()->of($habitaciones)->toJson();
    }

    public function getusoInsumos()
    {
        $uso = usoinsumo::select(
            'usoinsumos.*',
            'insumos.nombre'
        )
            ->join('insumos', 'insumos.id', '=', 'usoinsumos.insumo_id')
            ->get();
        return datatables()->of($uso)->toJson();
    }

    public function getCuentas()
    {
        $cuentas = cuentasporpagar::select(
            'cuentasporpagars.*',
            'proveedors.nombre as proveedor',
            'users.username as usuario'
        )
            ->join('proveedors', 'proveedors.id', '=', 'cuentasporpagars.proveedor_id')
            ->join('users', 'users.id', '=', 'cuentasporpagars.user_id')
            ->get();

        return datatables()->of($cuentas)->toJson();
    }


    public function getInsumos()
    {
        $insumos = insumo::select(
            'insumos.*',
            'categorias.categoria',
            DB::raw('IFNULL(SUM(loteinsumos.cantidad), 0) as total_cantidad')
        )
            ->join('categorias', 'insumos.categoria_id', '=', 'categorias.id')
            ->join('loteinsumos', 'insumos.codigo', '=', 'loteinsumos.codigo_insumo')
            ->where('insumos.del', 'N')
            ->groupBy(
                'insumos.id',
                'categorias.categoria',
                'insumos.codigo',
                'insumos.precio_venta',
                'insumos.nombre',
                'insumos.impuesto_id',
                'insumos.unidad_entrada_id',
                'insumos.unidad_salida_id',
                'insumos.stock_minimo',
                'insumos.created_at',
                'insumos.updated_at',
                'insumos.descripcion',
                'insumos.tipo_producto',
                'insumos.categoria_id',
                'insumos.del'
            )
            ->get();

        if ($insumos->isEmpty()) {
            $insumosExistentes = Insumo::where('del', 'N')
                ->where('tipo_Insumo', 'PRODUCTO FINAL')
                ->get();

            foreach ($insumosExistentes as $Insumo) {
                $loteinsumos = new loteinsumo();
                $loteinsumos->codigo_Insumo = $Insumo->codigo;
                $loteinsumos->fecha = Carbon::today();
                $loteinsumos->precio_compra = 0;
                $loteinsumos->cantidad = 0;
                $loteinsumos->cant_comprada = 0;
                $loteinsumos->fecha_vencimiento = null;
                $loteinsumos->save();
            }

            $insumos = Insumo::select(
                'insumos.*',
                'categorias.categoria',
                DB::raw('IFNULL(SUM(loteinsumoss.cantidad), 0) as total_cantidad')
            )
                ->join('categorias', 'insumos.categoria_id', '=', 'categorias.id')
                ->join('loteinsumoss', 'insumos.codigo', '=', 'loteinsumoss.codigo_Insumo')
                ->where('insumos.del', 'N')
                ->groupBy(
                    'insumos.id',
                    'categorias.categoria',
                    'insumos.codigo',
                    'insumos.precio_venta',
                    'insumos.nombre',
                    'insumos.impuesto_id',
                    'insumos.unidad_entrada_id',
                    'insumos.unidad_salida_id',
                    'insumos.stock_minimo',
                    'insumos.created_at',
                    'insumos.updated_at',
                    'insumos.descripcion',
                    'insumos.tipo_Insumo',
                    'insumos.categoria_id',
                    'insumos.del'
                )
                ->get();
        }

        return datatables()->of($insumos)->toJson();
    }


    public function getProveedores()
    {
        $proveedor = proveedor::where('del', 'N')->get();
        return datatables()->of($proveedor)->toJson();
    }

    public function getCategorias()
    {
        $categoria = categoria::where('del', 'N')->get();
        return datatables()->of($categoria)->toJson();
    }
    public function getUnidades()
    {
        $unidades = unidadinsumo::where('del', 'N')->get();
        return datatables()->of($unidades)->toJson();
    }

    public function getNotificaciones()
    {
        $notificaciones = NotificacionesHabitacion::all();
        return datatables()->of($notificaciones)->toJson();
    }

    public function getExistenciaProductos()
    {
        $productos = Producto::select(
            'productos.*',
            'categorias.categoria',
            DB::raw('IFNULL(SUM(lotes.cantidad), 0) as total_cantidad')
        )
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->leftJoin('lotes', 'productos.codigo', '=', 'lotes.codigo_producto')
            ->where('productos.del', 'N')
            ->where('tipo_producto', 'PRODUCTO FINAL')
            ->groupBy(
                'productos.id',
                'categorias.categoria',
                'productos.codigo',
                'productos.precio_venta',
                'productos.nombre',
                'productos.impuesto_id',
                'productos.unidad_entrada_id',
                'productos.unidad_salida_id',
                'productos.stock_minimo',
                'productos.created_at',
                'productos.updated_at',
                'productos.descripcion',
                'productos.tipo_producto',
                'productos.categoria_id',
                'productos.del'
            )
            ->get();

        if ($productos->isEmpty()) {
            $productosExistentes = Producto::where('del', 'N')
                ->get();

            foreach ($productosExistentes as $producto) {
                $lote = new Lote();
                $lote->codigo_producto = $producto->codigo;
                $lote->fecha = Carbon::today();
                $lote->precio_compra = 0;
                $lote->cantidad = 0;
                $lote->cant_comprada = 0;
                $lote->fecha_vencimiento = null;
                $lote->save();
            }

            $productos = Producto::select(
                'productos.*',
                'categorias.categoria',
                DB::raw('IFNULL(SUM(lotes.cantidad), 0) as total_cantidad')
            )
                ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
                ->leftJoin('lotes', 'productos.codigo', '=', 'lotes.codigo_producto')
                ->where('productos.del', 'N')
                ->where('tipo_producto', 'PRODUCTO FINAL')
                ->groupBy(
                    'productos.id',
                    'categorias.categoria',
                    'productos.codigo',
                    'productos.precio_venta',
                    'productos.nombre',
                    'productos.impuesto_id',
                    'productos.unidad_entrada_id',
                    'productos.unidad_salida_id',
                    'productos.stock_minimo',
                    'productos.created_at',
                    'productos.updated_at',
                    'productos.descripcion',
                    'productos.tipo_producto',
                    'productos.categoria_id',
                    'productos.del'
                )
                ->get();
        }

        return datatables()->of($productos)->toJson();
    }

    public function getProductosmasVendidos()
    {
        $productosMasVendidos = DB::table('detallefacturas')
            ->select('codproducto', 'descripcion', DB::raw('SUM(cant) as total_cantidad'))
            ->groupBy('codproducto', 'descripcion')
            ->unionAll(
                DB::table('detallefacturainternos')
                    ->select('codproducto', 'descripcion', DB::raw('SUM(cant) as total_cantidad'))
                    ->groupBy('codproducto', 'descripcion')
            )
            ->get()
            ->groupBy('codproducto')
            ->map(function ($group) {
                $descripcion = $group->first()->descripcion;
                $totalCantidad = $group->sum('total_cantidad');
                return (object)[
                    'codproducto' => $group->first()->codproducto,
                    'descripcion' => $descripcion,
                    'total_cantidad' => $totalCantidad,
                ];
            })
            ->sortByDesc('total_cantidad')
            ->values();
        return datatables()->of($productosMasVendidos)->toJson();
    }

    public function getProductos()
    {
        $productos = Producto::select(
            'productos.*',
            'categorias.categoria',
            DB::raw('IFNULL(SUM(lotes.cantidad), 0) as total_cantidad')
        )
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->leftJoin('lotes', 'productos.codigo', '=', 'lotes.codigo_producto')
            ->where('productos.del', 'N')
            ->groupBy(
                'productos.id',
                'categorias.categoria',
                'productos.codigo',
                'productos.precio_venta',
                'productos.nombre',
                'productos.impuesto_id',
                'productos.unidad_entrada_id',
                'productos.unidad_salida_id',
                'productos.stock_minimo',
                'productos.created_at',
                'productos.updated_at',
                'productos.descripcion',
                'productos.tipo_producto',
                'productos.categoria_id',
                'productos.del'
            )
            ->get();

        if ($productos->isEmpty()) {
            $productosExistentes = Producto::where('del', 'N')
                ->get();

            foreach ($productosExistentes as $producto) {
                $lote = new Lote();
                $lote->codigo_producto = $producto->codigo;
                $lote->fecha = Carbon::today();
                $lote->precio_compra = 0;
                $lote->cantidad = 0;
                $lote->cant_comprada = 0;
                $lote->fecha_vencimiento = null;
                $lote->save();
            }

            $productos = Producto::select(
                'productos.*',
                'categorias.categoria',
                DB::raw('IFNULL(SUM(lotes.cantidad), 0) as total_cantidad')
            )
                ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
                ->leftJoin('lotes', 'productos.codigo', '=', 'lotes.codigo_producto')
                ->where('productos.del', 'N')
                ->groupBy(
                    'productos.id',
                    'categorias.categoria',
                    'productos.codigo',
                    'productos.precio_venta',
                    'productos.nombre',
                    'productos.impuesto_id',
                    'productos.unidad_entrada_id',
                    'productos.unidad_salida_id',
                    'productos.stock_minimo',
                    'productos.created_at',
                    'productos.updated_at',
                    'productos.descripcion',
                    'productos.tipo_producto',
                    'productos.categoria_id',
                    'productos.del'
                )
                ->get();
        }

        return datatables()->of($productos)->toJson();
    }

    public function getHuespedesfrecuentes()
    {
        $huespedesMasHabituales = cliente::select('clientes.id', 'clientes.codigo_cliente', 'clientes.nombre_completo', DB::raw('COUNT(reservacions.id) as total_reservaciones'))
            ->join('reservacions', 'clientes.id', '=', 'reservacions.cliente_id')
            ->where('reservacions.del', 'N')
            ->groupBy('clientes.id', 'clientes.nombre_completo', 'clientes.codigo_cliente')
            ->orderByDesc('total_reservaciones')
            ->get();
        return datatables()->of($huespedesMasHabituales)->toJson();
    }

    public function getHuespedesPorDia($fecha)
    {
        $huespedesPorDia = Cliente::select('clientes.id', 'reservacions.numero', 'clientes.codigo_cliente', 'clientes.nombre_completo', DB::raw('COUNT(reservacions.id) as total_reservaciones'))
            ->join('reservacions', 'clientes.id', '=', 'reservacions.cliente_id')
            ->where('reservacions.del', 'N')
            ->whereDate('reservacions.fecha_entrada', $fecha)
            ->groupBy('clientes.id', 'clientes.nombre_completo', 'clientes.codigo_cliente', 'reservacions.numero')
            ->orderByDesc('total_reservaciones')
            ->get();

        return datatables()->of($huespedesPorDia)->toJson();
    }

    public function getHuespedesPorIntervalo($fechaInicio, $fechaFin)
    {
        $huespedesPorIntervalo = Cliente::select('clientes.id', 'reservacions.numero', 'clientes.codigo_cliente', 'clientes.nombre_completo', DB::raw('COUNT(reservacions.id) as total_reservaciones'))
            ->join('reservacions', 'clientes.id', '=', 'reservacions.cliente_id')
            ->where('reservacions.del', 'N')
            ->whereBetween('reservacions.fecha_entrada', [$fechaInicio, $fechaFin])
            ->groupBy('clientes.id', 'reservacions.numero', 'clientes.nombre_completo', 'clientes.codigo_cliente')
            ->orderByDesc('total_reservaciones')
            ->get();

        return datatables()->of($huespedesPorIntervalo)->toJson();
    }

    public function getHuespedesConMasIngresos()
    {
        $huespedesConMasIngresos = Cliente::select('clientes.id', 'clientes.codigo_cliente', 'clientes.nombre_completo', DB::raw('SUM(reservacions.total) as total_ingresos'))
            ->join('reservacions', 'clientes.id', '=', 'reservacions.cliente_id')
            ->where('reservacions.del', 'N')
            ->groupBy('clientes.id', 'clientes.nombre_completo', 'clientes.codigo_cliente')
            ->orderByDesc('total_ingresos')
            ->get();

        return datatables()->of($huespedesConMasIngresos)->toJson();
    }

    public function getLotes($cod_prod)
    {
        $lotes = lote::where('codigo_producto', $cod_prod)->where('cantidad', '>', 0)->get();
        return datatables()->of($lotes)->toJson();
    }

    public function getLotesInsumos($cod_prod)
    {
        $lotes = loteinsumo::where('codigo_insumo', $cod_prod)->where('cantidad', '>', 0)->get();
        return datatables()->of($lotes)->toJson();
    }

    public function getUsuarios()
    {
        $users = User::where('idrol', '!=', 'ROL000001')->where('del', 'N')->get();
        return datatables()->of($users)->toJson();
    }

    public function getGastos()
    {
        $gastos = gasto::select('gastos.*', 'users.username')
            ->join('users', 'gastos.user_id', '=', 'users.id')
            ->where('gastos.user_id', Auth::user()->id)
            ->get();

        return datatables()->of($gastos)->toJson();
    }

    public function getRoles()
    {
        $roles = role::where('codigo', '!=', 'ROL000001')->where('del', 'N')->get();
        return datatables()->of($roles)->toJson();
    }

    public function getTurnos()
    {
        $turnos = turno::where('del', 'N')->get();
        return datatables()->of($turnos)->toJson();
    }

    public function getCajas()
    {
        $cajas = caja::where('del', 'N')->get();
        return datatables()->of($cajas)->toJson();
    }

    public function getDeducciones()
    {
        $deducciones = deduccion::all();
        return datatables()->of($deducciones)->toJson();
    }

    public function getPlanillas()
    {
        $planillas = DB::table('cabeceraplanillas')
            ->join('empleados', 'empleados.id', '=', 'cabeceraplanillas.empleado_id')
            ->select(
                'cabeceraplanillas.*',
                'empleados.nombrecompleto'
            )
            ->distinct()
            ->get();

        return datatables()->of($planillas)->toJson();
    }


    public function getEmpleados()
    {
        $empleados = DB::table('empleados')
            ->join('departamentos', 'empleados.departamento_id', '=', 'departamentos.id')
            ->select(
                'empleados.id',
                'empleados.dni',
                'empleados.nombrecompleto',
                'empleados.telefono',
                'empleados.fechaingreso',
                'empleados.salario',
                'empleados.estado',
                'departamentos.id as departamento_id',
                'departamentos.codigo as codigo_departamento',
                'departamentos.departamento as departamento'
            )
            ->where('empleados.del', 'N')
            ->get();
        return datatables()->of($empleados)->toJson();
    }

    public function getDepartamentos()
    {
        $departamentos = departamento::where('del', 'N')->get();
        return datatables()->of($departamentos)->toJson();
    }

    public function getCajasAbiertas()
    {

        $cierresCajaIds = cierresCaja::pluck('aperturas_caja_id');
        $cajas = aperturasCajas::select([
            'aperturas_cajas.id',
            'aperturas_cajas.codigo_apertura',
            'aperturas_cajas.fecha',
            'aperturas_cajas.fondoinicial',
            'aperturas_cajas.estado',
            'turnos.turno as turno',
            'cajas.numcaja as caja',
            'users.username'
        ])
        ->join('turnos', 'aperturas_cajas.turno_id', '=', 'turnos.id')
        ->join('cajas', 'aperturas_cajas.caja_id', '=', 'cajas.id')
        ->join('users', 'aperturas_cajas.user_id', '=', 'users.id')
        // ->whereNotIn('aperturas_cajas.id', $cierresCajaIds)
        ->orderBy('aperturas_cajas.fecha', 'desc')
        ->get();


        return datatables()->of($cajas)->toJson();
    }

    public function getCais()
    {
        $cais = cai::where('del', 'N')->get();

        $cais->each(function ($cai) {
            $cai->cai = substr($cai->cai, 0, 14) . '...';
        });

        return datatables()->of($cais)->toJson();
    }

    public function getComprasProductos()
    {
        $compras = DB::table('cabeceracompraproductos')
            ->select(
                'cabeceracompraproductos.id',
                'cabeceracompraproductos.codigocompra',
                'proveedors.nombre as proveedor',
                'users.name as usuario',
                'cabeceracompraproductos.fecha_compra',
                'cabeceracompraproductos.total_compra'
            )
            ->join('proveedors', 'cabeceracompraproductos.proveedor_id', '=', 'proveedors.id')
            ->join('users', 'cabeceracompraproductos.usuario_id', '=', 'users.id')
            ->get();

        return datatables()->of($compras)->toJson();
    }

    public function getComprasInsumos()
    {
        $compras = DB::table('cabeceracomprainsumos')
            ->select(
                'cabeceracomprainsumos.id',
                'cabeceracomprainsumos.codigocompra',
                'proveedors.nombre as proveedor',
                'users.name as usuario',
                'cabeceracomprainsumos.fecha_compra',
                'cabeceracomprainsumos.total_compra'
            )
            ->join('proveedors', 'cabeceracomprainsumos.proveedor_id', '=', 'proveedors.id')
            ->join('users', 'cabeceracomprainsumos.usuario_id', '=', 'users.id')
            ->get();

        return datatables()->of($compras)->toJson();
    }

    public function getFacturasSar()
    {
        $facturas = cabecerafactura::where('anular', 'N')->get();
        return datatables()->of($facturas)->toJson();
    }

    public function getFacturasInterno()
    {
        $facturas = cabfacturainterno::where('anular', 'N')->get();
        return datatables()->of($facturas)->toJson();
    }

    public function getFacturasReservacionSar()
    {
        $facturas = cabfacturareservacionsar::where('anular', 'N')->get();
        return datatables()->of($facturas)->toJson();
    }

    public function getFacturasReservacionInterno()
    {
        $facturas = cabfacturareservacioninterno::where('anular', 'N')->get();
        return datatables()->of($facturas)->toJson();
    }

    public function getproducto()
    {
        $servicios = producto::where('del', 'N')->get();
        return datatables()->of($servicios)->toJson();
    }

    public function getMovimientos($producto_code)
    {
        $mov = kardex::select(
            'kardexes.*',
            'productos.*'
        )
            ->join('productos', 'productos.codigo', '=', 'kardexes.codproducto')
            ->where('codproducto', $producto_code)->get();
        return datatables()->of($mov)->toJson();
    }

    public function getIngresosDiarios($fecha)
    {
        $aper = aperturasCajas::select(
            'aperturas_cajas.codigo_apertura',
            'cierres_cajas.*'
        )
            ->join('cierres_cajas', 'aperturas_cajas.id', '=', 'cierres_cajas.aperturas_caja_id')
            ->where('aperturas_cajas.fecha', $fecha)
            ->get();
        return datatables()->of($aper)->toJson();
    }

    public function getIngresosMensuales($mes)
    {
        $year = substr($mes, 0, 4);
        $month = substr($mes, 5, 2);

        $aper = aperturasCajas::select(
            'aperturas_cajas.codigo_apertura',
            'cierres_cajas.*'
        )
            ->join('cierres_cajas', 'aperturas_cajas.id', '=', 'cierres_cajas.aperturas_caja_id')
            ->whereYear('aperturas_cajas.fecha', $year)
            ->whereMonth('aperturas_cajas.fecha', $month)
            ->get();

        return datatables()->of($aper)->toJson();
    }
}
