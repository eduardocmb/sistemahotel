<?php

namespace App\Http\Controllers;

use App\Models\caja;
use App\Models\role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CajaController extends Controller
{
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
        return view('cajas.index');
    }

    public function create()
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para realizar esta acción.');
        }
        return view('cajas.create');
    }

    public function store(Request $request)
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para realizar esta acción.');
        }

        $validated = $request->validate([
            'numcaja' => 'required|string|unique:cajas,numcaja|max:50',
        ], [
            'numcaja.required' => 'El número de caja es obligatorio.',
            'numcaja.string' => 'El número de caja debe ser una cadena de texto.',
            'numcaja.unique' => 'Ya existe una caja con ese número.',
            'numcaja.max' => 'El número de caja debe tener como máximo 50 caracteres.',
        ]);

        $caja = new caja();
        $caja->numcaja = $request->numcaja;
        $caja->save();

        return redirect()->route('cajas.index')->with('success', 'Caja creada exitosamente.');
    }

    public function show(caja $caja)
    {
        if ($this->rol->ver_informacion == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        return view('cajas.show', compact('caja'));
    }

    public function edit($caja_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para realizar esta acción.');
        }

        $caja = caja::findOrFail($caja_id);
        return view('cajas.edit', compact('caja'));
    }

    public function update(Request $request, $caja_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para realizar esta acción.');
        }

        $validated = $request->validate([
            'numcaja' => 'required|string|max:50|unique:cajas,numcaja,' . $caja_id,
        ], [
            'numcaja.required' => 'El número de caja es obligatorio.',
            'numcaja.string' => 'El número de caja debe ser una cadena de texto.',
            'numcaja.max' => 'El número de caja debe tener como máximo 50 caracteres.',
            'numcaja.unique' => 'Ya existe una caja con ese número.',
        ]);

        $caja = caja::findOrFail($caja_id);
        $caja->numcaja = $request->numcaja;
        $caja->save();

        return redirect()->route('cajas.index')->with('success', 'Caja actualizada exitosamente.');
    }

    public function destroy($caja_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para realizar esta acción.');
        }

        $caja = caja::findOrFail($caja_id);
        $caja->del = "S";
        $papelera = new PapeleraController();
        $papelera->agregarAPapelera($caja->numcaja, "cajas", $caja_id, "id");
        $caja->save();

        return redirect()->route('cajas.index')->with('success', 'Caja eliminada exitosamente.');
    }
}
