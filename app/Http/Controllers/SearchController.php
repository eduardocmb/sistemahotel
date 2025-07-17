<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function show(Request $request)
    {
        $data = trim($request->valor);

        $result = DB::table('productos')
            ->join('impuestos', 'productos.impuesto_id', '=', 'impuestos.id')
            ->join('unidadinsumos', 'productos.unidad_entrada_id', '=', 'unidadinsumos.id')
            ->select(
                'productos.*',
                'impuestos.porcentaje as isv',
                'unidadinsumos.nombre as unidad_nombre',
                'unidadinsumos.contiene as contiene'
            )
            ->where('productos.nombre', 'like', '%' . $data . '%')
            ->orWhere('productos.codigo', 'like', '%' . $data . '%')
            ->where('productos.tipo_producto', 'PRODUCTO FINAL')
            ->limit(5)
            ->get();

        return response()->json([
            "estado" => 1,
            "result" => $result
        ]);
    }

    public function getinsumos(Request $request){
        $data = trim($request->valor);

        $result = DB::table('insumos')
            ->join('impuestos', 'insumos.impuesto_id', '=', 'impuestos.id')
            ->join('unidadinsumos', 'insumos.unidad_entrada_id', '=', 'unidadinsumos.id')
            ->select(
                'insumos.*',
                'impuestos.porcentaje as isv',
                'unidadinsumos.nombre as unidad_nombre',
                'unidadinsumos.contiene as contiene'
            )
            ->where('insumos.nombre', 'like', '%' . $data . '%')
            ->orWhere('insumos.codigo', 'like', '%' . $data . '%')
            ->limit(5)
            ->get();
        return response()->json([
            "estado" => 1,
            "result" => $result
        ]);
    }
}
