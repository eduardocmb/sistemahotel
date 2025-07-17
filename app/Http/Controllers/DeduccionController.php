<?php

namespace App\Http\Controllers;

use App\Models\deduccion;
use App\Models\role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeduccionController extends Controller
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
        return view('planillas.deducciones.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        return view('planillas.deducciones.create');
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
            'nombre' => 'required|string|max:50',
            'tipo' => 'required|string|max:20',
            'monto' => 'required|numeric|min:0',
            'activo' => 'required|string|in:S,N',
            'descripcion' => 'nullable|string|max:250',
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder los 50 caracteres.',
            'tipo.required' => 'El campo tipo es obligatorio.',
            'tipo.max' => 'El tipo no puede exceder los 20 caracteres.',
            'monto.required' => 'El campo monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser un número.',
            'monto.min' => 'El monto debe ser un valor positivo.',
            'activo.required' => 'El campo activo es obligatorio.',
            'activo.in' => 'El valor del campo activo debe ser "S" o "N".',
            'descripcion.max' => 'La descripción no puede exceder los 250 caracteres.',
        ]);

        $deduccion = new Deduccion();
        $deduccion->nombre = $request->nombre;
        $deduccion->tipo = $request->tipo;
        $deduccion->monto = $request->monto;
        $deduccion->activo = $request->activo;
        $deduccion->descripcion = $request->descripcion;
        $deduccion->save();

        return redirect()->route('deducciones.index')->with('success', 'Deducción creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($deduccion_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $deduccion = Deduccion::findOrFail($deduccion_id);
        return view('planillas.deducciones.destroy', compact('deduccion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($deduccion_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $deduccion = Deduccion::findOrFail($deduccion_id);
        return view('planillas.deducciones.edit', compact('deduccion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $deduccion_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $request->validate([
            'nombre' => 'required|string|max:50',
            'tipo' => 'required|string|max:20',
            'monto' => 'required|numeric|min:0',
            'activo' => 'required|string|in:S,N',
            'descripcion' => 'nullable|string|max:250',
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder los 50 caracteres.',
            'tipo.required' => 'El campo tipo es obligatorio.',
            'tipo.max' => 'El tipo no puede exceder los 20 caracteres.',
            'monto.required' => 'El campo monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser un número.',
            'monto.min' => 'El monto debe ser un valor positivo.',
            'activo.required' => 'El campo activo es obligatorio.',
            'activo.in' => 'El valor del campo activo debe ser "S" o "N".',
            'descripcion.max' => 'La descripción no puede exceder los 250 caracteres.',
        ]);

        $deduccion = Deduccion::findOrFail($deduccion_id);
        $deduccion->nombre = $request->nombre;
        $deduccion->tipo = $request->tipo;
        $deduccion->monto = $request->monto;
        $deduccion->activo = $request->activo;
        $deduccion->descripcion = $request->descripcion;
        $deduccion->save();

        return redirect()->route('deducciones.index')->with('success', 'Deducción actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($deduccion_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $deduccion = Deduccion::findOrFail($deduccion_id);
        $deduccion->delete();
        return redirect()->route('deducciones.index')->with('success', 'Deducción eliminada exitosamente.');
    }

    public function getInfoDeduccion($deduccion_id){
        $deduccion = Deduccion::findOrFail($deduccion_id);
        return $deduccion;
    }
}
