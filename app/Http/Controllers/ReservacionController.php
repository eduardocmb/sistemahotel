<?php

namespace App\Http\Controllers;

use App\Models\cabfacturareservacioninterno;
use App\Models\cabfacturareservacionsar;
use App\Models\caja;
use App\Models\cliente;
use App\Models\correlativo;
use App\Models\detafacturareservacioninterno;
use App\Models\detafacturareservacionsar;
use App\Models\habitacione;
use App\Models\lote;
use App\Models\reservacion;
use App\Models\reservacionservicios;
use App\Models\role;
use App\Models\producto;
use App\Models\turno;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservacionController extends Controller
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
        correlativo::firstOrCreate([
            'codigo' => 'RESV',
            'description' => 'Correlativo de Reservaciones',
        ], [
            'last' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        if ($this->rol->ver_informacion == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        return view('reservaciones.index');
    }

    public function indexIn()
    {
        correlativo::firstOrCreate([
            'codigo' => 'RESV',
            'description' => 'Correlativo de Reservaciones',
        ], [
            'last' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        if ($this->rol->ver_informacion == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        return view('reservaciones.reservacionIn.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }

        $clientes = cliente::all()->sortBy('nombre_completo'); // Ordenar clientes alfabéticamente por el campo 'nombre'
        $habitaciones = habitacione::all()->sortBy('numero_habitacion')->where('estado', 'DISPONIBLE'); // Ordenar habitaciones numéricamente por el campo 'numero'
        $servicios = producto::where('del', 'N')->get();

        return view('reservaciones.create', compact('clientes', 'servicios', 'habitaciones'));
    }
    public function createReservacionIn()
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }

        $clientes = cliente::all()->sortBy('nombre_completo');
        $habitaciones = habitacione::all()->sortBy('numero_habitacion');
        $servicios = producto::where('del', 'N')->where('tipo_producto', 'SERVICIO')->get();

        return view('reservaciones.reservacionIn.create', compact('clientes', 'servicios', 'habitaciones'));
    }

    public function store(Request $request)
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        // dd($request);
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_entrada' => 'required|date|after_or_equal:today',
            'salida' => 'required|date|after:fecha_entrada',
            'habitacion_id' => 'required|exists:habitaciones,id',
            'estado' => 'required|in:CONFIRMADA,PENDIENTE,CANCELADA',
            'total' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string|max:255',
        ], [
            'cliente_id.required' => 'El cliente es obligatorio.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',
            'fecha_entrada.required' => 'La fecha de entrada es obligatoria.',
            'fecha_entrada.date' => 'La fecha de entrada debe ser una fecha válida.',
            'fecha_entrada.after_or_equal' => 'La fecha de entrada debe ser hoy o una fecha futura.',
            'salida.required' => 'La fecha de salida es obligatoria.',
            'salida.date' => 'La fecha de salida debe ser una fecha válida.',
            'salida.after' => 'La fecha de salida debe ser posterior a la fecha de entrada.',
            'habitacion_id.required' => 'La habitación es obligatoria.',
            'habitacion_id.exists' => 'La habitación seleccionada no existe.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser uno de los siguientes: Confirmada, Pendiente o Cancelada.',
            'total.required' => 'El total es obligatorio.',
            'total.numeric' => 'El total debe ser un valor numérico.',
            'total.min' => 'El total no puede ser menor a 0.',
            'observaciones.string' => 'Las observaciones deben ser un texto.',
            'observaciones.max' => 'Las observaciones no pueden exceder los 255 caracteres.',
        ]);

        $cliente = Cliente::findOrFail($request->cliente_id);

        $reservacionExistente = Reservacion::where('cliente_id', $request->cliente_id)
            ->whereIn('estado', ['CONFIRMADA', 'PENDIENTE'])
            ->where(function ($query) use ($request) {
                $query->whereBetween('fecha_entrada', [$request->fecha_entrada, $request->salida])
                    ->orWhereBetween('salida', [$request->fecha_entrada, $request->salida])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('fecha_entrada', '<=', $request->fecha_entrada)
                            ->where('salida', '>=', $request->salida);
                    });
            })
            ->exists();

        if ($reservacionExistente) {
            return back()->withInput()->withErrors(['error' => 'Este cliente ya tiene una reservación activa en el rango de fechas seleccionado.']);
        }

        $habitacion = habitacione::findOrFail($request->habitacion_id);

        if ($habitacion->estado !== 'DISPONIBLE') {
            return back()->withInput()->withErrors(['error' => 'La habitación seleccionada no está disponible. Estado actual: ' . $habitacion->estado]);
        }

        $hoy = Carbon::today();
        $conflictos = Reservacion::where('habitacion_id', $request->habitacion_id)
            ->where('estado', 'CONFIRMADA')
            ->where('fecha_entrada', '<=', $hoy)
            ->where('salida', '>', $hoy)
            ->exists();

        if ($conflictos) {
            return back()->withInput()->withErrors(['error' => 'La habitación ya está reservada.']);
        }

        $reservacion = new Reservacion();
        $reservacion->numero = $request->numeroreservacion;
        $reservacion->cliente_id = $request->cliente_id;
        $reservacion->fecha_entrada = $request->fecha_entrada;
        $reservacion->salida = $request->salida;
        $reservacion->habitacion_id = $request->habitacion_id;
        $reservacion->estado = $request->estado;
        $reservacion->total = $request->total;
        $reservacion->observaciones = $request->observaciones;
        $reservacion->confirmada = $request->estado == "CONFIRMADA" ? "S" : "N";
        $reservacion->save();

        if ($request->estado == "CONFIRMADA") {
            $serviciosSeleccionados = collect($request->input('servicios', []))
                ->filter(fn($servicio) => isset($servicio['seleccionado']) && $servicio['seleccionado'] == 1);

            foreach ($serviciosSeleccionados as $productoId => $servicio) {
                reservacionservicios::create([
                    'reservacion_id' => $reservacion->id,
                    'producto_id' => $productoId,
                    'cantidad' => $servicio['cantidad'] ?? 1,
                    'subtotal' => $servicio['total'] ?? 0.00,
                    'del' => 'N',
                ]);

                try {
                    $this->restarInventario($productoId, $servicio['cantidad'] ?? 1, $reservacion->numero);
                } catch (\Exception $e) {
                    return back()->withInput()->withErrors(['error' => $e->getMessage()]);
                }
            }
        }

        $correlativo = correlativo::where('codigo', 'RESV')->first();
        if ($correlativo) {
            $correlativo->increment('last', 1);
        }

        $habitacion->estado = 'OCUPADA';
        $habitacion->save();

        $reservacion_flag = $request->estado == "CONFIRMADA";

        $previousUrl = url()->previous();
        if ($previousUrl == route('reservaciones.create')) {
            return redirect()->route('reservaciones.index')
                ->with('success', 'Reservación creada con éxito.')
                ->with('reserv_estado', $reservacion_flag)
                ->with('reserv', $reservacion->numero);
        } elseif ($previousUrl == route('reservaciones.createReservacionIn')) {
            return redirect()->route('reservaciones.indexin')
                ->with('success', 'Reservación creada con éxito.')
                ->with('reserv_estado', $reservacion_flag)
                ->with('reserv', $reservacion->numero);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($reservacion_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $reservacion = reservacion::findOrFail($reservacion_id);
        if ($reservacion->estado != 'PENDIENTE') {
            return redirect()->route('dashboard')->with('nopermiso', 'Error, solo se pueden eliminar las reservaciones en estado PENDIENTE.');
        }
        $cliente = cliente::findOrFail($reservacion->cliente_id);
        $hab = habitacione::findOrFail($reservacion->habitacion_id);
        return view('reservaciones.destroy', compact('reservacion', 'cliente', 'hab'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($reservacion_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }

        $reservacion = reservacion::findOrFail($reservacion_id);
        $clientes = cliente::all()->sortBy('nombre_completo');
        $habitaciones = habitacione::all()->sortBy('numero_habitacion');
        $servicios = producto::where('del', 'N')->get();
        $facturada_flag = cabfacturareservacionsar::where('numreservacion', $reservacion->numero)->exists() ||
            cabfacturareservacioninterno::where('numreservacion', $reservacion->numero)->exists();
        $serviciosadquiridos = reservacionservicios::where('reservacion_id', $reservacion->id)->get();
        return view('reservaciones.edit', compact('facturada_flag', 'reservacion', 'serviciosadquiridos', 'servicios', 'clientes', 'habitaciones'));
    }

    public function editIn($reservacion_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }

        $reservacion = reservacion::Find($reservacion_id);
        $clientes = cliente::all()->sortBy('nombre_completo');
        $habitaciones = habitacione::all()->sortBy('numero_habitacion');
        $servicios = producto::where('del', 'N')->get();
        $facturada_flag = cabfacturareservacionsar::where('numreservacion', $reservacion->numero)->exists() ||
            cabfacturareservacioninterno::where('numreservacion', $reservacion->numero)->exists();
        $serviciosadquiridos = reservacionservicios::where('reservacion_id', $reservacion->id)->get();
        return view('reservaciones.reservacionIn.edit', compact('facturada_flag', 'reservacion', 'serviciosadquiridos', 'servicios', 'clientes', 'habitaciones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }

        $validated = $request->validate([
            'estado' => 'required|in:CONFIRMADA,PENDIENTE,CANCELADA,FINALIZADA',
            'observaciones' => 'nullable|string|max:255',
        ], [
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser uno de los siguientes: Confirmada, Pendiente, Cancelada o Finalizada.',
            'observaciones.string' => 'Las observaciones deben ser un texto.',
            'observaciones.max' => 'Las observaciones no pueden exceder los 255 caracteres.',
        ]);

        $reservacion = Reservacion::findOrFail($id);

        if ($reservacion->estado != 'FINALIZADA' && $reservacion->estado != 'CANCELADA') {
            $reservacion->salida = $request->salida;
            $reservacion->estado = $request->estado;
            $reservacion->observaciones = $request->observaciones;

            if ($reservacion->confirmada == "N" && $request->estado == "CONFIRMADA") {
                $reservacion->confirmada = "S";
            }

            $reservacion->total = $request->total;
            $reservacion->save();

            if (in_array($request->estado, ['CANCELADA', 'FINALIZADA'])) {
                $habitacion = $reservacion->habitacion;
                $habitacion->estado = 'DISPONIBLE';
                $habitacion->save();
            } else {
                $habitacion = $reservacion->habitacion;
                $habitacion->estado = 'OCUPADA';
                $habitacion->save();
            }

            // Solo procesar servicios si la reservación YA ESTÁ CONFIRMADA (antes del cambio)
            if ($reservacion->estado == 'CONFIRMADA') {
                foreach ($request->input('servicios', []) as $servicio) {
                    $seleccionado = $servicio['seleccionado'] ?? 0;
                    $productoId = $servicio['id'];
                    $cantidadEnviada = intval($servicio['cantidad'] ?? 0);
                    $subtotalEnviado = floatval($servicio['total'] ?? 0);

                    if ($seleccionado != 1 && $cantidadEnviada <= 0) {
                        continue;
                    }

                    $servicioExistente = reservacionservicios::where('reservacion_id', $reservacion->id)
                        ->where('producto_id', $productoId)
                        ->first();

                    $prod = producto::where('id', $productoId)->first();

                    if ($prod && $prod->tipo_producto == 'PRODUCTO FINAL') {
                        $lote = Lote::where('codigo_producto', $prod->codigo)
                            ->orderBy('created_at', 'asc')
                            ->first();
                    }

                    if ($servicioExistente) {
                        $diferencia = $cantidadEnviada - $servicioExistente->cantidad;

                        if ($diferencia > 0) {
                            $this->restarInventario($productoId, $diferencia, $reservacion->numero);
                            $servicioExistente->cantidad = $cantidadEnviada;
                            $servicioExistente->subtotal = $subtotalEnviado;
                            $servicioExistente->save();
                        }
                    } else {
                        $this->restarInventario($productoId, $cantidadEnviada, $reservacion->numero);
                        reservacionservicios::create([
                            'reservacion_id' => $reservacion->id,
                            'producto_id' => $productoId,
                            'cantidad' => $cantidadEnviada,
                            'subtotal' => $subtotalEnviado,
                            'del' => 'N',
                        ]);
                    }
                }
            }

            $reservacion_flag = $request->estado == "CONFIRMADA" ? true : false;

            return redirect()->route('reservaciones.index')
                ->with('success', 'Reservación modificada con éxito.')
                ->with('reserv_estado', $reservacion_flag)
                ->with('reserv', $reservacion->numero);
        } else {
            return redirect()->route('reservaciones.index')
                ->with('nopermiso', 'No se puede modificar una reservación ya finalizada o cancelada.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($reservacion_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }

        $reservacion = reservacion::findOrFail($reservacion_id);
        if ($reservacion->estado != 'PENDIENTE') {
            return redirect()->route('dashboard')->with('nopermiso', 'Error, solo se pueden eliminar las reservaciones en estado PENDIENTE.');
        }
        $reservacion->delete();
        return redirect()->route('reservaciones.index')->with('success', 'Reservación eliminada con éxito.');
    }

    public function getReservaciones()
    {
        $reservaciones = reservacion::all();

        return response()->json(['data' => $reservaciones]);
    }

    public function finishReservations()
    {
        $updated = DB::table('reservacions')
            ->where('estado', 'CONFIRMADA')
            ->whereDate('salida', Carbon::today())
            ->update(['estado' => 'FINALIZADA']);

        if ($updated) {
            return response()->json([
                'status' => 'success',
                'message' => 'Reservaciones finalizadas correctamente.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No se encontraron reservaciones para finalizar.'
            ]);
        }
    }

    public function facturaReservacionesSar($num_reservacion)
    {
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }

        $reservacion = reservacion::where('numero', $num_reservacion)->first();
        $serviciosadquiridos = reservacionservicios::where('reservacion_id', $reservacion->id)
            ->join('productos', 'reservacionservicios.producto_id', '=', 'productos.id')
            ->join('impuestos', 'productos.impuesto_id', '=', 'impuestos.id')
            ->select('reservacionservicios.*', 'productos.nombre', 'productos.precio_venta', 'impuestos.porcentaje')
            ->get();

        $cliente = cliente::findOrFail($reservacion->cliente_id);
        $habitacion = habitacione::findOrFail($reservacion->habitacion_id);

        $es_sar = cabfacturareservacionsar::where('numreservacion', $num_reservacion)->exists();
        $es_interno = cabfacturareservacioninterno::where('numreservacion', $num_reservacion)->exists();
        $existe_flag = $es_sar || $es_interno;

        if ($es_sar) {
            $factura = cabfacturareservacionsar::where('numreservacion', $num_reservacion)->first();
            $detallesfactura = detafacturareservacionsar::where('factnum', $factura->factnum)->get();
            $vista = 'facturacion.facturacionReservacionesSAR.create';
        } elseif ($es_interno) {
            $factura = cabfacturareservacioninterno::where('numreservacion', $num_reservacion)->first();
            $detallesfactura = detafacturareservacioninterno::where('cabfacturainterno_id', $factura->id)->get();
            $vista = 'facturacion.facturacionReservacionesInterno.create';
        } else {
            // Factura no registrada aún, usar vista por defecto
            $factura = '';
            $detallesfactura = '';
            $vista = 'facturacion.facturacionReservacionesSAR.create';
        }

        if ($existe_flag && $factura->anular === "S") {
            return redirect()->route('dashboard')->with('nopermiso', 'La factura seleccionada está anulada y no puede ser procesada.');
        }

        $turno = $existe_flag ? turno::where('id', $factura->turno_id)->first()->turno : '';
        $caja = $existe_flag ? caja::where('id', $factura->caja_id)->first()->numcaja : '';

        return view($vista, compact(
            'caja',
            'turno',
            'factura',
            'detallesfactura',
            'habitacion',
            'cliente',
            'reservacion',
            'serviciosadquiridos',
            'existe_flag'
        ));
    }


    public function facturaReservacionesIn($num_reservacion)
    {
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }

        $reservacion = reservacion::where('numero', $num_reservacion)->first();
        $serviciosadquiridos = reservacionservicios::where('reservacion_id', $reservacion->id)
            ->join('productos', 'reservacionservicios.producto_id', '=', 'productos.id')
            ->join('impuestos', 'productos.impuesto_id', '=', 'impuestos.id')
            ->select('reservacionservicios.*', 'productos.nombre', 'productos.precio_venta', 'impuestos.porcentaje')
            ->get();

        $cliente = cliente::findOrFail($reservacion->cliente_id);
        $habitacion = habitacione::findOrFail($reservacion->habitacion_id);

        $es_sar = cabfacturareservacionsar::where('numreservacion', $num_reservacion)->exists();
        $es_interno = cabfacturareservacioninterno::where('numreservacion', $num_reservacion)->exists();
        $existe_flag = $es_sar || $es_interno;

        if ($es_sar) {
            $factura = cabfacturareservacionsar::where('numreservacion', $num_reservacion)->first();
            $detallesfactura = detafacturareservacionsar::where('factnum', $factura->factnum)->get();
            $vista = 'facturacion.facturacionReservacionesSAR.create';
        } elseif ($es_interno) {
            $factura = cabfacturareservacioninterno::where('numreservacion', $num_reservacion)->first();
            $detallesfactura = detafacturareservacioninterno::where('cabfacturainterno_id', $factura->id)->get();
            $vista = 'facturacion.facturacionReservacionesInterno.create';
        } else {
            $factura = '';
            $detallesfactura = '';
            $vista = 'facturacion.facturacionReservacionesInterno.create';
        }
        $turno = $existe_flag ? turno::where('id', $factura->turno_id)->first()->turno : '';
        $caja = $existe_flag ? caja::where('id', $factura->caja_id)->first()->numcaja : '';

        return view($vista, compact(
            'caja',
            'turno',
            'factura',
            'detallesfactura',
            'habitacion',
            'cliente',
            'reservacion',
            'serviciosadquiridos',
            'existe_flag'
        ));
    }


    public function reservacionPorFecha()
    {
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        if ($this->rol->ver_informacion == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        return view('rpts.reservaciones.reservacionxfecha');
    }

    public function ingresosReservaciones()
    {
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        if ($this->rol->ver_informacion == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }

        return view('rpts.reservaciones.rptingresosreservaciones');
    }

    private function restarInventario($productoId, $cantidad, $num_reservacion = "")
    {
        $kardex = new KardexController();
        $prod = producto::findOrFail($productoId);
        $actuales = Lote::where('codigo_producto', $prod->codigo)->sum('cantidad');
        $nuevos = $actuales - $cantidad;
        $kardex->setKardex($num_reservacion, 'SALIDA', 'VENTA DE ' .  $prod->nombre, $prod->codigo, $actuales, 0, $cantidad, $nuevos);

        // Obtener el producto
        $producto = producto::where('id', $productoId)->first();
        if ($producto->tipo_producto == 'PRODUCTO FINAL') {
            if (!$producto) {
                throw new \Exception("Producto no encontrado.");
            }

            // Traer los lotes disponibles más antiguos primero
            $lotes = Lote::where('codigo_producto', $producto->codigo)
                ->where('cantidad', '>', 0)
                ->orderBy('created_at', 'asc') // o 'fecha_ingreso'
                ->get();

            foreach ($lotes as $lote) {
                if ($cantidad <= 0) break;

                // Si el lote tiene más de lo que necesito, solo resto parte
                if ($lote->cantidad >= $cantidad) {
                    $lote->cantidad -= $cantidad;
                    $lote->save();
                    $cantidad = 0;
                } else {
                    // Si el lote no tiene suficiente, lo dejo en 0 y sigo restando del siguiente lote
                    $cantidad -= $lote->cantidad;
                    $lote->cantidad = 0;
                    $lote->save();
                }
            }

            // Si aún queda cantidad por restar, significa que no hay suficiente stock
            if ($cantidad > 0) {
                throw new \Exception("Inventario insuficiente para el producto {$producto->nombre}.");
            }
        }
    }
}
