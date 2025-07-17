<?php

namespace App\Http\Controllers;

use App\Models\configuracion;
use App\Models\role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfiguracionController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $configuraciones = configuracion::all();
        return view('sistema.configuracion.create', compact('configuraciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $taman ="";
        if($request->config_pagina == "ticket"){
            $taman = "TICKET";
        }elseif($request->config_pagina == "carta"){
            $taman = "CARTA";
        }elseif($request->config_pagina == "media_carta"){
            $taman= "MEDIA CARTA";
        }

        $tamanRecibo ="";
        if($request->recibo_estado == "ticket"){
            $tamanRecibo = "TICKET";
        }elseif($request->recibo_estado == "carta"){
            $tamanRecibo = "CARTA";
        }

        $configuraciones = [
            'PRFAC' => $request->has('imprimir_factura') ? 'S' : 'N',
            'PRFCO' => $request->has('imprimir_copia') ? 'S' : 'N',
            'SCANB' => $request->has('usar_lector') ? 'S' : 'N',
            'AGRUP' => $request->has('agrupar_codigo') ? 'S' : 'N',
            'EDTPR' => $request->has('editar_precio') ? 'S' : 'N',
            'TAMAN' => $taman,
            'TMREC' => $tamanRecibo
        ];

        foreach ($configuraciones as $codigo => $valor) {
            $config = Configuracion::where('codigo', $codigo)->first();

            if ($config) {
                $config->valor = $valor;
                $config->save();
            } else {
                Configuracion::create([
                    'codigo' => $codigo,
                    'detalle' => $this->getDetalle($codigo),
                    'valor' => $valor
                ]);
            }
        }

        return redirect()->back()->with('success', 'Configuraciones guardadas correctamente.');
    }

    private function getDetalle($codigo)
    {
        $detalles = [
            'PRFAC' => 'Imprimir Factura',
            'PRFCO' => 'Imprimir Copia',
            'SCANB' => 'Usar Lector de Código de Barras',
            'AGRUP' => 'Agrupar Código de Barras',
            'EDTPR' => 'Editar Precio al Facturar',
            'TAMAN' => 'Tamaño de Impresión',
            'TMREC' => 'Tamaño del Recibo'
        ];

        return $detalles[$codigo] ?? 'No disponible';
    }

    /**
     * Display the specified resource.
     */
    public function show(configuracion $configuracion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(configuracion $configuracion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Recibir las configuraciones que vienen del formulario
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(configuracion $configuracion)
    {
        //
    }
}
