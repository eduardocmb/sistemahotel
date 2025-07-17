<?php

namespace App\Http\Controllers;

use App\Models\detallecompraproducto;
use App\Models\role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetallecompraproductoController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(detallecompraproducto $detallecompraproducto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(detallecompraproducto $detallecompraproducto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, detallecompraproducto $detallecompraproducto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(detallecompraproducto $detallecompraproducto)
    {
        //
    }
}
