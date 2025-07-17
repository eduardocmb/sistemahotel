<?php

namespace App\Http\Controllers;

use App\Models\cai;
use App\Models\correlativo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CorrelativoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(correlativo $correlativo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(correlativo $correlativo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, correlativo $correlativo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(correlativo $correlativo)
    {
        //
    }

    public function generateClientCode($key, $table, $prefix = "")
    {
        $correlativo = $this->generateId($key, $table, $prefix);

        return response()->json(['codigo' => $correlativo]);
    }

    private function generateId($key, $table, $prefix = "")
    {
        $id = "";
        $success = false;

        while (!$success) {
            $current = DB::table('correlativos')->where('codigo', $key)->value('last');

            if ($current === null) {
                $current = 1;
            } else {
                $current++;
            }

            $correlativo = $prefix . $this->correlativo($current);

            $exists = DB::table($table)->where('codigo', $correlativo)->exists();

            if (!$exists) {
                $id = $correlativo;
                $success = true;
            } else {
                $current++;
            }
        }

        return $id;
    }

    public function correlativo($id)
    {
        return sprintf("%06d", $id);
    }

    public function incrementarConPrefijo($valor)
    {
        if (preg_match('/^(\D*)(\d+)$/', $valor, $coincidencias)) {
            $prefijo = $coincidencias[1];
            $numero = $coincidencias[2];
            $numeroSumado = str_pad((int)$numero + 1, strlen($numero), '0', STR_PAD_LEFT);
            return $prefijo . $numeroSumado;
        } elseif (preg_match('/^\d+$/', $valor)) {
            return str_pad((int)$valor + 1, strlen($valor), '0', STR_PAD_LEFT);
        }

        return $valor;
    }
    public function decrementarConPrefijo($valor)
    {
        if (preg_match('/^(\D*)(\d+)$/', $valor, $coincidencias)) {
            $prefijo = $coincidencias[1];
            $numero = $coincidencias[2];

            $numeroRestado = str_pad((int)$numero - 1, strlen($numero), '0', STR_PAD_LEFT);

            if ((int)$numero - 1 < 0) {
                throw new \Exception("No se puede restar, el valor resultante sería negativo.");
            }

            return $prefijo . $numeroRestado;
        } elseif (preg_match('/^\d+$/', $valor)) {
            $numeroRestado = str_pad((int)$valor - 1, strlen($valor), '0', STR_PAD_LEFT);

            if ((int)$valor - 1 < 0) {
                throw new \Exception("No se puede restar, el valor resultante sería negativo.");
            }

            return $numeroRestado;
        }

        return $valor;
    }


    public function validateBill($codigo)
    {
        $correlativo = (int) DB::table('correlativos')
            ->where('codigo', $codigo)
            ->value('last');

        $ultimo = (int) DB::table('cais')
            ->where('estado', 'ACTIVO')
            ->value('numerofinal');

        $restan = $ultimo - $correlativo;

        if ($restan <= 100) {
            $msg = "ESTÁ LLEGANDO AL FINAL DE SU LIMITE DE FACTURACIÓN, TIENE " . $restan . " FACTURAS DISPONIBLES, CONTACTAR AL ADMINISTRADOR!";
            session()->flash('message', $msg);
        }

        if ($correlativo < $ultimo) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function getNextCorrelativoFact($target)
    {
        if ($target == "SAR") {
            $cai = cai::where('estado', 'ACTIVO')->first();

            if ($cai) {
                $correlativoNuminicial = $cai->numeroinicial;
                $formato = $this->decrementarConPrefijo($correlativoNuminicial);
                $prefijo = $cai->prefijo;

                $correlativo = correlativo::where('codigo', 'CAIS')->first();
                $newCorrelativo = $correlativo ? intval($correlativo->last) + 1 : 1;

                $nextCodigoFact = $prefijo . str_pad($formato + $newCorrelativo, 8, '0', STR_PAD_LEFT);

                return $nextCodigoFact;
            }
        } elseif ($target == "INTERNO") {
            $cai = cai::where('estado', 'ACTIVO')->first();

            if ($cai) {
                $correlativoNuminicial = $cai->numeroinicial;
                $formato = $this->decrementarConPrefijo($correlativoNuminicial);
                $prefijo = $cai->prefijo;

                $correlativo = correlativo::where('codigo', 'CAII')->first();
                $newCorrelativo = $correlativo ? intval($correlativo->last) + 1 : 1;

                $nextCodigoFact = $prefijo . str_pad($formato + $newCorrelativo, 8, '0', STR_PAD_LEFT);

                return $nextCodigoFact;
            }
        }
    }
}
