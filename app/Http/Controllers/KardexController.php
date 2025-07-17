<?php

namespace App\Http\Controllers;

use App\Models\correlativo;
use App\Models\kardex;
use App\Models\producto;
use App\Models\role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KardexController extends Controller
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
        $productos = producto::where('del', 'N')->where('tipo_producto', 'PRODUCTO FINAL')->get();
        return view('movimientos.index', compact('productos'));
    }

    public function setKardex( $numDocto, $movimiento, $detalle, $codProducto, $habian, $entrada, $salida, $ahora)
    {
        $movimientoNuevo = new kardex();
        $movimientoNuevo->fecha = Carbon::today();
        $movimientoNuevo->numdocto = $numDocto;
        $movimientoNuevo->movimiento = $movimiento;
        $movimientoNuevo->detalle = $detalle;
        $movimientoNuevo->codproducto = $codProducto;
        $movimientoNuevo->habian = $habian;
        $movimientoNuevo->entrada = $entrada;
        $movimientoNuevo->salida = $salida;
        $movimientoNuevo->ahora = $ahora;
        $movimientoNuevo->save();
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
    public function show(kardex $kardex)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(kardex $kardex)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, kardex $kardex)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(kardex $kardex)
    {
        //
    }
}
