<?php

namespace App\Http\Controllers;

use App\Models\categoria;
use App\Models\role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
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
        return view('categorias.index');
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
            'categoria' => 'required'], [
                'categoria.required' => 'La categoría es obligatoria.',
            ]);

        $categoria = new categoria();
        $categoria->categoria = $request->categoria;
        $categoria->save();
        return redirect()->route('categorias.index')->with('success', 'Categoría registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($categoria_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $categoria = categoria::findOrFail($categoria_id);
        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $categoria_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $request->validate([
            'categoria' => 'required'], [
                'categoria.required' => 'La categoría es obligatoria.',
            ]);

        $categoria = categoria::findOrFail($categoria_id);
        $categoria->categoria = $request->categoria;
        $categoria->save();
        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($categoria_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $categoria = categoria::findOrFail($categoria_id);
        $categoria->del = "S";
        $papelera = new PapeleraController();
        $papelera->agregarAPapelera($categoria->categoria, "categorias", $categoria_id, "id");
        $categoria->save();
        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada correctamente.');
    }
}
