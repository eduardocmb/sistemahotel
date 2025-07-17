<?php

namespace App\Http\Controllers;

use App\Models\categoria;
use App\Models\correlativo;
use App\Models\impuesto;
use App\Models\lote;
use App\Models\NotificacionesHabitacion;
use App\Models\producto;
use App\Models\proveedor;
use App\Models\role;
use App\Models\unidad;
use App\Models\unidadinsumo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
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
        correlativo::firstOrCreate([
            'codigo' => 'PROD',
            'description' => 'Correlativo de Productos',
        ], [
            'last' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return view('productos.index');
    }

    public function getProductosPocaExistencia()
{
    $productos = Producto::where('del', 'N')
        ->where('tipo_producto', 'PRODUCTO FINAL')
        ->get();

    foreach ($productos as $prod) {
        $total_cantidad = Lote::where('codigo_producto', $prod->codigo)->sum('cantidad');

        if (intval($total_cantidad) <= intval($prod->stock_minimo)) {
            // Buscar si ya existe la notificación
            $notificacion = NotificacionesHabitacion::where('title', "Producto {$prod->nombre} con poca existencia")->first();

            if ($notificacion) {
                $nuevaDescripcion = "El producto {$prod->nombre} tiene una cantidad actual de {$total_cantidad}, menor que el stock mínimo de {$prod->stock_minimo}.";
                if ($notificacion->description !== $nuevaDescripcion) {
                    $notificacion->update([
                        'description' => $nuevaDescripcion,
                        'leido' => 'N',
                    ]);
                }
            } else {
                NotificacionesHabitacion::create([
                    'title' => "Producto {$prod->nombre} con poca existencia",
                    'description' => "El producto {$prod->nombre} tiene una cantidad actual de {$total_cantidad}, menor que el stock mínimo de {$prod->stock_minimo}.",
                    'leido' => 'N',
                ]);
            }
        } else {
            NotificacionesHabitacion::where('title', "Producto {$prod->nombre} con poca existencia")->delete();
        }
    }

    return response()->json(['message' => 'Notificaciones de productos actualizadas exitosamente'], 200);
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
        $unidadproductos = unidadinsumo::where('del', 'N')->get();
        $proveedores = proveedor::where('del', 'N')->get();
        $categorias = categoria::where('del', 'N')->get();

        return view('productos.create', compact('unidadproductos', 'proveedores', 'impuestos', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $rules = [
            'codigo_producto' => 'required|max:9',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'stock_minimo' => 'required|numeric|min:0',
            'impuesto_id' => 'required|exists:impuestos,id',
            'precio_venta' => 'required|numeric|min:0',
            'categoria' => 'required',
            'tipo_producto' => 'required'
        ];

        if ($request->tipo_producto === 'productofinal') {
            $rules['unidadproductoentrada_id'] = 'required|exists:unidadinsumos,id';
            $rules['unidadproductosalida_id'] = 'required|exists:unidadinsumos,id';
            $rules['stock_minimo'] = 'required|numeric|min:0';
        }

        $messages = [
            'tipo_producto.required' => 'El tipo de producto es requerido.',
            'codigo_producto.required' => 'El código del producto es obligatorio.',
            'codigo_producto.max' => 'El código del producto no puede tener más de 9 caracteres.',
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'nombre.string' => 'El nombre del producto debe ser una cadena de texto.',
            'unidadproductoentrada_id.required' => 'Debe seleccionar una unidad de producto de entrada.',
            'unidadproductoentrada_id.exists' => 'La unidad de producto de entrada seleccionada no es válida.',
            'unidadproductosalida_id.required' => 'Debe seleccionar una unidad de producto de salida.',
            'unidadproductosalida_id.exists' => 'La unidad de producto de salida seleccionada no es válida.',
            'nombre.max' => 'El nombre del producto no puede tener más de 255 caracteres.',
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
        ];

        $validatedData = $request->validate($rules, $messages);


        $producto = new producto();
        $producto->codigo = $validatedData['codigo_producto'];
        $producto->nombre = $validatedData['nombre'];
        $producto->descripcion = $validatedData['descripcion'];
        $producto->stock_minimo = $validatedData['tipo_producto'] == 'productofinal' ? $validatedData['stock_minimo'] : 0;
        $producto->precio_venta = $validatedData['precio_venta'];
        $producto->categoria_id = $validatedData['categoria'];
        $producto->tipo_producto = $validatedData['tipo_producto'] == 'productofinal' ? 'PRODUCTO FINAL' : 'SERVICIO';
        $producto->impuesto_id = $validatedData['impuesto_id'];
        $producto->unidad_entrada_id = $validatedData['tipo_producto'] == 'productofinal' ? $validatedData['unidadproductoentrada_id'] : null;;
        $producto->unidad_salida_id = $validatedData['tipo_producto'] == 'productofinal' ? $validatedData['unidadproductosalida_id']:null;

        $producto->save();
        if ($request->has('chkGenerarAuto')) {
            $correlativo = correlativo::where('codigo', 'PROD')->first();
            if ($correlativo) {
                $correlativo->increment('last', 1);
            }
        }

        if($request->tipo_producto == 'productofinal'){
            $lote = new lote();
            $lote->fecha = Carbon::today();
            $lote->codigo_producto = $validatedData['codigo_producto'];
            $lote->precio_compra = 0;
            $lote->cantidad = 0;
            $lote->cant_comprada = 0;
            $lote->save();

            return redirect()->route('productos.index')
            ->with('success', 'Producto creado con éxito!')
            ->with('nuevoProducto', true)
            ->with('prod', $validatedData['nombre'])
            ->with('codprod', $validatedData['codigo_producto']);
        }else{
            return redirect()->route('productos.index')
            ->with('success', 'Servicio creado con éxito!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($producto_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $producto = producto::findOrFail($producto_id);
        $impuestos = impuesto::all();
        $unidadproductos = unidadinsumo::all();
        $proveedores = proveedor::all();
        $categorias = categoria::all();

        return view('productos.destroy', compact('producto', 'unidadproductos', 'proveedores', 'impuestos', 'categorias'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($producto_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $producto = producto::findOrFail($producto_id);
        $impuestos = impuesto::all();
        $unidadproductos = unidadinsumo::all();
        $proveedores = proveedor::all();
        $categorias = categoria::all();

        return view('productos.edit', compact('producto', 'unidadproductos', 'proveedores', 'impuestos', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $producto_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $rules = [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'stock_minimo' => 'required|numeric|min:0',
            'impuesto_id' => 'required|exists:impuestos,id',
            'precio_venta' => 'required|numeric|min:0',
            'categoria' => 'required',
            'tipo_producto' => 'required'
        ];

        if ($request->tipo_producto === 'productofinal') {
            $rules['unidadproductoentrada_id'] = 'required|exists:unidadinsumos,id';
            $rules['unidadproductosalida_id'] = 'required|exists:unidadinsumos,id';
            $rules['stock_minimo'] = 'required|numeric|min:0';
        }

        $messages = [
            'tipo_producto.required' => 'El tipo de producto es requerido.',
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'nombre.string' => 'El nombre del producto debe ser una cadena de texto.',
            'unidadproductoentrada_id.required' => 'Debe seleccionar una unidad de producto de entrada.',
            'unidadproductoentrada_id.exists' => 'La unidad de producto de entrada seleccionada no es válida.',
            'unidadproductosalida_id.required' => 'Debe seleccionar una unidad de producto de salida.',
            'unidadproductosalida_id.exists' => 'La unidad de producto de salida seleccionada no es válida.',
            'nombre.max' => 'El nombre del producto no puede tener más de 255 caracteres.',
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
        ];

        $validatedData = $request->validate($rules, $messages);

        $producto = producto::findOrFail($producto_id);
        $producto->nombre = $validatedData['nombre'];
        $producto->descripcion = $validatedData['descripcion'];
        $producto->tipo_producto = $validatedData['tipo_producto'] == 'productofinal' ? 'PRODUCTO FINAL' : 'SERVICIO';
        $producto->stock_minimo = $validatedData['stock_minimo'];
        $producto->categoria_id = $validatedData['categoria'];
        $producto->impuesto_id = $validatedData['impuesto_id'];
        $producto->precio_venta = $validatedData['precio_venta'];
        $producto->unidad_entrada_id = $validatedData['tipo_producto'] == 'productofinal' ? $validatedData['unidadproductoentrada_id'] : null;;
        $producto->unidad_salida_id = $validatedData['tipo_producto'] == 'productofinal' ? $validatedData['unidadproductosalida_id']:null;

        $producto->save();
        return redirect()->route('productos.index')->with('success', 'Producto actualizado con éxito!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($producto_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $producto = producto::find($producto_id);
        $producto->del = "S";
        $papelera = new PapeleraController();
        $papelera->agregarAPapelera($producto->nombre, "productos", $producto_id, "id");
        $producto->save();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente.');
    }

    public function buscar($receibedQuery)
    {
        $query = $receibedQuery;
        $productos = Producto::where('nombre', 'LIKE', "%{$query}%")
            ->orWhere('codigo', 'LIKE', "%{$query}%")
            ->get(['id', 'codigo', 'nombre', 'precio_venta']);
        return response()->json($productos);
    }

    public function verificarExistenciasProductos($codigo_producto)
    {
        $producto = producto::where('codigo', $codigo_producto)->first();
        if($producto->tipo_producto === "PRODUCTO FINAL"){
            $existencias = DB::table('lotes')
            ->selectRaw('IFNULL(SUM(cantidad), 0) as total_cantidad')
            ->where('codigo_producto', $codigo_producto)
            ->value('total_cantidad');
            return $existencias;
        }else{
            return 1;
        }
    }

        public function verificarExistenciasProductosPorId($codigo_producto)
    {
        $producto = producto::where('id', $codigo_producto)->first();
        if($producto->tipo_producto === "PRODUCTO FINAL"){
            $existencias = DB::table('lotes')
            ->selectRaw('IFNULL(SUM(cantidad), 0) as total_cantidad')
            ->where('codigo_producto', $producto->codigo)
            ->value('total_cantidad');
            return $existencias;
        }else{
            return 1;
        }
    }

    public function verificarTipoProducto($cod){
        $producto = producto::where('codigo', $cod)->first();
        return $producto->tipo_producto;
    }

    public function verificarPreciosProductos($codigo_producto)
    {
        $precio = Lote::where('codigo_producto', $codigo_producto)
            ->latest('id')
            ->value('precio_compra');
        $prese = unidadinsumo::where('id', producto::where('codigo', $codigo_producto)->first()->unidad_entrada_id)->first()->contiene;
        $precio = $precio / intval($prese);
        return $precio;
    }

    public function getUnidadesInsumo($codigo_producto)
    {
        $unidad_entrada = unidadinsumo::where('id', producto::where('codigo', $codigo_producto)->first()->unidad_entrada_id)->first()->contiene;
        $unidad_salida = unidadinsumo::where('id', producto::where('codigo', $codigo_producto)->first()->unidad_salida_id)->first()->contiene;
        return response()->json([
            'entrada' => $unidad_entrada,
            'salida' => $unidad_salida
        ]);
    }

    public function existenciasProductos(){
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        if ($this->rol->reimprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }

        return view('productos.existencias');
    }

    public function productosmasVendidos(){
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        if ($this->rol->reimprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }

        return view('productos.masvendidos');
    }
}
