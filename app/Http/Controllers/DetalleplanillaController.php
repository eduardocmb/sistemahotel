<?php

namespace App\Http\Controllers;

use App\Models\cabeceraplanilla;
use App\Models\configuracion;
use App\Models\correlativo;
use App\Models\departamento;
use App\Models\detalleplanilla;
use App\Models\empleado;
use App\Models\infohotel;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use App\Models\role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetalleplanillaController extends Controller
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
        correlativo::firstOrCreate([
            'codigo' => 'PLAN',
            'description' => 'Correlativo de Planillas de Empleados',
        ], [
            'last' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return view('planillas.indexe');
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
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $correlativoController = new CorrelativoController();
        $nextCodigo = $correlativoController->generateClientCode('PLAN', 'correlativos');
        $cabecera = new cabeceraplanilla();
        $cabecera->codigo = $nextCodigo->getData()->codigo;
        $cabecera->empleado_id = $request->empleado_id;
        $cabecera->fecha_inicio = $request->fecha_inicio;
        $cabecera->fecha_final = $request->fecha_final;
        $cabecera->total = $request->total_planilla;
        $cabecera->save();

        foreach ($request->motivo as $index => $motivo) {
            $detalleplanilla = new detalleplanilla();
            $detalleplanilla->codigo_planilla =  $cabecera->codigo;
            $detalleplanilla->motivo = $request->motivo[$index];
            $detalleplanilla->cantidad = $request->cantidad[$index];
            $detalleplanilla->devengado = isset($request->devengado[$index]) && is_numeric($request->devengado[$index]) ? $request->devengado[$index] : 0;
            $detalleplanilla->deducido = isset($request->deducido[$index]) && is_numeric($request->deducido[$index]) ? $request->deducido[$index] : 0;
            $detalleplanilla->save();
        }

        $correlativo = correlativo::where('codigo', 'PLAN')->first();
        if ($correlativo) {
            $correlativo->increment('last', 1);
        }

        $imprimir_flag = configuracion::where('codigo', 'PRFAC')->first()?->valor == "S" ? true : false;
        if ($imprimir_flag) {
            return redirect()->route('rpt.imprimirPlanilla', $cabecera->codigo)->with('etiqueta', 'ORIGINAL');
        } else {
            return redirect()->route('planilla.index')->with('success', 'Factura creada exitosamente');
        }
    }

    public function imprimirPlanilla($codigo)
    {
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        if ($this->rol->reimprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $info = infohotel::first();
        $planilla = cabeceraplanilla::where('codigo', $codigo)->first();
        $detalleplanilla = detalleplanilla::where('codigo_planilla', $codigo)->get();
        $total_devengado = 0;
        $total_deducido = 0;
        foreach($detalleplanilla as $deta){
            $total_deducido += floatval($deta->deducido);
            $total_devengado+= floatval($deta->devengado);
        }
        $empleado = empleado::findOrFail($planilla->empleado_id);
        $departamento = departamento::findOrFail($empleado->departamento_id);
        $pdf = facadePdf::loadView('rpts.planilla.index', [
            'info' => $info,
            'planilla' => $planilla,
            'detalleplanilla' => $detalleplanilla,
            'empleado' => $empleado,
            'departamento' => $departamento,
            'total_devengado' => $total_devengado,
            'total_deducido'=>$total_deducido
        ]);

        $config = configuracion::where('codigo', 'TAMAN')->first();

        if ($config->valor === "MEDIA CARTA") {
            $pdf->setPaper([0, 0, 139.7, 215.9], 'portrait');
        } else if ($config->valor === "TICKET") {
            $pdf->setPaper([0, 0, 80, 200], 'portrait');
        } else {
            $pdf->setPaper('A4', 'portrait');
        }

        return $pdf->stream('planilla.pdf', ['Attachment' => 0]);
    }

    /**
     * Display the specified resource.
     */
    public function show(detalleplanilla $detalleplanilla)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(detalleplanilla $detalleplanilla)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, detalleplanilla $detalleplanilla)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(detalleplanilla $detalleplanilla)
    {
        //
    }
}
