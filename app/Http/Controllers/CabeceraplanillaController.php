<?php

namespace App\Http\Controllers;

use App\Models\cabeceraplanilla;
use App\Models\correlativo;
use App\Models\deduccion;
use App\Models\detalleplanilla;
use App\Models\empleado;
use App\Models\role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CabeceraplanillaController extends Controller
{
    protected $rol;

    public function __construct()
    {
        $this->rol = role::where('codigo', Auth::user()->idrol)->first();
    }

    public function index()
    {

    }

    public function index2()
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
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $deducciones = deduccion::where('activo', 'S')->get();
        $empleados = empleado::where('estado', 'ACTIVO')->where('del', 'N')->get();
        return view('planillas.create', compact('deducciones', 'empleados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show($planilla_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        return view('planillas.destroy');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($planilla_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        return view('planillas.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $planilla_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($planilla_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
    }
}
