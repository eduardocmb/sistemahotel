<?php

namespace App\Http\Controllers;

use App\Models\correlativo;
use App\Models\role;
use App\Models\turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TurnoController extends Controller
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
        return view('turnos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        correlativo::firstOrCreate([
            'codigo' => 'TURN',
            'description' => 'Correlativo de Turnos',
        ], [
            'last' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $validated = $request->validate([
            'codigo' => 'required|string|unique:turnos,codigo|max:9',
            'turno' => 'required|string|max:50',
        ], [
            'codigo.required' => 'El código del turno es obligatorio.',
            'codigo.string' => 'El código debe ser una cadena de texto.',
            'codigo.unique' => 'Ya existe un turno con ese código.',
            'codigo.max' => 'El código debe tener como máximo 9 caracteres.',
            'turno.required' => 'El nombre del turno es obligatorio.',
            'turno.string' => 'El nombre del turno debe ser una cadena de texto.',
            'turno.max' => 'El nombre del turno debe tener como máximo 50 caracteres.',
        ]);

        if ($request->has('chkGenerarAuto')) {
            $correlativo = correlativo::where('codigo', 'TURN')->first();
            if ($correlativo) {
                $correlativo->increment('last', 1);
            }
        }
            $turno = new turno();
            $turno->codigo =$request->codigo;
            $turno->turno = $request->turno;
            $turno->save();
            return redirect()->route('turnos.index')->with('success', 'Turno creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(turno $turno)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($turno_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $turno = turno::findOrFail($turno_id);
        return view('turnos.edit', compact('turno'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $turno_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $validated = $request->validate([
            'turno' => 'required|string|max:50',
        ], [
            'turno.required' => 'El nombre del turno es obligatorio.',
            'turno.string' => 'El nombre del turno debe ser una cadena de texto.',
            'turno.max' => 'El nombre del turno debe tener como máximo 50 caracteres.',
        ]);


            $turno = turno::findOrFail($turno_id);
            $turno->turno = $request->turno;
            $turno->save();
            return redirect()->route('turnos.index')->with('success', 'Turno actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($turno_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }

        $turno = turno::findOrFail($turno_id);
        $turno->del = "S";
        $papelera = new PapeleraController();
        $papelera->agregarAPapelera($turno->turno, "turnos", $turno_id, "id");
        $turno->save();
        return redirect()->route('turnos.index')->with('success', 'Turno eliminado exitosamente.');
    }
}
