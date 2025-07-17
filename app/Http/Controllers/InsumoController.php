<?php

namespace App\Http\Controllers;

use App\Models\categoria;
use App\Models\correlativo;
use App\Models\impuesto;
use App\Models\insumo;
use App\Models\loteinsumo;
use App\Models\proveedor;
use App\Models\role;
use App\Models\unidadinsumo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InsumoController extends Controller
{
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
        correlativo::firstOrCreate([
            'codigo' => 'INSU',
            'description' => 'Correlativo de Insumos',
        ], [
            'last' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return view('insumos.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }

        $impuestos = impuesto::all();
        $unidadInsumos = unidadinsumo::all();
        $proveedores = proveedor::all();
        $categorias = categoria::all();

        return view('insumo.create', compact('unidadInsumos', 'proveedores', 'impuestos', 'categorias'));
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
            'codigo_Insumo' => 'required|max:9',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'stock_minimo' => 'required|numeric|min:0',
            'impuesto_id' => 'required|exists:impuestos,id',
            'precio_venta' => 'required|numeric|min:0',
            'categoria' => 'required',
            'unidadInsumoentrada_id' => 'required|exists:unidadinsumos,id',
            'unidadInsumosalida_id' => 'required|exists:unidadinsumos,id',
            'tipo_Insumo' => 'required'
        ], [
            'tipo_Insumo.required' => 'El tipo de insumo es requerido.',
            'codigo_Insumo.required' => 'El código del insumo es obligatorio.',
            'codigo_Insumo.max' => 'El código del insumo no puede tener más de 9 caracteres.',
            'nombre.required' => 'El nombre del insumo es obligatorio.',
            'nombre.string' => 'El nombre del insumo debe ser una cadena de texto.',
            'unidadInsumoentrada_id.required' => 'Debe seleccionar una unidad de insumo.',
            'unidadInsumoentrada_id.exists' => 'La unidad de insumo seleccionada no es válida.',
            'unidadInsumosalida_id.required' => 'Debe seleccionar una unidad de insumo.',
            'unidadInsumosalida_id.exists' => 'La unidad de insumo seleccionada no es válida.',
            'nombre.max' => 'El nombre del insumo no puede tener más de 255 caracteres.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'stock_minimo.required' => 'El stock mínimo es obligatorio.',
            'stock_minimo.numeric' => 'El stock mínimo debe ser un número.',
            'stock_minimo.min' => 'El stock mínimo no puede ser menor que 0.',
            'impuesto_id.required' => 'Debe seleccionar un impuesto.',
            'impuesto_id.exists' => 'El impuesto seleccionado no es válido.',
            'precio_venta.required' => 'El precio de venta es obligatorio.',
            'precio_venta.numeric' => 'El precio de venta debe ser un número.',
            'precio_venta.min' => 'El precio de venta no puede ser menor que 0.',

            'categoria.required' => 'Debe seleccionar una categoría.',
        ]);

        $insumo = new insumo();
        $insumo->codigo = $validatedData['codigo_Insumo'];
        $insumo->nombre = $validatedData['nombre'];
        $insumo->descripcion = $validatedData['descripcion'];
        $insumo->stock_minimo = $validatedData['stock_minimo'];
        $insumo->precio_venta = $validatedData['precio_venta'];
        $insumo->categoria_id = $validatedData['categoria'];
        $insumo->tipo_producto = $validatedData['tipo_Insumo'] == 'insumofinal' ? 'PRODUCTO FINAL' : 'SERVICIO';
        $insumo->impuesto_id = $validatedData['impuesto_id'];
        $insumo->unidad_entrada_id = $validatedData['unidadInsumoentrada_id'];
        $insumo->unidad_salida_id = $validatedData['unidadInsumosalida_id'];

        $insumo->save();
        if ($request->has('chkGenerarAuto')) {
            $correlativo = correlativo::where('codigo', 'INSU')->first();
            if ($correlativo) {
                $correlativo->increment('last', 1);
            }
        }

        $lote = new loteinsumo();
        $lote->fecha = Carbon::today();
        $lote->codigo_insumo = $request->codigo_Insumo;
        $lote->precio_compra = 0;
        $lote->cantidad = 0;
        $lote->cant_comprada = 0;
        $lote->save();
        return redirect()->route('insumo.index')
            ->with('success', 'Insumo creado con éxito!');
    }

    /**
     * Display the specified resource.
     */
    public function show($insumo_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $insumo = insumo::findOrFail($insumo_id);
        $impuestos = impuesto::all();
        $unidadinsumos = unidadinsumo::all();
        $proveedores = proveedor::all();
        $categorias = categoria::all();

        return view('insumo.destroy', compact('insumo','unidadinsumos', 'proveedores', 'impuestos', 'categorias'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($insumo_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $insumo = insumo::findOrFail($insumo_id);
        $impuestos = impuesto::all();
        $unidadinsumos = unidadinsumo::all();
        $proveedores = proveedor::all();
        $categorias = categoria::all();

        return view('insumo.edit', compact('insumo','unidadinsumos', 'proveedores', 'impuestos', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $insumo_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'cantidad' => 'required|numeric|min:0',
            'precio_unitario' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'impuesto_id' => 'required|exists:impuestos,id',
            'unidadinsumo_id' => 'required|exists:unidadinsumos,id',
            'proveedor_id' => 'required|exists:proveedors,id',
            'categoria' => 'required',
            'fecha_compra' => 'required|date',
            'fecha_vencimiento' => 'nullable|date|after:fecha_compra',
            'precio_venta' => ['required', 'numeric', 'min:0', function ($attribute, $value, $fail) use ($request) {
                if ($value < $request->precio_unitario) {
                    $fail('El precio de venta no puede ser menor que el precio de compra.');
                }
            }],
        ], [
            'nombre.required' => 'El nombre del insumo es obligatorio.',
            'nombre.string' => 'El nombre del insumo debe ser una cadena de texto.',
            'nombre.max' => 'El nombre del insumo no puede tener más de 255 caracteres.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.numeric' => 'La cantidad debe ser un número.',
            'cantidad.min' => 'La cantidad no puede ser menor que 0.',
            'precio_unitario.required' => 'El precio de compra es obligatorio.',
            'precio_unitario.numeric' => 'El precio de compra debe ser un número.',
            'precio_unitario.min' => 'El precio de compra no puede ser menor que 0.',
            'precio_venta.required' => 'El precio de venta es obligatorio.',
            'precio_venta.numeric' => 'El precio de venta debe ser un número.',
            'precio_venta.min' => 'El precio de venta no puede ser menor que 0.',
            'stock_minimo.required' => 'El stock mínimo es obligatorio.',
            'stock_minimo.numeric' => 'El stock mínimo debe ser un número.',
            'stock_minimo.min' => 'El stock mínimo no puede ser menor que 0.',
            'impuesto_id.required' => 'Debe seleccionar un impuesto.',
            'impuesto_id.exists' => 'El impuesto seleccionado no es válido.',
            'unidadinsumo_id.required' => 'Debe seleccionar una unidad de insumo.',
            'unidadinsumo_id.exists' => 'La unidad de insumo seleccionada no es válida.',
            'proveedor_id.required' => 'Debe seleccionar un proveedor.',
            'proveedor_id.exists' => 'El proveedor seleccionado no es válido.',
            'categoria.required' => 'Debe seleccionar una categoría.',
            'fecha_compra.required' => 'La fecha de compra es obligatoria.',
            'fecha_compra.date' => 'La fecha de compra no es válida.',
            'fecha_vencimiento.after' => 'La fecha de vencimiento debe ser anterior a la fecha de compra.',
        ]);

        $insumo = insumo::findOrFail($insumo_id);
        $insumo->nombre = $validatedData['nombre'];
        $insumo->descripcion = $validatedData['descripcion'];
        $insumo->cantidad = $validatedData['cantidad'];
        $insumo->precio_compra = $validatedData['precio_unitario'];
        $insumo->precio_venta = $validatedData['precio_venta'];
        $insumo->stock_minimo = $validatedData['stock_minimo'];
        $insumo->categoria_id = $validatedData['categoria'];
        $insumo->impuesto_id = $validatedData['impuesto_id'];
        $insumo->unidadinsumo_id = $validatedData['unidadinsumo_id'];
        $insumo->proveedor_id = $validatedData['proveedor_id'];
        $insumo->fecha_compra = $validatedData['fecha_compra'];
        $insumo->fecha_vencimiento = $validatedData['fecha_vencimiento'] ?? null;

        $insumo->save();
        return redirect()->route('insumo.index')->with('success', 'Insumo actualizado con éxito!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($insumo_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $insumo = Insumo::find($insumo_id);
        $insumo->delete();
        return redirect()->route('insumo.index')->with('success', 'Insumo eliminado exitosamente.');
    }
}
