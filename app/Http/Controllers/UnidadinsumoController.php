<?php

namespace App\Http\Controllers;

use App\Models\role;
use App\Models\unidadinsumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnidadinsumoController extends Controller
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
        return view('unidades.index');
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
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $request->validate([
            'nombre' => 'required|max:100',
            'contiene' => 'required|integer|min:1',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            'contiene.required' => 'El campo "contiene" es obligatorio.',
            'contiene.integer' => 'El campo "contiene" debe ser un número entero.',
            'contiene.min' => 'El campo "contiene" debe ser al menos 1.',
        ]);

        $unidadInsumo = new UnidadInsumo();
        $unidadInsumo->nombre = $request->nombre;
        $unidadInsumo->contiene = $request->contiene;

        $unidadInsumo->save();

        return redirect()->route('unidades.index')->with('success', 'Unidad de insumo creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(unidadinsumo $unidadinsumo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($unidadinsumo_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $unidadinsumo = unidadinsumo::findOrFail($unidadinsumo_id);
        return view('unidades.edit', compact('unidadinsumo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $unidadinsumo_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $request->validate([
            'nombre' => 'required|max:100',
            'contiene' => 'required|integer|min:1',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            'contiene.required' => 'El campo "contiene" es obligatorio.',
            'contiene.integer' => 'El campo "contiene" debe ser un número entero.',
            'contiene.min' => 'El campo "contiene" debe ser al menos 1.',
        ]);

        $unidadinsumo = unidadinsumo::findOrFail($unidadinsumo_id);
        $unidadinsumo->nombre = $request->nombre;
        $unidadinsumo->contiene = $request->contiene;

        $unidadinsumo->save();

        return redirect()->route('unidades.index')->with('success', 'Unidad de insumo actualizada exitosamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($unidadinsumo_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $unidadInsumo = UnidadInsumo::findOrFail($unidadinsumo_id);
        $unidadInsumo->del = "S";
        $papelera = new PapeleraController();
        $papelera->agregarAPapelera($unidadInsumo->nombre, "unidadinsumos", $unidadinsumo_id, "id");
        $unidadInsumo->save();
        return redirect()->route('unidades.index')->with('success', 'Unidad de insumo eliminada exitosamente.');
    }
}
