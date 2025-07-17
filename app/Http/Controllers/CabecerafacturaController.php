<?php

namespace App\Http\Controllers;

use App\Models\aperturasCajas;
use App\Models\cabecerafactura;
use App\Models\cai;
use App\Models\cliente;
use App\Models\configuracion;
use App\Models\correlativo;
use App\Models\detallefactura;
use App\Models\lote;
use App\Models\producto;
use App\Models\role;
use App\Models\unidadinsumo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Luecano\NumeroALetras\NumeroALetras;

class CabecerafacturaController extends Controller
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
        return view('facturacion.create');
    }

    public function anularSar($num_factura){
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $kardex = new KardexController();
        $factura = cabecerafactura::where('factnum', $num_factura)->first();
        $factura->anular = 'S';
        $factura->save();

        $prods = detallefactura::where('factnum', $num_factura)->get();
        foreach($prods as $prod){
            $codigo = $prod->codproducto;
            $producto = producto::where('codigo', $codigo)->first();
            if($producto->tipo_producto === "PRODUCTO FINAL"){
                $unidad_id = producto::where('codigo', $codigo)->first()->unidad_salida_id;
                $contiene = unidadinsumo::findOrFail($unidad_id)->contiene;
                $lote = Lote::where('id', $prod->idlote)->first();
                $kardex->setKardex($num_factura, 'ENTRADA', 'ANULACION DE FACTURA N.' . $num_factura, $codigo, $lote->cantidad, (intval($contiene)*intval($prod->cant)), 0, intval($lote->cantidad) + (intval($contiene)*intval($prod->cant)));
                $lote->cantidad = intval($lote->cantidad) + (intval($contiene)*intval($prod->cant));

                $lote->save();
            }
        }
        return redirect()->route('facturaciones.index')->with('success', 'Factura Anulada Exitosamente');
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
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        //   dd($request);
        $aLetras = new NumeroALetras();
        //dd($aLetras->toMoney('12345.98', 2, "Lempiras", "Centavos"));

        $correlativoController = new CorrelativoController();
        $nextCodigo = $correlativoController->getNextCorrelativoFact('SAR');
        $facturas = cabecerafactura::where('factnum', $nextCodigo)->first();

        while ($facturas) {
            $correlativo = correlativo::where('codigo', 'CAIS')->first();
            if ($correlativo) {
                $correlativo->increment('last', 1);
                $nextCodigo = $correlativoController->getNextCorrelativoFact('SAR');
            }

            $facturas = cabecerafactura::where('factnum', $nextCodigo)->first();
        }
        $kardex = new KardexController();
        $cabfactura = new cabecerafactura();
        $cabfactura->factnum = $nextCodigo;
        $cabfactura->apnum = aperturasCajas::where('fecha', Carbon::today())
            ->where('user_id', Auth::user()->id)
            ->where('turno_id', $request->turno)
            ->where('caja_id', $request->caja)
            ->where('estado', 'ABIERTA')
            ->first()->codigo_apertura;
        $cabfactura->fecha = Carbon::today();
        $cabfactura->idcai = cai::where('estado', 'ACTIVO')->first()->codigo;
        $cabfactura->pago = $request->tipopago;
        $cabfactura->turno_id = $request->turno;
        $cabfactura->caja_id = $request->caja;
        $cabfactura->codigo_cliente = $request->codigocliente;
        $cabfactura->cliente = cliente::where('codigo_cliente', $request->codigocliente)->first()->nombre_completo;
        $cabfactura->rtn = $request->rtn;
        $cabfactura->impoexon = $request->importeExonerado;
        $cabfactura->impoexen = $request->importeExento;
        $cabfactura->impograv15 = $request->importeGrav15;
        $cabfactura->impograv18 = $request->importeGrav18;
        $cabfactura->isv15 = $request->isv15;
        $cabfactura->isv18 = $request->isv18;
        $cabfactura->descto = $request->descuento;
        $cabfactura->total = $request->total;
        $cabfactura->enletras = $aLetras->toMoney($request->total, 2, "Lempiras", "Centavos");
        $cabfactura->usuario = Auth::user()->username;
        // Asignar métodos de pago
        if ($request->tipopago == "EFECTIVO") {
            $cabfactura->efectivo = $request->montorecibido;
            $cabfactura->tarjeta = "0.00";
            $cabfactura->transferencia = "0.00";
            $cabfactura->cambio = $request->cambioadar;
        } elseif ($request->tipopago == "TARJETA") {
            $cabfactura->tarjeta = $request->total;
            $cabfactura->efectivo = "0.00";
            $cabfactura->transferencia = "0.00";
            $cabfactura->cambio = "0.00";
        } elseif ($request->tipopago == "TRANSFERENCIA") {
            $cabfactura->transferencia = $request->total;
            $cabfactura->efectivo = "0.00";
            $cabfactura->tarjeta = "0.00";
            $cabfactura->cambio = "0.00";
        }
        $cabfactura->idvendedor = "NA";
        $cabfactura->comision = "0";
        $cabfactura->liq = "N";
        $cabfactura->anular = "N";
        $cabfactura->save();

        $productos = $request->producto;
        $loteid  = null;

        foreach ($productos as $index => $producto) {
            $detalle = new detallefactura();
            $detalle->factnum = $cabfactura->factnum;
            $detalle->pos = $index;
            $detalle->codproducto = $request->codigo[$index];
            $detalle->descripcion = $request->producto[$index];
            $detalle->cant = $request->cantidad[$index];
            $detalle->precio = $request->precio_unitario[$index];
            $detalle->descto = $request->descuento_prod[$index];
            $detalle->total = $request->cantidad[$index] * $request->precio_unitario[$index];
            $detalle->comision = 0.00;

            $productoTipo = producto::where('codigo', $request->codigo[$index])->first()->tipo_producto;
            if ($productoTipo === "PRODUCTO FINAL") {
                $productoContieneEntrada = unidadinsumo::findOrfail(producto::where('codigo', $request->codigo[$index])->first()->unidad_entrada_id)->contiene;
                $productoContieneSalida = unidadinsumo::findOrfail(producto::where('codigo', $request->codigo[$index])->first()->unidad_salida_id)->contiene;
                $totalVentaNoDescuento = $request->precio_unitario[$index] * $request->cantidad[$index];

                $actuales = 0;
                $lotes = lote::where('codigo_producto', $request->codigo[$index])->get();
                foreach ($lotes as $lot) {
                    $actuales += floatval($lot->cantidad);
                }
                $nuevos = $actuales - ($detalle->cant * $productoContieneSalida);
                $kardex->setKardex($cabfactura->factnum, 'SALIDA', 'SALIDA DE ' . $detalle->descripcion, $request->codigo[$index], $actuales, 0, ($detalle->cant * $productoContieneSalida), $nuevos);

                $lote_prod = lote::where('codigo_producto', $detalle->codproducto)
                    ->orderBy('fecha')
                    ->where('cantidad', '>', 0)
                    ->first();

                $utilidadTotal = (
                    ($request->precio_unitario[$index] - ($lote_prod->precio_compra / $lote_prod->cant_comprada))
                ) * ($request->cantidad[$index] * $productoContieneSalida);
            }

            $detalle->utilidad = $productoTipo === "PRODUCTO FINAL" ? $utilidadTotal - $request->descuento_prod[$index] : $detalle->total;
            if ($productoTipo == "PRODUCTO FINAL") {

                $lotes = lote::where('codigo_producto', $detalle->codproducto)
                    ->orderBy('fecha')
                    ->where('cantidad', '>', 0)
                    ->get();

                if (!$lotes->isEmpty()) {
                    $cantidad_a_restaurar = ($detalle->cant * $productoContieneSalida);
                    foreach ($lotes as $lote) {
                        if ($cantidad_a_restaurar <= 0) break;

                        $cantidad_del_lote = min($lote->cantidad, $cantidad_a_restaurar);
                        $lote->cantidad -= $cantidad_del_lote;
                        $lote->save();

                        $loteid = $lote->id;
                        $idsLotesEncontrados[] = $lote->id;

                        $cantidad_a_restaurar -= $cantidad_del_lote;

                        if ($cantidad_a_restaurar <= 0) {
                            break;
                        }
                    }
                }
            }
            $detalle->idlote = $loteid;
            $detalle->save();
        }

        $correlativo = correlativo::where('codigo', 'CAIS')->first();
        if ($correlativo) {
            $correlativo->increment('last', 1);
        }
        $imprimir_flag = configuracion::where('codigo', 'PRFAC')->first()?->valor == "S" ? true : false;
        if ($imprimir_flag) {
            return redirect()->route('facturas.rptfacturasar', $cabfactura->factnum)->with('etiqueta', 'ORIGINAL');
        } else {
            return redirect()->back()->with('success', 'Factura creada exitosamente');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(cabecerafactura $cabecerafactura)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($num_factura)
    {
        $factura = cabecerafactura::select(
            'cabecerafacturas.*',
            'detallefacturas.*',
            'cajas.numcaja',
            'turnos.turno',
            'clientes.*',
            'productos.*',
            'cabecerafacturas.total as totalfactura'
        )
            ->join('detallefacturas', 'cabecerafacturas.factnum', '=', 'detallefacturas.factnum')
            ->join('cajas', 'cabecerafacturas.caja_id', '=', 'cajas.id')
            ->join('turnos', 'cabecerafacturas.turno_id', '=', 'turnos.id')
            ->join('clientes', 'cabecerafacturas.codigo_cliente', '=', 'clientes.codigo_cliente')
            ->join('productos', 'detallefacturas.codproducto', '=', 'productos.codigo')
            ->where('cabecerafacturas.factnum', $num_factura)
            ->get();

        return view('facturacion.edit', compact('factura'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, cabecerafactura $cabecerafactura)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cabecerafactura $cabecerafactura)
    {
        //
    }
}
