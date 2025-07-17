<?php

namespace App\Http\Controllers;

use App\Models\insumo;
use App\Models\loteinsumo;
use App\Models\role;
use App\Models\unidadinsumo;
use App\Models\usoinsumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsoinsumoController extends Controller
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
        return view('insumos.uso.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $insumos = insumo::where('del', 'N')->get();
        return view('insumos.uso.create', compact('insumos'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $validated = $request->validate([
            'insumo_id' => 'required|exists:insumos,id',
            'cantidad_usada' => 'required|integer|min:1',
            'ubicacion' => 'required|string|max:255',
            'fecha_uso' => 'required|date|before_or_equal:today',
            'descripcion' => 'nullable|string|max:500',
        ], [
            'insumo_id.required' => 'El insumo es obligatorio.',
            'insumo_id.exists' => 'El insumo seleccionado no es válido.',
            'cantidad_usada.required' => 'La cantidad usada es obligatoria.',
            'cantidad_usada.integer' => 'La cantidad usada debe ser un número entero.',
            'cantidad_usada.min' => 'La cantidad usada debe ser al menos 1.',
            'ubicacion.required' => 'La ubicación es obligatoria.',
            'ubicacion.string' => 'La ubicación debe ser un texto válido.',
            'ubicacion.max' => 'La ubicación no puede exceder 255 caracteres.',
            'fecha_uso.required' => 'La fecha de uso es obligatoria.',
            'fecha_uso.date' => 'La fecha de uso debe ser válida.',
            'fecha_uso.before_or_equal' => 'La fecha de uso no puede ser futura.',
            'descripcion.string' => 'La descripción debe ser un texto válido.',
            'descripcion.max' => 'La descripción no puede exceder 500 caracteres.',
        ]);

        $insumo = Insumo::findOrFail($validated['insumo_id']);
        $insumocantidad = 0;
        $lotes = loteinsumo::where('codigo_insumo', $insumo->codigo)->get();
        foreach ($lotes as $lote) {
            $insumocantidad += floatval($lote->cantidad);
        }

        if ($validated['cantidad_usada'] > $insumocantidad) {
            return back()->withErrors(['cantidad_usada' => 'La cantidad usada excede la cantidad disponible del insumo.'])
                ->withInput();
        }

        $usoInsumo = new UsoInsumo();
        $usoInsumo->insumo_id = $validated['insumo_id'];
        $usoInsumo->cantidad_usada = $validated['cantidad_usada'];
        $usoInsumo->ubicacion = $validated['ubicacion'];
        $usoInsumo->fecha_uso = $validated['fecha_uso'];
        $usoInsumo->descripcion = $validated['descripcion'] ?? null;

        $lotes = loteinsumo::where('codigo_insumo', $insumo->codigo)
            ->orderBy('fecha')
            ->where('cantidad', '>', 0)
            ->get();
            $productoContieneSalida = unidadinsumo::findOrfail(insumo::where('codigo', $insumo->codigo)->first()->unidad_salida_id)->contiene;

        if (!$lotes->isEmpty()) {
            $cantidad_a_restaurar = ( $validated['cantidad_usada'] * $productoContieneSalida);
            foreach ($lotes as $lote) {
                if ($cantidad_a_restaurar <= 0) break;

                $cantidad_del_lote = min($lote->cantidad, $cantidad_a_restaurar);
                $lote->cantidad -= $cantidad_del_lote;
                $lote->save();
                $cantidad_a_restaurar -= $cantidad_del_lote;

                if ($cantidad_a_restaurar <= 0) {
                    break;
                }
            }
        }
        $usoInsumo->save();
        return redirect()->route('uso_insumos.index')->with('success', 'El uso del insumo ha sido registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($usoinsumo_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $usoInsumo = usoinsumo::findOrFail($usoinsumo_id);
        $insumo = insumo::where('del', 'N')->where('id', $usoInsumo->insumo_id)->first();
        return view('insumos.uso.destroy', compact('usoInsumo', 'insumo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($usoinsumo_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $usoInsumo = usoinsumo::findOrFail($usoinsumo_id);
        $insumos = insumo::where('del', 'N')->where('id', $usoInsumo->insumo_id)->get();
        return view('insumos.uso.edit', compact('usoInsumo', 'insumos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $usoinsumo_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $validated = $request->validate([
            'ubicacion' => 'required|string|max:255',
            'fecha_uso' => 'required|date|before_or_equal:today',
            'descripcion' => 'nullable|string|max:500',
        ], [
            'ubicacion.required' => 'La ubicación es obligatoria.',
            'ubicacion.string' => 'La ubicación debe ser un texto válido.',
            'ubicacion.max' => 'La ubicación no puede exceder 255 caracteres.',
            'fecha_uso.required' => 'La fecha de uso es obligatoria.',
            'fecha_uso.date' => 'La fecha de uso debe ser válida.',
            'fecha_uso.before_or_equal' => 'La fecha de uso no puede ser futura.',
            'descripcion.string' => 'La descripción debe ser un texto válido.',
            'descripcion.max' => 'La descripción no puede exceder 500 caracteres.',
        ]);

        $usoInsumo = UsoInsumo::findOrFail($usoinsumo_id);
        $usoInsumo->ubicacion = $validated['ubicacion'];
        $usoInsumo->fecha_uso = $validated['fecha_uso'];
        $usoInsumo->descripcion = $validated['descripcion'] ?? null;
        $usoInsumo->save();
        return redirect()->route('uso_insumos.index')->with('success', 'El uso del insumo ha sido actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($usoinsumo_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $usoInsumo = UsoInsumo::findOrFail($usoinsumo_id);
        $usoInsumo->delete();
        return redirect()->route('uso_insumos.index')->with('success', 'El uso del insumo ha sido eliminado exitosamente.');
    }
}
