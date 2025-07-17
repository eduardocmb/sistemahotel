<?php

namespace App\Http\Controllers;

use App\Models\correlativo;
use App\Models\cuentasporpagar;
use App\Models\pagoscuenta;
use App\Models\proveedor;
use App\Models\role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CuentasporpagarController extends Controller
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
        return view('proveedores.cuentas.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        correlativo::firstOrCreate([
            'codigo' => 'CTPG',
            'description' => 'Correlativo de Cuentas por Pagar',
        ], [
            'last' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $proveedores = proveedor::where('del', 'N')->get();
        return view('proveedores.cuentas.create', compact('proveedores'));
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
            'codigo' => 'required|unique:cuentasporpagars,codigo|max:9',
            'fecha' => 'required|date',
            'proveedor_id' => 'required|exists:proveedors,id',
            'numfactura' => 'required|max:50',
            'monto_total' => 'required|numeric',
            'fecha_vencimiento' => 'nullable|date|after:fecha',
            'estado' => 'nullable|in:PENDIENTE,COMPLETADO',
            'notas' => 'nullable|string',
        ], [
            'codigo.required' => 'El campo código es obligatorio.',
            'codigo.unique' => 'El código ya está registrado.',
            'codigo.max' => 'El código no debe exceder los 9 caracteres.',
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha debe ser una fecha válida.',
            'proveedor_id.required' => 'El proveedor es obligatorio.',
            'proveedor_id.exists' => 'El proveedor seleccionado no existe.',
            'numfactura.required' => 'El número de factura es obligatorio.',
            'numfactura.max' => 'El número de factura no debe exceder los 50 caracteres.',
            'monto_total.required' => 'El monto total es obligatorio.',
            'monto_total.numeric' => 'El monto total debe ser un número.',
            'fecha_vencimiento.date' => 'La fecha de vencimiento debe ser una fecha válida.',
            'fecha_vencimiento.after' => 'La fecha de vencimiento debe ser posterior a la fecha de compra.',
            'estado.in' => 'El estado debe ser uno de los siguientes: PENDIENTE, COMPLETADO.',
            'notas.string' => 'Las notas deben ser texto.',
        ]);

        $cuentaPorPagar = new cuentasporpagar();

        $cuentaPorPagar->codigo = $request->codigo;
        $cuentaPorPagar->fecha = $request->fecha;
        $cuentaPorPagar->proveedor_id = $request->proveedor_id;
        $cuentaPorPagar->numfactura = $request->numfactura;
        $cuentaPorPagar->monto_total = $request->monto_total;
        $cuentaPorPagar->fecha_vencimiento = $request->fecha_vencimiento;
        $cuentaPorPagar->estado = $request->estado ?? 'PENDIENTE';
        $cuentaPorPagar->user_id = Auth::user()->id;
        $cuentaPorPagar->notas = $request->notas;

        $cuentaPorPagar->save();

        return redirect()->route('cuentas.index')
            ->with('success', 'Cuenta por pagar registrada exitosamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show($cuentasporpagar_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $cuenta = cuentasporpagar::findOrFail($cuentasporpagar_id);
        $proveedor = proveedor::findOrFail($cuenta->proveedor_id);
        return view('proveedores.cuentas.destroy', compact('cuenta', 'proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($cuentasporpagar_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $cuenta = cuentasporpagar::findOrFail($cuentasporpagar_id);
        $proveedores = proveedor::where('del', 'N')->get();
        return view('proveedores.cuentas.edit', compact('cuenta', 'proveedores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha' => 'required|date',
            'proveedor_id' => 'required|exists:proveedors,id',
            'numfactura' => 'required|max:50',
            'monto_total' => 'required|numeric',
            'fecha_vencimiento' => 'nullable|date|after:fecha',
            'estado' => 'nullable|in:PENDIENTE,PAGADO',
            'notas' => 'nullable|string',
        ], [
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha debe ser una fecha válida.',
            'proveedor_id.required' => 'El proveedor es obligatorio.',
            'proveedor_id.exists' => 'El proveedor seleccionado no existe.',
            'numfactura.required' => 'El número de factura es obligatorio.',
            'numfactura.max' => 'El número de factura no debe exceder los 50 caracteres.',
            'monto_total.required' => 'El monto total es obligatorio.',
            'monto_total.numeric' => 'El monto total debe ser un número.',
            'fecha_vencimiento.date' => 'La fecha de vencimiento debe ser una fecha válida.',
            'fecha_vencimiento.after' => 'La fecha de vencimiento debe ser posterior a la fecha de compra.',
            'estado.in' => 'El estado debe ser uno de los siguientes: PENDIENTE, COMPLETADO.',
            'notas.string' => 'Las notas deben ser texto.',
        ]);

        $cuentaPorPagar = cuentasporpagar::findOrFail($id);

        $cuentaPorPagar->fecha = $request->fecha;
        $cuentaPorPagar->proveedor_id = $request->proveedor_id;
        $cuentaPorPagar->numfactura = $request->numfactura;
        $cuentaPorPagar->monto_total = $request->monto_total;
        $cuentaPorPagar->fecha_vencimiento = $request->fecha_vencimiento;
        $cuentaPorPagar->estado = $request->estado ?? 'PENDIENTE';
        $cuentaPorPagar->user_id = Auth::user()->id;
        $cuentaPorPagar->notas = $request->notas;

        $cuentaPorPagar->save();

        return redirect()->route('cuentas.index')
            ->with('success', 'Cuenta por pagar actualizada exitosamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $cuenta = cuentasporpagar::findOrFail($id);
        $cuenta->delete();

        return redirect()->route('cuentas.index')->with('success', 'Cuenta eliminada con éxito.');
    }

    public function showCta($cta_number)
    {
        if ($this->rol->guardar == "N" || $this->rol->finanzas == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }

        $cuenta = cuentasporpagar::where('codigo', $cta_number)->first();
        if (!$cuenta) {
            return redirect()->route('dashboard')->with('error', 'La cuenta por pagar no fue encontrada.');
        }

        $pagos = pagoscuenta::where('cuentasporpagar_id', $cuenta->id)->get();
        $saldo_pendiente = $pagos->isNotEmpty()
            ? $cuenta->monto_total - $pagos->sum('monto_pagado')
            : $cuenta->monto_total;

        $proveedor = proveedor::findOrFail($cuenta->proveedor_id);

        return view('proveedores.cuentas.abonar', compact('cuenta', 'pagos','proveedor', 'saldo_pendiente'));
    }

}
