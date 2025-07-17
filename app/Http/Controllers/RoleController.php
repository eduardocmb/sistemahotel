<?php

namespace App\Http\Controllers;

use App\Models\correlativo;
use App\Models\role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
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
        return view('roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        return view('roles.create');
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
            'codigo' => 'required|string|max:9|unique:roles,codigo',
            'rol' => 'required|string|max:50',
        ], [
            'codigo.required' => 'El campo código es obligatorio.',
            'codigo.max' => 'El código no debe exceder los 9 caracteres.',
            'codigo.unique' => 'El código ya está en uso.',
            'rol.required' => 'El campo rol es obligatorio.',
            'rol.max' => 'El rol no debe exceder los 50 caracteres.',
        ]);

        $rol = new role();
        $rol->codigo = $request->codigo;
        $rol->rol = $request->rol;

        $checkboxFields = ['ver_informacion', 'guardar', 'actualizar', 'eliminar', 'imprimir', 'reimprimir', 'finanzas'];
        foreach ($checkboxFields as $field) {
            $rol->$field = $request->has($field) ? 'S' : 'N';
        }
        $rol->save();
        if ($request->has('chkGenerarAuto')) {
            $correlativo = correlativo::where('codigo', 'ROLE')->first();
            if ($correlativo) {
                $correlativo->increment('last', 1);
            }
        }
        return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show($role_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $rol = role::findOrFail($role_id);
        return view('roles.destroy', compact('rol'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($role_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $rol = role::findOrFail($role_id);
        return view('roles.edit', compact('rol'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $role_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $request->validate([
            'rol' => 'required|string|max:50',
        ], [
            'rol.required' => 'El campo rol es obligatorio.',
            'rol.max' => 'El rol no debe exceder los 50 caracteres.',
        ]);

        $rol = role::findOrFail($role_id);
        $rol->rol = $request->rol;

        $checkboxFields = ['ver_informacion', 'guardar', 'actualizar', 'eliminar', 'imprimir', 'reimprimir', 'finanzas'];
        foreach ($checkboxFields as $field) {
            $rol->$field = $request->has($field) ? 'S' : 'N';
        }
        $rol->save();
        return redirect()->route('roles.index')->with('success', 'Rol actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $role_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $rol = role::findOrFail($role_id);
        $rol->del = "S";
        $papelera = new PapeleraController();
        $papelera->agregarAPapelera($rol->rol, "roles", $role_id, "id");
        $rol->save();
        return redirect()->route('roles.index')->with('success', 'Rol eliminado exitosamente.');
    }
}
