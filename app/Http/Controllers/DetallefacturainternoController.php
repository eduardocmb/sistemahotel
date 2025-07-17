<?php

namespace App\Http\Controllers;

use App\Models\cabfacturainterno;
use App\Models\detallefacturainterno;
use Illuminate\Http\Request;
use App\Models\cai;
use App\Models\configuracion;
use App\Models\infohotel;
use App\Models\role;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\Auth;

class DetallefacturainternoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $rol;

    public function __construct()
    {
        $this->rol = role::where('codigo', Auth::user()->idrol)->first();
    }

    public function index() {}
    public function imprimirFactura($id, Request $request)
    {
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        if ($this->rol->reimprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $factura = cabfacturainterno::select(
            'cabfacturainternos.*',
            'detallefacturainternos.*',
            'cajas.numcaja',
            'turnos.turno',
            'clientes.*',
            'productos.*',
            'cabfacturainternos.total as totalfactura',
            'cabfacturainternos.descto as descuento_general'
        )
            ->join('detallefacturainternos', 'cabfacturainternos.id', '=', 'detallefacturainternos.cabfacturainterno_id')
            ->join('cajas', 'cabfacturainternos.caja_id', '=', 'cajas.id')
            ->join('turnos', 'cabfacturainternos.turno_id', '=', 'turnos.id')
            ->join('clientes', 'cabfacturainternos.codigo_cliente', '=', 'clientes.codigo_cliente')
            ->join('productos', 'detallefacturainternos.codproducto', '=', 'productos.codigo')
            ->where('cabfacturainternos.id', $id)
            ->get();
        $info = infohotel::first();
        $cai = cai::where('codigo', $factura[0]->idcai)->first();
        $tipo_factura = session('etiqueta') ?? $request->query('etiqueta', '');
        $config = configuracion::where('codigo', 'TAMAN')->first();
        if ($config->valor === "CARTA") {
            $pdf = FacadePdf::loadView('rpts.facturacion.rptfacturacionsar', ['factura' => $factura, 'tipofactura' => $tipo_factura, 'info' => $info, 'cai' => $cai]);
            $pdf->setPaper('A4', 'portrait');
        } elseif ($config->valor === "TICKET") {
            $calculatedHeight = 200 + ($factura->count() * 200); // o ajustá según tu diseño
            $customPaper = [0, 0, 200, $calculatedHeight];
            // $customPaper = [0, 0, 200, 99999];
            $pdf = FacadePdf::loadView('rpts.facturacion.rptfacturacionsarticket', ['factura' => $factura, 'tipofactura' => $tipo_factura, 'info' => $info, 'cai' => $cai]);
            $pdf->setPaper($customPaper);
        }
        return $pdf->stream('factura.pdf', ['Attachment' => 0]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if ($this->rol->ver_informacion == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        return view('facturacion.facturacionReservacionesSAR.factexistentes');
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
    public function show($detallefacturainterno) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(detallefacturainterno $detallefacturainterno) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, detallefacturainterno $detallefacturainterno)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(detallefacturainterno $detallefacturainterno)
    {
        //
    }
}
