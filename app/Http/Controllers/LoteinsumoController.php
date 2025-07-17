<?php

namespace App\Http\Controllers;

use App\Models\insumo;
use App\Models\loteinsumo;
use App\Models\role;
use App\Models\unidadinsumo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoteinsumoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $rol;

    public function __construct()
    {
        $this->rol = role::where('codigo', Auth::user()->idrol)->first();
    }

    public function index($insumo_codigo)
    {
        if ($this->rol->ver_informacion == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }

        $lotes = loteinsumo::where('codigo_insumo', $insumo_codigo)->get();
        $producto = insumo::where('codigo', $insumo_codigo)->first();
        return view('insumos.lotes.index', compact('lotes', 'producto'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createlote($producto_codigo)
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $lotes = loteinsumo::where('codigo_insumo', $producto_codigo)->get();
        $producto = insumo::where('codigo', $producto_codigo)->first();
        $presentacion = unidadinsumo::where('id', $producto->unidad_salida_id)->first();
        return view('insumos.lotes.create', compact('lotes', 'producto', 'presentacion'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $validatedData = $request->validate([
            'fecha' => 'required|date',
            'precio_compra' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:1',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha',
        ], [
            'fecha.date' => 'La fecha de Ingreso debe ser una fecha válida.',
            'fecha.required' => 'La fecha de Ingreso es obligatoria.',
            'precio_compra.min' => 'El precio de compra no puede ser negativo.',
            'precio_compra.required' => 'El precio de compra es obligatorio.',
            'precio_compra.numeric' => 'El precio de compra debe ser un número válido.',
            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'fecha_vencimiento.after_or_equal' => 'La fecha de vencimiento no puede ser anterior a la fecha de ingreso.',
            'fecha_vencimiento.date' => 'La fecha de vencimiento debe ser una fecha válida.',
        ]);

        $producto = insumo::where('codigo', $request->codigoprod)->first();
        $presentacion = unidadinsumo::where('id', $producto->unidad_salida_id)->first();

        $precioCompraTotal = $request->precio_compra;
        $cantidad = $request->cantidad;
        $precioVenta = $producto->precio_venta;
        $presentacionContiene = $presentacion->contiene;
        $costoUnitario = ($precioCompraTotal / $cantidad) * $presentacionContiene;
        $utilidad = $precioVenta - $costoUnitario;

        if ($utilidad < 0) {
            return back()->withErrors([
                'utilidad' => 'La utilidad no puede ser menor que 0. El precio de venta es demasiado bajo.'
            ])->withInput();
        }

        $lote = new loteinsumo();
        $lote->codigo_insumo = $request->codigoprod;
        $lote->fecha = Carbon::today();
        $lote->precio_compra = $request->precio_compra;
        $lote->cantidad = $request->cantidad;
        $lote->cant_comprada = $request->cantidad;
        $lote->fecha_vencimiento = $request->fecha_vencimiento;
        $lote->save();

        return redirect()->route('lotes.insumos.index', $request->codigoprod)->with('success', 'Lote agregado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($lote_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $lote = loteinsumo::findOrFail($lote_id);
        return view('insumos.lotes.destroy', compact('lote'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit2($lote_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $lote = loteinsumo::findOrFail($lote_id);
        $producto = insumo::where('codigo', $lote->codigo_insumo)->first();
        $presentacion = unidadinsumo::where('id', $producto->unidad_salida_id)->first();
        return view('insumos.lotes.edit', compact('lote', 'producto', 'presentacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $lote_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $validatedData = $request->validate([
            'fecha' => 'required|date',
            'precio_compra' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:1',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha',
        ], [
            'fecha.date' => 'La fecha de Ingreso debe ser una fecha válida.',
            'fecha.required' => 'La fecha de Ingreso es obligatoria.',
            'precio_compra.min' => 'El precio de compra no puede ser negativo.',
            'precio_compra.required' => 'El precio de compra es obligatorio.',
            'precio_compra.numeric' => 'El precio de compra debe ser un número válido.',
            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'fecha_vencimiento.after_or_equal' => 'La fecha de vencimiento no puede ser anterior a la fecha de ingreso.',
            'fecha_vencimiento.date' => 'La fecha de vencimiento debe ser una fecha válida.',
        ]);

        $producto = insumo::where('codigo', $request->codigoprod)->first();
        $presentacion = unidadinsumo::where('id', $producto->unidad_salida_id)->first();

        $precioCompraTotal = $request->precio_compra;
        $cantidad = $request->cantidad;
        $precioVenta = $producto->precio_venta;
        $presentacionContiene = $presentacion->contiene;
        $costoUnitario = ($precioCompraTotal / $cantidad) * $presentacionContiene;
        $utilidad = $precioVenta - $costoUnitario;

        if ($utilidad < 0) {
            return back()->withErrors([
                'utilidad' => 'La utilidad no puede ser menor que 0. El precio de venta es demasiado bajo.'
            ])->withInput();
        }

        $lote = loteinsumo::findOrFail($lote_id);
        $lote->precio_compra = $request->precio_compra;
        $lote->cantidad = $request->cantidad;
        $lote->cant_comprada = $request->cantidad;
        $lote->fecha_vencimiento = $request->fecha_vencimiento;
        $lote->save();

        return redirect()->route('lotes.insumos.index', $request->codigoprod)->with('success', 'Lote actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($lote_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $lote = loteinsumo::findOrFail($lote_id);
        $lote->delete();
        return redirect()->route('lotes.insumos.index', $lote->codigo_insumo)->with('success', 'Lote eliminado correctamente.');
    }
}
