<?php

namespace App\Http\Controllers;

use App\Models\cabfacturareservacioninterno;
use App\Models\detafacturareservacioninterno;
use App\Models\cai;
use App\Models\configuracion;
use App\Models\habitacione;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use App\Models\infohotel;
use App\Models\reservacion;
use App\Models\reservacionservicios;
use App\Models\role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetafacturareservacioninternoController extends Controller
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
    public function imprimirFactura($id, Request $request)
    {
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $factura = cabfacturareservacioninterno::select(
            'cabfacturareservacioninternos.*',
            'detafacturareservacioninternos.*',
            'cajas.numcaja',
            'turnos.turno',
            'clientes.*',
            'productos.*',
            'cabfacturareservacioninternos.total as totalfactura',
            'cabfacturareservacioninternos.descto as descuentogeneral'
        )
            ->join('detafacturareservacioninternos', 'cabfacturareservacioninternos.id', '=', 'detafacturareservacioninternos.cabfacturainterno_id')
            ->join('cajas', 'cabfacturareservacioninternos.caja_id', '=', 'cajas.id')
            ->join('turnos', 'cabfacturareservacioninternos.turno_id', '=', 'turnos.id')
            ->join('clientes', 'cabfacturareservacioninternos.codigo_cliente', '=', 'clientes.codigo_cliente')
            ->leftJoin('productos', 'detafacturareservacioninternos.producto_id', '=', 'productos.id')
            ->where('cabfacturareservacioninternos.id', $id)
            ->get();
        $etiqueta = $request->query('etiqueta', '');
        $info = infohotel::first();
        $cai = cai::where('codigo', $factura[0]->idcai)->first();
        $tipo_factura = "ORIGINAL";
        $habitacion = habitacione::findOrFail($factura[0]->habitacion_id);
        $reservacion_id = reservacion::where('numero', $factura[0]->numreservacion)->first()->id;
        $serviciosadquiridos = reservacionservicios::select('productos.*', 'detafacturareservacioninternos.*', 'reservacionservicios.*')
            ->join('productos', 'reservacionservicios.producto_id', '=', 'productos.id')
            ->join('detafacturareservacioninternos', 'reservacionservicios.producto_id', '=', 'detafacturareservacioninternos.producto_id')
            ->where('reservacionservicios.reservacion_id', '=', $reservacion_id)
            ->where('detafacturareservacioninternos.cabfacturainterno_id',$id)
            ->get();
       // dd($serviciosadquiridos);
        $config = configuracion::where('codigo', 'TAMAN')->first();
        if ($config->valor === "CARTA") {
            $pdf = facadePdf::loadView('rpts.reservaciones.interno.rtpreservacion', [
                'etiqueta' => $etiqueta,
                'serviciosadquiridos' => $serviciosadquiridos,
                'habitacion' => $habitacion,
                'factura' => $factura,
                'tipofactura' => $tipo_factura,
                'info' => $info,
                'cai' => $cai
            ]);
            $pdf->setPaper('A4', 'portrait');
        } elseif ($config->valor === "TICKET") {
            $pdf = facadePdf::loadView('rpts.reservaciones.reportes.rptreservacionticket', [
                'etiqueta' => $etiqueta,
                'serviciosadquiridos' => $serviciosadquiridos,
                'habitacion' => $habitacion,
                'factura' => $factura,
                'tipofactura' => $tipo_factura,
                'info' => $info,
                'cai' => $cai
            ]);
             $calculatedHeight = 200 + ($factura->count() * 300); // o ajustá según tu diseño
            $customPaper = [0, 0, 200, $calculatedHeight];
            //$customPaper = [0, 0, 200, 99999];
            $pdf->setPaper($customPaper);
        }
        return $pdf->stream('factura.pdf', ['Attachment' => 0]);
    }

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
    public function show(detafacturareservacioninterno $detafacturareservacioninterno)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(detafacturareservacioninterno $detafacturareservacioninterno)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, detafacturareservacioninterno $detafacturareservacioninterno)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(detafacturareservacioninterno $detafacturareservacioninterno)
    {
        //
    }
}
