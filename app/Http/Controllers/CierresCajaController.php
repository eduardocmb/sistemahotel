<?php

namespace App\Http\Controllers;

use App\Models\aperturasCajas;
use App\Models\cabeceracompraproducto;
use App\Models\cabecerafactura;
use App\Models\cabfacturainterno;
use App\Models\cabfacturareservacioninterno;
use App\Models\cabfacturareservacionsar;
use App\Models\cierresCaja;
use App\Models\compraproducto;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use App\Models\configuracion;
use App\Models\gasto;
use App\Models\infohotel;
use App\Models\role;
use App\Models\turno;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CierresCajaController extends Controller
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
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $turnos = turno::where('del', 'N')->get();
        return view('cajas.cierres.index', compact('turnos'));
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
        if ($this->rol->finanzas == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }

        $ventasEfectivo = 0;
        $ventasTarjeta = 0;
        $transferencias = 0;
        $egresos = 0;
        $totalVentas = 0;
        $caja = 0;
        $apertura_id = aperturasCajas::where('codigo_apertura', $request->ap_num)->first()->id;

        $ventasEfectivo += cabecerafactura::where('anular', 'N')
            ->where('apnum', $request->ap_num)
            ->sum(DB::raw('efectivo - cambio'));

        $ventasEfectivo += cabfacturainterno::where('anular', 'N')
            ->where('apnum', $request->ap_num)
            ->whereDate('fecha', $request->fecha)
            ->where('turno_id', $request->turno)
            ->where('usuario', Auth::user()->username)
            ->sum(DB::raw('efectivo - cambio'));

        $ventasEfectivo += cabfacturareservacioninterno::where('anular', 'N')
            ->where('apnum', $request->ap_num)
            ->whereDate('fecha', $request->fecha)
            ->where('turno_id', $request->turno)
            ->where('usuario', Auth::user()->username)
            ->sum(DB::raw('efectivo - cambio'));

        $ventasEfectivo += cabfacturareservacionsar::where('anular', 'N')
            ->where('apnum', $request->ap_num)
            ->whereDate('fecha', $request->fecha)
            ->where('turno_id', $request->turno)
            ->where('usuario', Auth::user()->username)
            ->sum(DB::raw('efectivo - cambio'));


        $ventasTarjeta += cabecerafactura::where('anular', 'N')
            ->where('apnum', $request->ap_num)
            ->whereDate('fecha', $request->fecha)
            ->where('turno_id', $request->turno)
            ->where('usuario', Auth::user()->username)
            ->sum('tarjeta');

        $ventasTarjeta += cabfacturainterno::where('anular', 'N')
            ->where('apnum', $request->ap_num)
            ->whereDate('fecha', $request->fecha)
            ->where('turno_id', $request->turno)
            ->where('usuario', Auth::user()->username)
            ->sum('tarjeta');

        $ventasTarjeta += cabfacturareservacioninterno::where('anular', 'N')
            ->where('apnum', $request->ap_num)
            ->whereDate('fecha', $request->fecha)
            ->where('turno_id', $request->turno)
            ->where('usuario', Auth::user()->username)
            ->sum('tarjeta');

        $ventasTarjeta += cabfacturareservacionsar::where('anular', 'N')
            ->where('apnum', $request->ap_num)
            ->whereDate('fecha', $request->fecha)
            ->where('turno_id', $request->turno)
            ->where('usuario', Auth::user()->username)
            ->sum('tarjeta');

        $transferencias += cabecerafactura::where('anular', 'N')
            ->where('apnum', $request->ap_num)
            ->whereDate('fecha', $request->fecha)
            ->where('turno_id', $request->turno)
            ->where('usuario', Auth::user()->username)
            ->sum('transferencia');

        $transferencias += cabfacturainterno::where('anular', 'N')
            ->where('apnum', $request->ap_num)
            ->whereDate('fecha', $request->fecha)
            ->where('turno_id', $request->turno)
            ->where('usuario', Auth::user()->username)
            ->sum('transferencia');

        $transferencias += cabfacturareservacionsar::where('anular', 'N')
            ->where('apnum', $request->ap_num)
            ->whereDate('fecha', $request->fecha)
            ->where('turno_id', $request->turno)
            ->where('usuario', Auth::user()->username)
            ->sum('transferencia');

        $transferencias += cabfacturareservacioninterno::where('anular', 'N')
            ->where('apnum', $request->ap_num)
            ->whereDate('fecha', $request->fecha)
            ->where('turno_id', $request->turno)
            ->where('usuario', Auth::user()->username)
            ->sum('transferencia');


        $egresos = 0;
        $gastos = gasto::where('fecha', $request->fecha)->where('apnum', $request->ap_num)->get();
        foreach ($gastos as $gasto) {
            $egresos -= floatval($gasto->monto);
        }

        $totalVentas += cabecerafactura::where('anular', 'N')
            ->where('apnum', $request->ap_num)
            ->sum('total');

        $totalVentas += cabfacturainterno::where('anular', 'N')
            ->where('apnum', $request->ap_num)
            ->whereDate('fecha', $request->fecha)
            ->where('turno_id', $request->turno)
            ->where('usuario', Auth::user()->username)
            ->sum('total');

        $totalVentas += cabfacturareservacionsar::where('anular', 'N')
            ->where('apnum', $request->ap_num)
            ->whereDate('fecha', $request->fecha)
            ->where('turno_id', $request->turno)
            ->where('usuario', Auth::user()->username)
            ->sum('total');

        $totalVentas += cabfacturareservacioninterno::where('anular', 'N')
            ->where('apnum', $request->ap_num)
            ->whereDate('fecha', $request->fecha)
            ->where('turno_id', $request->turno)
            ->where('usuario', Auth::user()->username)
            ->sum('total');

        $caja = aperturasCajas::where('codigo_apertura', $request->ap_num)
            ->sum('fondoinicial');

        $grantotal = floatval($request->grantotal);

        $cajaEsperada = $caja + $ventasEfectivo + $egresos;
        $diferencia = $grantotal - $cajaEsperada;

        if ($diferencia < 0) {
            $deduccion = "FALTANTE";
        } elseif ($diferencia > 0) {
            $deduccion = "SOBRANTE";
        } else {
            $deduccion = "CUADRADA";
        }

        $apertura = aperturasCajas::where('codigo_apertura', $request->ap_num)->first();
        $apertura->estado = "CERRADA";
        $apertura->save();

        $retirar = $ventasTarjeta + $transferencias;
        $cierre = new cierresCaja();
        $cierre->aperturas_caja_id = $apertura_id;
        $cierre->fondo = $request->fondo;
        $cierre->ventasefe = $ventasEfectivo;
        $cierre->ventaspos = $ventasTarjeta;
        $cierre->transferencias = $transferencias;
        $cierre->totventas = $totalVentas;
        $cierre->rec_ctas = 0;
        $cierre->caja = $grantotal;
        $cierre->egresos = $egresos;
        $cierre->diferencia = $diferencia;
        $cierre->observ = $deduccion;
        $cierre->retirar = $retirar;
        $cierre->save();

        $imprimir_flag = configuracion::where('codigo', 'PRFAC')->first()?->valor == "S" ? true : false;
        if ($imprimir_flag) {
            return redirect()->route('rpt.cierreCaja', $apertura_id);
        } else {
            return redirect()->route('cierre_cajas.index')->with('success', 'Caja cerrada correctamente.');
        }

    }

    public function imprimirCierre($apnum){
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $cierre = cierresCaja::where('aperturas_caja_id', $apnum)->first();
        $ape_num = aperturasCajas::findOrFail($apnum);
        $user = User::findOrFail($ape_num->user_id);
        $info = infohotel::first();
        $pdf = facadePdf::loadView('rpts.caja.cierrecaja', ['usuario'=>$user,'info' => $info, 'cierre'=>$cierre, 'apnum'=>$ape_num]);
      //  $pdf->setPaper('A4', 'portrait');
        $pdf->setPaper([0, 0, 200, 600], 'portrait');
        return $pdf->stream('cierre_de_caja.pdf', ['Attachment' => 0]);
    }

    /**
     * Display the specified resource.
     */
    public function show(cierresCaja $cierresCaja)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(cierresCaja $cierresCaja)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, cierresCaja $cierresCaja)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cierresCaja $cierresCaja)
    {
        //
    }
}
