<?php

namespace App\Http\Controllers;

use App\Models\correlativo;
use App\Models\proveedor;
use App\Models\role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProveedorController extends Controller
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
            'codigo' => 'PROV',
            'description' => 'Correlativo de Proveedores',
        ], [
            'last' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return view('proveedores.index');
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
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|max:20',
            'email' => 'required|email|max:255',
            'direccion' => 'required|string',
            'codigo_proveedor' => 'required|string|max:9',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'direccion.required' => 'La dirección es requerida.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'codigo_proveedor.required' => 'El código es obligatorio.',
            'codigo_proveedor.max' => 'El código no puede tener más de 9 caracteres.',
        ]);


        $proveedor = new proveedor();
        $proveedor->codigo = $validated['codigo_proveedor'];
        $proveedor->nombre = $validated['nombre'];
        $proveedor->telefono = $validated['telefono'];
        $proveedor->email = $validated['email'];
        $proveedor->direccion = $validated['direccion'];
        $proveedor->save();

        if ($request->has('chkGenerarAuto')) {
            $correlativo = correlativo::where('codigo', 'PROV')->first();
            if ($correlativo) {
                $correlativo->increment('last', 1);
            }
        }

        return redirect()->route('proveedores.index')->with('success', 'Proveedor registrado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(proveedor $proveedor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $proveedor = proveedor::findOrFail($id);
        return view('proveedores.edit', compact('proveedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|max:20',
            'email' => 'required|email|max:255',
            'direccion' => 'required|string',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'direccion.required' => 'La dirección es requerida.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
        ]);

        $proveedor = Proveedor::findOrFail($id);

        $proveedor->nombre = $validated['nombre'];
        $proveedor->telefono = $validated['telefono'];
        $proveedor->email = $validated['email'];
        $proveedor->direccion = $validated['direccion'];
        $proveedor->save();
        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($proveedor_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $prov = proveedor::findOrFail($proveedor_id);
        $prov->del = "S";
        $papelera = new PapeleraController();
        $papelera->agregarAPapelera($prov->nombre, "proveedors", $proveedor_id, "id");
        $prov->save();
        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado correctamente.');
    }
}
