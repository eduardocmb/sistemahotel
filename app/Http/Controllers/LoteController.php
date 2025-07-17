<?php

namespace App\Http\Controllers;

use App\Models\lote;
use App\Models\producto;
use App\Models\role;
use App\Models\unidadinsumo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $rol;

    public function __construct()
    {
        $this->rol = role::where('codigo', Auth::user()->idrol)->first();
    }

    public function index($producto_codigo)
    {
        if ($this->rol->ver_informacion == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }

        $lotes = lote::where('codigo_producto', $producto_codigo)->get();
        $producto = producto::where('codigo', $producto_codigo)->first();
        return view('productos.lotes.index', compact('lotes', 'producto'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($producto_codigo) {}

    public function createlote($producto_codigo)
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $lotes = lote::where('codigo_producto', $producto_codigo)->get();
        $producto = producto::where('codigo', $producto_codigo)->first();
        $presentacion = unidadinsumo::where('id', $producto->unidad_salida_id)->first();
        return view('productos.lotes.create', compact('lotes', 'producto', 'presentacion'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $kardex = new KardexController();
        $validatedData = $request->validate([
            'codigoprod' => 'required|exists:productos,codigo',
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

        $producto = producto::where('codigo', $request->codigoprod)->first();
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

        $lote = new Lote();
        $lote->codigo_producto = $request->codigoprod;
        $actuales = 0;
        $lotes = lote::where('codigo_producto', $lote->codigo_producto)->get();
        foreach($lotes as $lot){
            $actuales+= floatval($lot->cantidad);
        }
        $nuevos = $actuales + $request->cantidad;
        $kardex->setKardex('NA', 'ENTRADA', 'ENTRADA DE LOTE', $lote->codigo_producto, $actuales, $request->cantidad, 0, $nuevos);
        $lote->fecha = Carbon::today();
        $lote->precio_compra = $request->precio_compra;
        $lote->cantidad = $request->cantidad;
        $lote->cant_comprada = $request->cantidad;
        $lote->fecha_vencimiento = $request->fecha_vencimiento;
        $lote->save();

        return redirect()->route('lotes.productos.index', $request->codigoprod)->with('success', 'Lote agregado correctamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show($lote_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $lote = Lote::findOrFail($lote_id);
        return view('productos.lotes.destroy', compact('lote'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($lote_id) {}

    public function edit2($lote_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }

        $lote = Lote::findOrFail($lote_id);
        $producto = producto::where('codigo', $lote->codigo_producto)->first();
        $presentacion = unidadinsumo::where('id', $producto->unidad_salida_id)->first();

        return view('productos.lotes.edit', compact('lote', 'producto', 'presentacion'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $lote_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        // Validación con mensajes personalizados
        $validatedData = $request->validate([
            'codigoprod' => 'required|exists:productos,codigo',
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

        $producto = producto::where('codigo', $request->codigoprod)->first();
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

        $lote = lote::findOrFail($lote_id);
        $lote->precio_compra = $request->precio_compra;
        $lote->cantidad = $request->cantidad;
        $lote->cant_comprada = $request->cantidad;
        $lote->fecha_vencimiento = $request->fecha_vencimiento;
        $lote->save();

        return redirect()->route('lotes.productos.index', $request->codigoprod)->with('success', 'Lote actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($lote_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $lote = lote::findOrFail($lote_id);
        $lote->delete();
        return redirect()->route('lotes.productos.index', $lote->codigo_producto)->with('success', 'Lote eliminado correctamente.');
    }
}
