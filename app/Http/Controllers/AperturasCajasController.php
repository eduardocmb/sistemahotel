<?php

namespace App\Http\Controllers;

use App\Models\aperturasCajas;
use App\Models\cai;
use App\Models\caja;
use App\Models\cierresCaja;
use App\Models\correlativo;
use App\Models\role;
use App\Models\turno;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AperturasCajasController extends Controller
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
        $cajas = caja::where('del', 'N')->get();
        $turnos = turno::where('del', 'N')->get();
        correlativo::firstOrCreate([
            'codigo' => 'APER',
            'description' => 'Correlativo de Aperturas',
        ], [
            'last' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return view('cajas.aperturas.index', compact('cajas', 'turnos'));
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
        $nextCodigo = $correlativoController->generateClientCode('APER', 'correlativos', 'APE');
        $validated = $request->validate([
            'caja_id' => 'required|exists:cajas,id',
            'turno_id' => 'required|exists:turnos,id',
            'fondoinicial' => 'nullable|numeric|min:0',
            'codigo' => 'required|unique:aperturas_cajas,codigo_apertura|max:9',
        ], [
            'caja_id.required' => 'Debe seleccionar una caja.',
            'caja_id.exists' => 'La caja seleccionada no es válida.',
            'turno_id.required' => 'Debe seleccionar un turno.',
            'turno_id.exists' => 'El turno seleccionado no es válido.',
            'fondoinicial.numeric' => 'El fondo inicial debe ser un número.',
            'fondoinicial.min' => 'El fondo inicial debe ser mayor o igual a 0.',
            'codigo.required' => 'El código de apertura es obligatorio.',
            'codigo.unique' => 'El código de apertura ya existe.',
            'codigo.max' => 'El código de apertura no debe superar los 9 caracteres.',
        ]);

        // Validar que la caja no esté abierta por otro usuario
        $cajaAbierta = aperturasCajas::where('fecha', Carbon::today())
            ->where('caja_id', $request->caja_id)
            ->where('estado', 'ABIERTA')
            ->first();

        if ($cajaAbierta) {
            return redirect()->back()->with('error', 'La caja seleccionada ya está abierta por otro usuario. No puede abrir la misma caja dos veces.');
        }


        $apertura = aperturasCajas::where('fecha', Carbon::today())
            ->where('user_id', Auth::user()->id)
            ->where('estado', 'ABIERTA')
            ->first();

        if ($apertura) {
            return redirect()->back()->with('error', 'El usuario ya tiene una apertura de caja abierta para la fecha de hoy, realice el cierre e intente de nuevo.');
        }

        $aperturaTurno = aperturasCajas::where('fecha', Carbon::today())
            ->where('user_id', Auth::user()->id)
            ->where('turno_id', $request->turno_id)
            ->where('estado', 'ABIERTA')
            ->first();

        if ($aperturaTurno) {
            return redirect()->back()->with('error', 'El usuario ya está asignado al turno seleccionado para esta fecha.');
        }

        $aperturasPendientes = aperturasCajas::where('user_id', Auth::user()->id)
            ->where('estado', 'ABIERTA')
            ->get();

        if ($aperturasPendientes->count() > 0) {
            $fechas = $aperturasPendientes->map(function ($apertura) {
                return Carbon::parse($apertura->fecha)->format('d/m/Y');
            })->implode(', ');

            return redirect()->back()->with('error', 'El usuario tiene aperturas de caja abiertas en las siguientes fechas: ' . $fechas . '. Por favor, cierre esas aperturas antes de proceder.');
        }

        $correlativo = correlativo::where('codigo', 'APER')->first();
        if ($correlativo) {
            $correlativo->increment('last', 1);
        }


        $aperturaCaja = new aperturasCajas();
        $aperturaCaja->codigo_apertura = $nextCodigo->getData()->codigo;
        $aperturaCaja->fecha = Carbon::today();
        $aperturaCaja->turno_id = $request->turno_id;
        $aperturaCaja->caja_id = $request->caja_id;
        $aperturaCaja->fondoinicial = $request->fondoinicial == null ? 0 : $request->fondoinicial;
        $aperturaCaja->user_id = Auth::id();
        $aperturaCaja->estado = "ABIERTA";
        $aperturaCaja->save();
        return redirect()->route('aperturas_cajas.index')->with('success', 'Caja abierta correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(aperturasCajas $aperturasCajas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(aperturasCajas $aperturasCajas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, aperturasCajas $aperturasCajas)
    {
        //
    }

    /**
     * Remove the specified resource from storag e.
     */
    public function destroy($apertura_id)
{
    if ($this->rol->eliminar == "N") {
        return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
    }

    $apertura = aperturasCajas::findOrFail($apertura_id);

    if ($apertura->user_id != Auth::id()) {
        return redirect()->route('aperturas_cajas.index')->with('error', 'No puedes eliminar la apertura de caja de otro usuario.');
    }

    $apertura->delete();

    return redirect()->route('aperturas_cajas.index')->with('success', 'Caja eliminada correctamente.');
}


    public function getCajasAbiertasxFechayTurno($turno_id, $fecha)
    {
        $apertura = aperturasCajas::where('fecha', $fecha)
            ->where('user_id', Auth::user()->id)
            ->where('turno_id', $turno_id)
            ->where('estado', 'ABIERTA')
            ->first();

        return response()->json([
            'apertura' => $apertura == null ? '' : $apertura,
        ]);
    }

    public function verificarAperturaCaja()
    {
        $userId = Auth::user()->id;
        $aperturaAbierta = aperturasCajas::where('user_id', $userId)
            ->where('estado', 'ABIERTA')
            ->where('fecha', Carbon::today())
            ->first();

        if ($aperturaAbierta) {
            $turno = turno::find($aperturaAbierta->turno_id);
            $caja = caja::find($aperturaAbierta->caja_id);
        }

        return response()->json([
            'apertura_abierta' => (bool) $aperturaAbierta,
            'codigo_apertura' => $aperturaAbierta->codigo_apertura ?? '',
            'turno_id' => $aperturaAbierta->turno_id ?? '',
            'turno' => $aperturaAbierta ? $turno->turno ?? '' : '',
            'caja_id' => $aperturaAbierta->caja_id ?? '',
            'caja' => $aperturaAbierta ? $caja->numcaja ?? '' : '',
        ]);
    }

    public function reporteIngresosXDia()
    {
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        if ($this->rol->reimprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        return view('rpts.finanzas.ingresospordia');
    }
    public function reporteIngresosXMes()
    {
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        if ($this->rol->reimprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        return view('rpts.finanzas.ingresospormes');
    }
}
