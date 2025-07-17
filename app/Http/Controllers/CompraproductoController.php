<?php

namespace App\Http\Controllers;

use App\Models\aperturasCajas;
use App\Models\cabeceracompraproducto;
use App\Models\categoria;
use App\Models\compraproducto;
use App\Models\correlativo;
use App\Models\cuentasporpagar;
use App\Models\detallecompraproducto;
use App\Models\impuesto;
use App\Models\lote;
use App\Models\producto;
use App\Models\proveedor;
use App\Models\role;
use App\Models\unidadinsumo;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompraproductoController extends Controller
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
        correlativo::firstOrCreate([
            'codigo' => 'CTPG',
            'description' => 'Correlativo de Cuentas por Pagar',
        ], [
            'last' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        if ($this->rol->ver_informacion == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        return view('productos.compras.index');
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
            'codigo' => 'CMPR',
            'description' => 'Correlativo de Compras de Productos',
        ], [
            'last' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        correlativo::firstOrCreate([
            'codigo' => 'CTPG',
            'description' => 'Correlativo de Cuentas por Pagar',
        ], [
            'last' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $impuestos = impuesto::all();
        $unidadproductos = unidadinsumo::all();
        $proveedores = proveedor::all();
        $categorias = categoria::all();

        return view('productos.compras.create', compact('unidadproductos', 'proveedores', 'impuestos', 'categorias'));
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
        $kardex = new KardexController();
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

                    $actuales = 0;
                    $lotes = lote::where('codigo_producto', $codigoProducto)->get();
                    foreach ($lotes as $lot) {
                        $actuales += floatval($lot->cantidad);
                    }
                    $nuevos = $actuales + $cantidadTotal;
                    $kardex->setKardex($request->numero, 'ENTRADA', 'COMPRA DE ' .  $request->producto[$key], $codigoProducto, $actuales, $cantidadTotal, (0), $nuevos);


                    $lote = new lote();
                    $lote->fecha = $fechaHoy;
                    $lote->codigo_producto = $codigoProducto;
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

            $cabeceraCompra = new cabeceracompraproducto();
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
                $detalleCompra = new detallecompraproducto();
                $detalleCompra->codigocompra_id = $cabeceraCompra->id;
                $prod = producto::where('codigo', $productoData['codigo'])->first()->id;
                // dd($prod);
                $detalleCompra->producto_id = $prod;
                $detalleCompra->cantidad = $productoData['cantidad'];
                $detalleCompra->precio_unitario = $productoData['precio_unitario'];
                $detalleCompra->total_producto = $productoData['total'];
                $detalleCompra->save();
            }

            $correlativo = correlativo::where('codigo', 'CMPR')->first();
            if ($correlativo) {
                $correlativo->increment('last', 1);
            }

            return redirect()->route('productos.index')->with('success', 'Compra registrada correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hubo un error al guardar los lotes: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(compraproducto $compraproducto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($compraproducto_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }

        $cabcompra = DB::table('cabeceracompraproductos')->where('id', $compraproducto_id)->first();
        $detacompra = DB::table('detallecompraproductos')->where('codigocompra_id', $compraproducto_id)->get();
        $productos = [];

        foreach ($detacompra as $prods) {
            $producto = producto::findOrFail($prods->producto_id);
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

        return view('productos.compras.edit', compact('productos', 'cabcompra', 'detacompra', 'unidadproductos', 'proveedores', 'impuestos', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, compraproducto $compraproducto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(compraproducto $compraproducto)
    {
        //
    }

    public function rptGenerarCompra()
    {
        $cabcompras = DB::table('cabeceracompraproductos')->get();
        //$pdf = FacadePdf::loadView('rpts.rptcomprasproductos', compact('cabcompras'));
        //return $pdf->download('rpd_cabcompras.pdf');
        return view('rpts.rptcomprasproductos', compact('cabcompras'));
    }
}
