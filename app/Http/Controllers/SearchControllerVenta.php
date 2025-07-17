<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchControllerVenta extends Controller
{
    public function show(Request $request)
    {
        $data = trim($request->valor);

        $result = DB::table('productos')
            ->join('impuestos', 'productos.impuesto_id', '=', 'impuestos.id')
            ->leftJoin('unidadinsumos', 'productos.unidad_salida_id', '=', 'unidadinsumos.id')
            ->select(
                'productos.*',
                'impuestos.porcentaje as isv',
                'unidadinsumos.nombre as unidad_nombre',
                'unidadinsumos.contiene as contiene'
            )
            ->where('productos.nombre', 'like', '%' . $data . '%')
            ->orWhere('productos.codigo', 'like', '%' . $data . '%')
            ->limit(5)
            ->get();
        return response()->json([
            "estado" => 1,
            "result" => $result
        ]);
    }
}
