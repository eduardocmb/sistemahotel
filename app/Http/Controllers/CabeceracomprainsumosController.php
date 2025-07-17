<?php

namespace App\Http\Controllers;

use App\Models\aperturasCajas;
use App\Models\cabeceracomprainsumos;
use App\Models\categoria;
use App\Models\correlativo;
use App\Models\cuentasporpagar;
use App\Models\detallecomprainsumos;
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

class CabeceracomprainsumosController extends Controller
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
        correlativo::firstOrCreate([
            'codigo' => 'CTPG',
            'description' => 'Correlativo de Cuentas por Pagar',
        ], [
            'last' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return view('insumos.compras.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        correlativo::firstOrCreate([
            'codigo' => 'CMIN',
            'description' => 'Correlativo de Compras de Insumos',
        ], [
            'last' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $impuestos = impuesto::all();
        $unidadproductos = unidadinsumo::all();
        $proveedores = proveedor::all();
        $categorias = categoria::all();

        return view('insumos.compras.create', compact('unidadproductos', 'proveedores', 'impuestos', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
     //   dd($request);
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }

        $productos = [];
        foreach ($request->codigo as $key => $codigo) {
            $productos[] = [
                'codigo' => $codigo,
                'producto' => $request->producto[$key],
                'cantidad' => $request->cantidad[$key],
                'precio_unitario' => $request->precio_unitario[$key],
                'total' => $request->total[$key],
            ];
        }

        $turnoId = aperturasCajas::where('user_id', Auth::user()->id)
            ->where('estado', 'ABIERTA')
            ->first()
            ->turno_id;

        $incluyeIsv = $request->has('precios_impuestos');
        $subtotal = 0;
        $isv = 0;
        $total = 0;

        try {
            $fechaHoy = now()->toDateString();

            foreach ($request->codigo as $key => $codigoProducto) {
                if (isset(
                    $request->precio_unitario[$key],
                    $request->cantidad[$key],
                    $request->presContiene[$key]
                )) {
                    $cantidadTotal = $request->cantidad[$key] * $request->presContiene[$key];

                    $lote = new loteinsumo();
                    $lote->fecha = $fechaHoy;
                    $lote->codigo_insumo = $codigoProducto;
                    $lote->precio_compra = $request->precio_unitario[$key];
                    $lote->cantidad = $cantidadTotal;
                    $lote->cant_comprada = $cantidadTotal;
                    $lote->fecha_vencimiento = null;
                    $lote->save();
                }
            }

            foreach ($request->producto as $index => $producto) {
                $precioUnitario = (float)$request->precio_unitario[$index];
                $isvProducto = (float)$request->isv[$index];
                $cantidad = (int)$request->cantidad[$index];

                $totalProducto = $precioUnitario * $cantidad;

                if ($isvProducto > 0) {
                    if ($incluyeIsv) {
                        $isv += $totalProducto - ($totalProducto / (1 + ($isvProducto / 100)));
                        $subtotal += $totalProducto / (1 + ($isvProducto / 100));
                    } else {
                        $isv += $totalProducto * ($isvProducto / 100);
                        $subtotal += $totalProducto;
                    }
                } else {
                    $isv += 0;
                    $subtotal += $totalProducto;
                }
            }

            $total = $subtotal + $isv;

            $cabeceraCompra = new cabeceracomprainsumos();
            $cabeceraCompra->codigocompra = $request->numero;
            $cabeceraCompra->proveedor_id = $request->proveedor;
            $cabeceraCompra->usuario_id = Auth::user()->id;
            $cabeceraCompra->fecha_compra = Carbon::today();
            $cabeceraCompra->turno_id = $turnoId;
            $cabeceraCompra->tipo_pago = $request->tipo_pago;
            $cabeceraCompra->numfactura = $request->factura;
            $cabeceraCompra->subtotal = $subtotal;
            $cabeceraCompra->impuesto = $isv;
            $cabeceraCompra->total_compra = $total;
            if($request->tipo_pago == "CREDITO"){
                $cuenta = new cuentasporpagar();
                $correlativoController = new CorrelativoController();
                $nextCodigo = $correlativoController->generateClientCode('CTPG', 'correlativos', 'CTA');
                $cuenta->proveedor_id = $request->proveedor;
                $cuenta->fecha = Carbon::today();
                $cuenta->codigo = $nextCodigo->getData()->codigo;
                $cuenta->numfactura = $request->factura;
                $cuenta->monto_total = $total;
                $cuenta->fecha_vencimiento = now()->addDays(31);
                $cuenta->estado = "PENDIENTE";
                $cuenta->user_id = Auth::user()->id;
                $cuenta->save();
                $correlativoCuenta = correlativo::where('codigo', 'CTPG')->first();
                if ($correlativoCuenta) {
                    $correlativoCuenta->increment('last', 1);
                }
            }
            $cabeceraCompra->save();

            foreach ($productos as $productoData) {
                $detalleCompra = new detallecomprainsumos();
                $detalleCompra->codigocompra_id = $cabeceraCompra->id;
                $prod = insumo::where('codigo', $productoData['codigo'])->first()->id;
               // dd($prod);
                $detalleCompra->producto_id = $prod;
                $detalleCompra->cantidad = $productoData['cantidad'];
                $detalleCompra->precio_unitario = $productoData['precio_unitario'];
                $detalleCompra->total_producto = $productoData['total'];
                $detalleCompra->save();
            }

            $correlativo = correlativo::where('codigo', 'CMIN')->first();
            if ($correlativo) {
                $correlativo->increment('last', 1);
            }

            return redirect()->route('insumo.index')->with('success', 'Compra registrada correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hubo un error al guardar los lotes: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(cabeceracomprainsumos $cabeceracomprainsumos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($cabeceracomprainsumos_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }

        $cabcompra = DB::table('cabeceracomprainsumos')->where('id', $cabeceracomprainsumos_id)->first();
        $detacompra = DB::table('detallecomprainsumos')->where('codigocompra_id', $cabeceracomprainsumos_id)->get();
        $productos = [];

        foreach ($detacompra as $prods) {
            $producto = insumo::findOrFail($prods->producto_id);
            $unidadInsumo = unidadInsumo::where('id', $producto->unidad_id)->first();
            $impuesto = impuesto::findOrFail($producto->impuesto_id);

            $productos[] = [
                'producto_id' => $producto->codigo,
                'producto' => $producto->nombre,
                'cantidad' => $unidadInsumo ? ($prods->cantidad) / $unidadInsumo->contiene : $prods->cantidad,
                'precio_compra' => $prods->precio_unitario,
                'isv' => $impuesto->porcentaje ?? 0,
                'total' => $prods->total_producto,
            ];
        }

        $impuestos = impuesto::all();
        $unidadproductos = unidadinsumo::all();
        $proveedores = proveedor::all();
        $categorias = categoria::all();

        return view('insumos.compras.edit', compact('productos','cabcompra','detacompra','unidadproductos', 'proveedores', 'impuestos', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, cabeceracomprainsumos $cabeceracomprainsumos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cabeceracomprainsumos $cabeceracomprainsumos)
    {
        //
    }

    public function rptGenerarCompra(){
        $cabcompras = DB::table('cabeceracabeceracomprainsumoss')->get();
        //$pdf = FacadePdf::loadView('rpts.rptcomprasproductos', compact('cabcompras'));
        //return $pdf->download('rpd_cabcompras.pdf');
        return view('rpts.rptcomprasproductos', compact('cabcompras'));
    }
}
