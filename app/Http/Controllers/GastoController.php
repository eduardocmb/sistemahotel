<?php

namespace App\Http\Controllers;

use App\Models\aperturasCajas;
use App\Models\gasto;
use App\Models\role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GastoController extends Controller
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
        return view('gastos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if ($this->rol->ver_informacion == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        return view('gastos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar permisos
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }

        // Validación de los datos
        $validatedData = $request->validate([
            'tipo' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
        ], [
            'tipo.required' => 'El tipo de gasto es obligatorio.',
            'tipo.string' => 'El tipo de gasto debe ser una cadena de texto.',
            'tipo.max' => 'El tipo de gasto no puede exceder los 255 caracteres.',
            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser un número.',
            'monto.min' => 'El monto no puede ser menor que 0.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
        ]);

        $gasto = new Gasto();
        $gasto->tipo = $request->tipo;
        $gasto->monto = $request->monto;
        $gasto->user_id = Auth::user()->id;

        $caja = aperturasCajas::where('user_id', Auth::user()->id)
            ->where('estado', 'ABIERTA')
            ->where('fecha', Carbon::today())
            ->first();

        if ($caja) {
            $gasto->caja_id = $caja->caja_id;
            $gasto->turno_id = $caja->turno_id;
        } else {
            return redirect()->route('gastos.create')->with('error', 'No se encontró una caja abierta con la fecha de hoy. Verifique su caja e inténtelo nuevamente.');
        }

        $gasto->apnum = aperturasCajas::where('fecha', Carbon::today())
        ->where('user_id', Auth::user()->id)
        ->where('turno_id', $caja->turno_id)
        ->where('caja_id', $caja->caja_id)
        ->where('estado', 'ABIERTA')
        ->first()->codigo_apertura;

        $gasto->fecha = Carbon::today();
        $gasto->descripcion = $request->descripcion;

        if ($gasto->save()) {
            return redirect()->route('gastos.index')->with('success', 'El gasto ha sido registrado correctamente.');
        } else {
            return redirect()->route('gastos.create')->with('error', 'Ocurrió un problema al registrar el gasto. Inténtelo nuevamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($gasto_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $gasto = Gasto::findOrFail($gasto_id);
        return view('gastos.destroy', compact('gasto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($gasto_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $gasto = Gasto::findOrFail($gasto_id);
        return view('gastos.edit', compact('gasto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $gasto_id)
    {
        if ($this->rol->editar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }

        $validatedData = $request->validate([
            'tipo' => 'required|string|max:255',
            'fecha' => 'required|date',
            'descripcion' => 'nullable|string',
        ], [
            'tipo.required' => 'El tipo de gasto es obligatorio.',
            'tipo.string' => 'El tipo de gasto debe ser una cadena de texto.',
            'tipo.max' => 'El tipo de gasto no puede exceder los 255 caracteres.',
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha debe ser una fecha válida.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
        ]);

        $gasto = Gasto::findOrFail($gasto_id);
        $gasto->tipo = $request->tipo;
        $gasto->descripcion = $request->descripcion;

        if ($gasto->save()) {
            return redirect()->route('gastos.index')->with('success', 'El gasto ha sido actualizado correctamente.');
        } else {
            return redirect()->route('gastos.edit', $gasto_id)->with('error', 'Ocurrió un problema al actualizar el gasto. Inténtelo nuevamente.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($gasto_id)
    {
    if ($this->rol->eliminar == "N") {
        return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
    }
    $gasto = Gasto::findOrFail($gasto_id);
    if ($gasto->delete()) {
        return redirect()->route('gastos.index')->with('success', 'El gasto ha sido eliminado correctamente.');
    } else {
        return redirect()->route('gastos.index')->with('error', 'Ocurrió un problema al eliminar el gasto. Inténtelo nuevamente.');
    }
    }
}
