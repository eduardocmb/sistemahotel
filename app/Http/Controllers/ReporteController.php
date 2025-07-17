<?php

namespace App\Http\Controllers;

use App\Models\aperturasCajas;
use App\Models\cliente;
use App\Models\infohotel;
use App\Models\lote;
use App\Models\producto;
use App\Models\reporte;
use App\Models\reservacion;
use App\Models\role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $rol;

    public function __construct()
    {
        $this->rol = role::where('codigo', Auth::user()->idrol)->first();
    }

    public function index()
    {
        if ($this->rol->ver_informacion == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
    }

    public function imprimirReservacionesPorFecha($fecha1, $fecha2)
    {
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        if ($this->rol->reimprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
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
        $fechainicial = $fecha1;
        $fechafinal = $fecha2;
        if (!$reservacion->isEmpty()) {
            $info = infohotel::first();
            $pdf = FacadePdf::loadView('rpts.reservaciones.reportes.rptreservacionesxfecha', [
                'info' => $info,
                'reservaciones' => $reservacion,
                'fechainicial' => $fechainicial,
                'fechafinal' => $fechafinal
            ]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('reporte.pdf', ['Attachment' => 0]);
        } else {
            return redirect()->route('dashboard')->with('nopermiso', 'Error, no se encontraron reservaciones en el intervalo de fechas seleccionado.');
        }
    }

    public function imprimirReservacionesActivas()
    {
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        if ($this->rol->reimprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $hoy = Carbon::today();

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
            ->where(function ($query) use ($hoy) {
                $query->whereDate('reservacions.fecha_entrada', '<=', $hoy)
                    ->whereDate('reservacions.salida', '>=', $hoy);
            })
            ->get();
        $info = infohotel::first();
        if (!$reservacion->isEmpty()) {
            $pdf = FacadePdf::loadView('rpts.reservaciones.reportes.rptreservacion', [
                'info' => $info,
                'reservaciones' => $reservacion
            ]);

            $pdf->setPaper('A4', 'portrait');


            return $pdf->stream('reporte.pdf', ['Attachment' => 0]);
        } else {
            return redirect()->route('dashboard')->with('nopermiso', 'Error, no se encontraron reservaciones activas.');
        }
    }
    public function ReservacionesXClienteIndex()
    {
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        if ($this->rol->reimprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        if ($this->rol->ver_informacion == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $clientes = cliente::where('del', 'N')->get();
        return view('rpts.reservaciones.reservacionxcliente', compact('clientes'));
    }

    public function ReservacionesActivas()
    {
        $hoy = Carbon::today();

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
            ->where(function ($query) use ($hoy) {
                $query->whereDate('reservacions.fecha_entrada', '<=', $hoy)
                    ->whereDate('reservacions.salida', '>=', $hoy);
            })
            ->get();

        return datatables()->of($reservacion)->toJson();
    }

    public function imprimirReservacionesGeneral()
    {
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        if ($this->rol->reimprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }

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
        $total = 0;
        foreach ($reservacion as $res) {
            $total += floatval($res->total);
        }
        $info = infohotel::first();
        if (!$reservacion->isEmpty()) {
            $pdf = FacadePdf::loadView('rpts.reservaciones.reportes.rptreservaciongral', [
                'info' => $info,
                'reservaciones' => $reservacion
            ]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('reporte.pdf', ['Attachment' => 0]);
        } else {
            return redirect()->route('dashboard')->with('nopermiso', 'Error, no se encontraron reservaciones.');
        }
    }


    public function imprimirReservacionesXCliente($cliente_id)
    {
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        if ($this->rol->reimprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $hoy = Carbon::today();

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
            ->where('cliente_id', $cliente_id)
            ->get();
        $info = infohotel::first();
        if (!$reservacion->isEmpty()) {
            $pdf = FacadePdf::loadView('rpts.reservaciones.reportes.rptreservacionxcliente', [
                'info' => $info,
                'reservaciones' => $reservacion
            ]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('reporte.pdf', ['Attachment' => 0]);
        } else {
            return redirect()->route('dashboard')->with('nopermiso', 'Error, no se encontraron reservaciones con el cliente seleccionado.');
        }
    }
    public function ReservacionesPorCliente($cliente_id)
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
            ->where('cliente_id', $cliente_id)
            ->get();

        return datatables()->of($reservacion)->toJson();
    }

    public function imprimirReservacionesIngresos()
    {
        $datos = DB::table('cabfacturareservacionsars')
            ->select('numreservacion', 'fecha', 'cliente', 'descto', 'isv15', 'isv18', 'total')
            ->union(
                DB::table('cabfacturareservacioninternos')
                    ->select('numreservacion', 'fecha', 'cliente', 'descto', 'isv15', 'isv18', 'total')
            )
            ->distinct()
            ->get();
        $total = 0;
        foreach ($datos as $dato) {
            $total += floatval($dato->total);
        }
        $info = infohotel::first();
        if (!$datos->isEmpty()) {
            $pdf = FacadePdf::loadView('rpts.reservaciones.reportes.rptingresosreservaciones', [
                'info' => $info,
                'datos' => $datos,
                'total' => $total
            ]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('reporte.pdf', ['Attachment' => 0]);
        } else {
            return redirect()->route('dashboard')->with('nopermiso', 'Error, no se encontraron reservaciones.');
        }
    }

    public function imprimirIngresosxFecha($fecha)
    {
        $aper = aperturasCajas::select(
            'aperturas_cajas.codigo_apertura',
            'aperturas_cajas.fecha',
            'cierres_cajas.*'
        )
            ->join('cierres_cajas', 'aperturas_cajas.id', '=', 'cierres_cajas.aperturas_caja_id')
            ->where('aperturas_cajas.fecha', $fecha)
            ->get();
        $total = 0;
        foreach ($aper as $dato) {
            $total += floatval($dato->totventas);
        }
        $info = infohotel::first();
        if (!$aper->isEmpty()) {
            $pdf = FacadePdf::loadView('rpts.finanzas.reportes.rptingresospordia', [
                'info' => $info,
                'datos' => $aper,
                'total' => $total
            ]);

            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('reporte.pdf', ['Attachment' => 0]);
        } else {
            return redirect()->route('dashboard')->with('nopermiso', 'Error, no se encontraron ingresos en la fecha seleccionada.');
        }
    }

    public function imprimirIngresosxMes($mes)
    {
        $year = substr($mes, 0, 4);
        $month = substr($mes, 5, 2);
        $meses = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        ];
        $mesEnPalabras = $meses[$month];
        $anioEnPalabras = $year;

        $aper = aperturasCajas::select(
            'aperturas_cajas.codigo_apertura',
            'cierres_cajas.*'
        )
            ->join('cierres_cajas', 'aperturas_cajas.id', '=', 'cierres_cajas.aperturas_caja_id')
            ->whereYear('aperturas_cajas.fecha', $year)
            ->whereMonth('aperturas_cajas.fecha', $month)
            ->get();
        $total = 0;
        foreach ($aper as $dato) {
            $total += floatval($dato->totventas);
        }
        $info = infohotel::first();
        if (!$aper->isEmpty()) {
            $pdf = FacadePdf::loadView('rpts.finanzas.reportes.rptingresospormes', [
                'info' => $info,
                'datos' => $aper,
                'total' => $total,
                'mes' => $mesEnPalabras,
                'anio' => $anioEnPalabras
            ]);

            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('reporte.pdf', ['Attachment' => 0]);
        } else {
            return redirect()->route('dashboard')->with('nopermiso', 'Error, no se encontraron ingresos en el mes seleccionado.');
        }
    }

    public function imprimirHuespedesFrecuentes()
    {
        $datos = cliente::select('clientes.id', 'clientes.codigo_cliente', 'clientes.nombre_completo', DB::raw('COUNT(reservacions.id) as total_reservaciones'))
            ->join('reservacions', 'clientes.id', '=', 'reservacions.cliente_id')
            ->where('reservacions.del', 'N')
            ->groupBy('clientes.id', 'clientes.nombre_completo', 'clientes.codigo_cliente')
            ->orderByDesc('total_reservaciones')
            ->get();
        $info = infohotel::first();
        if(!$datos->isEmpty()){
            $pdf = FacadePdf::loadView('rpts.clientes.rptHuespedesFrecuentes', [
                'info' => $info,
                'datos' => $datos
            ]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('reporte.pdf', ['Attachment' => 0]);
        }else{
            return redirect()->route('dashboard')->with('nopermiso', 'Error, no se encontraron huespedes.');
        }

    }

    public function imprimirHuespedesXDia($fecha)
    {
        $datos = Cliente::select('clientes.id', 'reservacions.numero', 'clientes.codigo_cliente', 'clientes.nombre_completo', DB::raw('COUNT(reservacions.id) as total_reservaciones'))
            ->join('reservacions', 'clientes.id', '=', 'reservacions.cliente_id')
            ->where('reservacions.del', 'N')
            ->whereDate('reservacions.fecha_entrada', $fecha)
            ->groupBy('clientes.id', 'clientes.nombre_completo', 'clientes.codigo_cliente', 'reservacions.numero')
            ->orderByDesc('total_reservaciones')
            ->get();
        $info = infohotel::first();
        if(!$datos->isEmpty()){
            $pdf = FacadePdf::loadView('rpts.clientes.rptHuespedesporDia', [
                'info' => $info,
                'datos' => $datos
            ]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('reporte.pdf', ['Attachment' => 0]);
        }else{
            return redirect()->route('dashboard')->with('nopermiso', 'Error, no se encontraron huespedes.');
        }

    }

    public function imprimirHuespedesXfechas($fechaInicio, $fechaFin)
    {
        $datos = Cliente::select('clientes.id', 'reservacions.fecha_entrada', 'reservacions.numero', 'clientes.codigo_cliente', 'clientes.nombre_completo', DB::raw('COUNT(reservacions.id) as total_reservaciones'))
            ->join('reservacions', 'clientes.id', '=', 'reservacions.cliente_id')
            ->where('reservacions.del', 'N')
            ->whereBetween('reservacions.fecha_entrada', [$fechaInicio, $fechaFin])
            ->groupBy('clientes.id', 'reservacions.fecha_entrada', 'reservacions.numero', 'clientes.nombre_completo', 'clientes.codigo_cliente')
            ->orderByDesc('total_reservaciones')
            ->get();
        $info = infohotel::first();

        if(!$datos->isEmpty()){
            $fechainicial = $fechaInicio;
            $fechafinal = $fechaFin;
            $pdf = FacadePdf::loadView('rpts.clientes.rptHuespedesxFechas', [
                'info' => $info,
                'datos' => $datos,
                'fechainicial' => $fechainicial,
                'fechafinal' => $fechafinal
            ]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('reporte.pdf', ['Attachment' => 0]);
        }
        else{
            return redirect()->route('dashboard')->with('nopermiso', 'Error, no se encontraron huespedes.');
        }
    }

    public function imprimirHuespedesMasIngresos()
    {
        $datos = Cliente::select('clientes.id', 'clientes.codigo_cliente', 'clientes.nombre_completo', DB::raw('SUM(reservacions.total) as total_ingresos'))
            ->join('reservacions', 'clientes.id', '=', 'reservacions.cliente_id')
            ->where('reservacions.del', 'N')
            ->groupBy('clientes.id', 'clientes.nombre_completo', 'clientes.codigo_cliente')
            ->orderByDesc('total_ingresos')
            ->get();
        $info = infohotel::first();

        if(!$datos->isEmpty()){
            $total = 0;
            foreach ($datos as $dato) {
                $total += floatval($dato->total_ingresos);
            }
            $pdf = FacadePdf::loadView('rpts.clientes.rptHuespedesconmasingresos', [
                'info' => $info,
                'datos' => $datos,
                'total_' => $total,
            ]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('reporte.pdf', ['Attachment' => 0]);
        }else{
            return redirect()->route('dashboard')->with('nopermiso', 'Error, no se encontraron huespedes.');
        }
    }

    public function imprimirExistenciaProductos()
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
                $lote = new lote();
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

        $info = infohotel::first();
        if(!$productos->isEmpty()){
            $pdf = FacadePdf::loadView('rpts.reservaciones.productos.rptExistencias', [
                'info' => $info,
                'productos' => $productos,
            ]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('reporte.pdf', ['Attachment' => 0]);
        }else{
            return redirect()->route('dashboard')->with('nopermiso', 'Error, no se encontraron productos.');
        }

    }

    public function imprimirProductosmasVendidos()
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
        $info = infohotel::first();
        if(!$productosMasVendidos->isEmpty()){
            $pdf = FacadePdf::loadView('rpts.reservaciones.productos.rptProductosmasvendidos', [
                'info' => $info,
                'datos' => $productosMasVendidos,
            ]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('reporte.pdf', ['Attachment' => 0]);
        }else{
            return redirect()->route('dashboard')->with('nopermiso', 'Error, no se encontraron productos.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(reporte $reporte)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(reporte $reporte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, reporte $reporte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(reporte $reporte)
    {
        //
    }
}
