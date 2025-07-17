<?php

namespace App\Http\Controllers;

use App\Models\cabfacturareservacionsar;
use App\Models\cai;
use App\Models\configuracion;
use App\Models\detafacturareservacionsar;
use App\Models\habitacione;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use App\Models\infohotel;
use App\Models\reservacion;
use App\Models\reservacionservicios;
use App\Models\role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetafacturareservacionsarController extends Controller
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

    public function imprimirFactura($num_factura, Request $request)
    {
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $factura = cabfacturareservacionsar::select(
            'cabfacturareservacionsars.*',
            'detafacturareservacionsars.*',
            'cajas.numcaja',
            'turnos.turno',
            'clientes.*',
            'productos.*',
            'cabfacturareservacionsars.total as totalfactura',
            'cabfacturareservacionsars.descto as descuentogeneral'
        )
            ->join('detafacturareservacionsars', 'cabfacturareservacionsars.factnum', '=', 'detafacturareservacionsars.factnum')
            ->join('cajas', 'cabfacturareservacionsars.caja_id', '=', 'cajas.id')
            ->join('turnos', 'cabfacturareservacionsars.turno_id', '=', 'turnos.id')
            ->join('clientes', 'cabfacturareservacionsars.codigo_cliente', '=', 'clientes.codigo_cliente')
            ->leftJoin('productos', 'detafacturareservacionsars.producto_id', '=', 'productos.id')
            ->where('cabfacturareservacionsars.factnum', $num_factura)
            ->get();

        $etiqueta = $request->query('etiqueta', '');
        $info = infohotel::first();
        $cai = cai::where('codigo', $factura[0]->idcai)->first();
        $tipo_factura = "ORIGINAL";
        $habitacion = habitacione::findOrFail($factura[0]->habitacion_id);
        $reservacion_id = reservacion::where('numero', $factura[0]->numreservacion)->first()->id;
        $serviciosadquiridos = reservacionservicios::select('productos.*', 'detafacturareservacionsars.*', 'reservacionservicios.*')
        ->join('productos', 'productos.id', '=', 'reservacionservicios.producto_id')
        ->join('detafacturareservacionsars', 'detafacturareservacionsars.producto_id', '=', 'reservacionservicios.producto_id')
        ->where('reservacionservicios.reservacion_id', '=', $reservacion_id)
        ->where('detafacturareservacionsars.factnum', $num_factura)
        ->get();
      //  dd($serviciosadquiridos);
        $config = configuracion::where('codigo', 'TAMAN')->first();
        if ($config->valor === "CARTA") {
            $pdf = facadePdf::loadView('rpts.reservaciones.rtpreservacion', ['etiqueta' => $etiqueta, 'serviciosadquiridos' => $serviciosadquiridos, 'habitacion' => $habitacion, 'factura' => $factura, 'tipofactura' => $tipo_factura, 'info' => $info, 'cai' => $cai]);
            $pdf->setPaper('A4', 'portrait');
        } elseif ($config->valor === "TICKET") {
             $calculatedHeight = 200 + ($factura->count() * 300); // o ajustá según tu diseño
            $customPaper = [0, 0, 200, $calculatedHeight];
            // $customPaper = [0, 0, 200, 99999];
            $pdf = facadePdf::loadView('rpts.reservaciones.reportes.rptreservacionticket', [
                'etiqueta' => $etiqueta,
                'serviciosadquiridos' => $serviciosadquiridos,
                'habitacion' => $habitacion,
                'factura' => $factura,
                'tipofactura' => $tipo_factura,
                'info' => $info,
                'cai' => $cai
            ]);
            $pdf->setPaper($customPaper);
        }
        return $pdf->stream('factura.pdf', ['Attachment' => 0]);
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
    public function show(detafacturareservacionsar $detafacturareservacionsar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(detafacturareservacionsar $detafacturareservacionsar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, detafacturareservacionsar $detafacturareservacionsar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(detafacturareservacionsar $detafacturareservacionsar)
    {
        //
    }
}
