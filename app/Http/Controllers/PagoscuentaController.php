<?php

namespace App\Http\Controllers;

use App\Models\cuentasporpagar;
use App\Models\pagoscuenta;
use App\Models\role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagoscuentaController extends Controller
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
        $request->validate([
            'cuenta_id' => 'required|exists:cuentasporpagars,id',
            'fecha' => 'required|date',
            'monto_pagado' => 'required|numeric|min:0.01',
            'tipo_pago' => 'required|string|max:20',
            'notas' => 'nullable|string',
        ], [
            'cuenta_id.required' => 'La cuenta por pagar es obligatoria.',
            'cuenta_id.exists' => 'La cuenta por pagar seleccionada no es válida.',
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'El formato de la fecha no es válido.',
            'monto_pagado.required' => 'El monto pagado es obligatorio.',
            'monto_pagado.numeric' => 'El monto pagado debe ser un número.',
            'monto_pagado.min' => 'El monto pagado debe ser mayor a 0.',
            'tipo_pago.required' => 'El tipo de pago es obligatorio.',
            'tipo_pago.string' => 'El tipo de pago debe ser una cadena de texto.',
            'tipo_pago.max' => 'El tipo de pago no puede exceder los 20 caracteres.',
            'notas.string' => 'Las notas deben ser texto.',
        ]);
        $cuenta = cuentasporpagar::findOrFail($request->cuenta_id);

        $pagos = pagoscuenta::where('cuentasporpagar_id', $cuenta->id)->get();
        $saldo_pendiente = $pagos->isNotEmpty()
            ? $cuenta->monto_total - $pagos->sum('monto_pagado')
            : $cuenta->monto_total;

        if ($saldo_pendiente <= 0) {
            $cuenta->estado = "PAGADO";
            $cuenta->save();

            return redirect()->back()->withInput()->with('error', 'No se puede abonar, esta cuenta ya está completamente pagada.');
        }

        if ($cuenta->estado == "PAGADO") {
            return redirect()->back()->withInput()->with('error', 'No se puede abonar, esta cuenta ya fue anteriormente pagada.');
        }

        if ($request->monto_pagado > $saldo_pendiente) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['monto_pagado' => 'El monto pagado no puede ser mayor al saldo pendiente.']);
        }

        if ($request->monto_pagado == $saldo_pendiente) {
            $cuenta->estado = "PAGADO";
            $cuenta->save();
        }


        $pago = new PagosCuenta();
        $pago->cuentasporpagar_id = $request->cuenta_id;
        $pago->fecha = $request->fecha;
        $pago->monto_pagado = $request->monto_pagado;
        $pago->tipo_pago = $request->tipo_pago;
        $pago->user_id = Auth::user()->id;
        $pago->notas = $request->notas;
        $pago->save();

        return redirect()->route('cuentas.index')->with('success', 'El pago se registró correctamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show(pagoscuenta $pagoscuenta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(pagoscuenta $pagoscuenta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, pagoscuenta $pagoscuenta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(pagoscuenta $pagoscuenta)
    {
        //
    }
}
