<?php

namespace App\Http\Controllers;

use App\Models\aperturasCajas;
use App\Models\cabfacturareservacioninterno;
use App\Models\cabfacturareservacionsar;
use Illuminate\Http\Request;
use App\Models\cai;
use App\Models\cliente;
use App\Models\configuracion;
use App\Models\correlativo;
use App\Models\detafacturareservacioninterno;
use App\Models\detallefacturainterno;
use App\Models\habitacione;
use App\Models\producto;
use App\Models\role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Luecano\NumeroALetras\NumeroALetras;

class CabfacturareservacioninternoController extends Controller
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
        //
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
        $facturasConReservacionInterno = cabfacturareservacioninterno::where('numreservacion', $request->num_reservacion)->first();
        $facturasConReservacion = cabfacturareservacionsar::where('numreservacion', $request->num_reservacion)->first();

        if($facturasConReservacionInterno || $facturasConReservacion){
            return redirect()->back()->with('error', 'Ya existe una factura asociada a esta reservación.');
        }
        //dd($request);
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        // dd($request);
        $aLetras = new NumeroALetras();
        //dd($aLetras->toMoney('12345.98', 2, "Lempiras", "Centavos"));

        $correlativoController = new CorrelativoController();
        $nextCodigo = $correlativoController->getNextCorrelativoFact('SAR');
        $cabfactura = new cabfacturareservacioninterno();
        $cabfactura->factnum = $nextCodigo;
        $cabfactura->apnum = aperturasCajas::where('fecha', Carbon::today())
            ->where('user_id', Auth::user()->id)
            ->where('turno_id', $request->turno)
            ->where('caja_id', $request->caja)
            ->where('estado', 'ABIERTA')
            ->first()->codigo_apertura;
        $cabfactura->numreservacion = $request->num_reservacion;
        $cabfactura->fechaentrada = $request->reservacion_entrada;
        $cabfactura->fechasalida = $request->reservacion_salida;
        $cabfactura->fecha = Carbon::today();
        $cabfactura->idcai = cai::where('estado', 'ACTIVO')->first()->codigo;
        $cabfactura->pago = $request->tipopago;
        $cabfactura->turno_id = $request->turno;
        $cabfactura->caja_id = $request->caja;
        $cabfactura->codigo_cliente = $request->codigocliente;
        $cabfactura->cliente = cliente::where('codigo_cliente', $request->codigocliente)->first()->nombre_completo;
        $cabfactura->rtn = $request->rtn;
        $cabfactura->impoexon = $request->importeExonerado;
        $cabfactura->impoexen = $request->importeExento;
        $cabfactura->impograv15 = $request->importeGrav15;
        $cabfactura->impograv18 = $request->importeGrav18;
        $cabfactura->isv15 = $request->isv15;
        $cabfactura->isv18 = $request->isv18;
        $cabfactura->descto = $request->descuento_general === null ? '0' : $request->descuento_general;
        $cabfactura->total = $request->total;
        $cabfactura->enletras = $aLetras->toMoney($request->total, 2, "Lempiras", "Centavos");
        $cabfactura->usuario = Auth::user()->username;
        // Asignar métodos de pago
        if ($request->tipopago == "EFECTIVO") {
            $cabfactura->efectivo = $request->montorecibido;
            $cabfactura->tarjeta = "0.00";
            $cabfactura->transferencia = "0.00";
            $cabfactura->cambio = $request->cambioadar;
        } elseif ($request->tipopago == "TARJETA") {
            $cabfactura->tarjeta = $request->total;
            $cabfactura->efectivo = "0.00";
            $cabfactura->transferencia = "0.00";
            $cabfactura->cambio = "0.00";
        } elseif ($request->tipopago == "TRANSFERENCIA") {
            $cabfactura->transferencia = $request->total;
            $cabfactura->efectivo = "0.00";
            $cabfactura->tarjeta = "0.00";
            $cabfactura->cambio = "0.00";
        }
        $cabfactura->idvendedor = "NA";
        $cabfactura->comision = "0";
        $cabfactura->liq = "N";
        $cabfactura->anular = "N";
        $cabfactura->save();
        $servicios = $request->servicio_id;
        $cantidades = $request->cantidad;
        $dias = $request->dias_estadia;
        $descuentos = $request->descuento;
        $indexes = $request->index;
        $servicio_id = $servicios[1] ?? null;
        $servicio = $servicios === null ? '' : producto::find($servicio_id);

        if ($servicio) {
            $detalle = new detafacturareservacioninterno();
            $detalle->factnum = $cabfactura->factnum;
            $detalle->cabfacturainterno_id = $cabfactura->id;
            $detalle->pos = 1;
            $detalle->habitacion_id = $request->habitacion_id;
            $detalle->producto_id = $servicio_id;
            $detalle->descripcion = "Habitacion N°: " . habitacione::findOrFail($request->habitacion_id)->numero_habitacion;
            $detalle->cant = "----";
            $detalle->dias = $dias;
            $detalle->precio = habitacione::findOrFail($request->habitacion_id)->precio_diario;
            $detalle->descto = $descuentos[1] ?? 0.00;
            $detalle->total = ($detalle->precio * $dias) - $detalle->descto;
            $detalle->comision = 0.00;
            // dd($detalle->total);
            $detalle->save();
        } else {
            $detalle = new detafacturareservacioninterno();
            $detalle->factnum = $cabfactura->factnum;
            $detalle->cabfacturainterno_id = $cabfactura->id;
            $detalle->pos = 1;
            $detalle->habitacion_id = $request->habitacion_id;
            $detalle->producto_id = $servicio_id;
            $detalle->descripcion = "Habitacion N°: " . habitacione::findOrFail($request->habitacion_id)->numero_habitacion;
            $detalle->cant = "----";
            $detalle->dias = $dias;
            $detalle->precio = habitacione::findOrFail($request->habitacion_id)->precio_diario;
            $detalle->descto = $descuentos[1] ?? 0.00;
            $detalle->total = ($detalle->precio * $dias) - $detalle->descto;
            $detalle->comision = 0.00;
            // dd($detalle->total);
            $detalle->save();
        }

        if ($servicios) {
            foreach ($servicios as $index => $servicio_id) {
                if ($index == 0) {
                    continue;
                }

                $servicio = producto::find($servicio_id);
                $detalle = new detafacturareservacioninterno();
                $detalle->cabfacturainterno_id = $cabfactura->id;
                $detalle->factnum = $cabfactura->factnum;
                $detalle->pos = $index + 1;
                $detalle->habitacion_id = $request->habitacion_id;
                $detalle->producto_id = $servicio_id;
                $detalle->descripcion = $servicio->nombre;
                $detalle->cant = $cantidades[$index] ?? 0;
                $detalle->dias = "----";
                $detalle->precio = $servicio->precio_venta;
                $detalle->descto = $descuentos[$index] ?? 0.00;
                $detalle->total = ($detalle->precio * $detalle->cant) - $detalle->descto;
                $detalle->comision = 0.00;

                $detalle->save();
            }
        }

        $imprimir_flag = configuracion::where('codigo', 'PRFAC')->first()?->valor == "S" ? true : false;
        if ($imprimir_flag) {
            return redirect()->route('facturas.rptfacturareservacionIn', $cabfactura->id)->with('etiqueta', 'ORIGINAL');
        } else {
            return redirect()->back()->with('success', 'Factura creada exitosamente');
        }
    }

    public function anularReservacionInterno($id_factura)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $factura = cabfacturareservacioninterno::where('id', $id_factura)->first();
        $factura->anular = 'S';
        $factura->save();
        return redirect()->route('facturacionesin.index')->with('success', 'Factura Anulada Exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(cabfacturareservacioninterno $cabfacturareservacioninterno)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(cabfacturareservacioninterno $cabfacturareservacioninterno)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, cabfacturareservacioninterno $cabfacturareservacioninterno)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cabfacturareservacioninterno $cabfacturareservacioninterno)
    {
        //
    }
}
