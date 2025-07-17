<?php

namespace App\Http\Controllers;

use App\Models\papelera;
use App\Models\role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PapeleraController extends Controller
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
        return view('papelera.index');
    }

    /**
     * Show the form for creating a new resource.
     */

    public function agregarAPapelera($registro, $modelo, $id, $token)
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $pap = new papelera();
        $pap->registro = $registro;
        $pap->modelo = $modelo;
        $pap->pk = $id;
        $pap->token = $token;
        $pap->usuario = Auth::user()->username;
        $pap->fecha = Carbon::today();
        $pap->save();
    }

    public function create() {}

    public function restore($id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $registro = papelera::findOrFail($id);
        $tabla = $registro->modelo;
        $pk = $registro->pk;

        DB::table($tabla)->where($registro->token, $pk)->update(['del' => 'N']);
        DB::table('papeleras')->where('id', $id)->delete();
        return redirect()->route('papelera.index')->with('success', 'El registro se ha restaurado correctamente.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $papeleras = DB::table('papeleras')->get();

        foreach ($papeleras as $registro) {
            $tabla = $registro->modelo;
            $pk = $registro->pk;

            DB::table($tabla)->where($registro->token, $pk)->update(['del' => 'N']);
            DB::table('papeleras')->where('id', $registro->id)->delete();
        }

        return redirect()->route('papelera.index')->with('success', 'Todos los registros han sido restaurados correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(papelera $papelera)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(papelera $papelera)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $registro = papelera::findOrFail($id);
        $tabla = $registro->modelo;
        $pk = $registro->pk;
        DB::table($tabla)->where($registro->token, $pk)->update(['del' => 'N']);
        DB::table('papeleras')->where('id', $registro->id)->delete();
        return redirect()->route('papelera.index')->with('success', 'El registro ha sido restaurado correctamente.');
    }

    public function dstroyall() {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        $registro = papelera::findOrFail($id);
        DB::table('papeleras')->where('id', $registro->id)->delete();
        return redirect()->route('papelera.index')->with('success', 'El registro ha sido eliminado permanentemente.');
    }
}
