<?php

namespace App\Http\Controllers;

use App\Models\correlativo;
use App\Models\departamento;
use App\Models\role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartamentoController extends Controller
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
            'codigo' => 'DPRT',
            'description' => 'Correlativo de Departamentos de Trabajo',
        ], [
            'last' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return view('empleados.departamentos.index');
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
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $request->validate([
            'codigo_departamento' => 'required|string|max:255',
            'departamento' => 'required|string|max:255',
        ], [
            'codigo_departamento.required' => 'El código del departamento es obligatorio.',
            'codigo_departamento.string' => 'El código del departamento debe ser una cadena de texto.',
            'codigo_departamento.max' => 'El código del departamento no debe superar los 255 caracteres.',
            'departamento.required' => 'El nombre del departamento es obligatorio.',
            'departamento.string' => 'El nombre del departamento debe ser una cadena de texto.',
            'departamento.max' => 'El nombre del departamento no debe superar los 255 caracteres.',
        ]);

        $departamento = new departamento();
        $departamento->codigo = $request->codigo_departamento;
        $departamento->departamento = strtoupper($request->departamento);
        $departamento->save();

        $correlativo = correlativo::where('codigo', 'DPRT')->first();
        if ($correlativo) {
            $correlativo->increment('last', 1);
        }
        return redirect()->route('departamentos.index')->with('success', 'Departamento creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(departamento $departamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($departamento_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $departamento = departamento::findOrFail($departamento_id);
        return view('empleados.departamentos.edit', compact('departamento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $departamento_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $request->validate([
            'departamento' => 'required|string|max:255',
        ], [
            'departamento.required' => 'El nombre del departamento es obligatorio.',
            'departamento.string' => 'El nombre del departamento debe ser una cadena de texto.',
            'departamento.max' => 'El nombre del departamento no debe superar los 255 caracteres.',
        ]);

        $departamento = departamento::findOrFail($departamento_id);
        $departamento->departamento = strtoupper($request->departamento);
        $departamento->save();

        return redirect()->route('departamentos.index')->with('success', 'Departamento actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($departamento_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $dep = departamento::findOrFail($departamento_id);
        $dep->del = 'S';
        $dep->save();
        return redirect()->route('departamentos.index')->with('success', 'Departamento eliminado correctamente.');
    }
}
