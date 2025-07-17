<?php

namespace App\Http\Controllers;

use App\Models\cliente;
use App\Models\correlativo;
use App\Models\role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
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
            'codigo' => 'CLIE',
            'description' => 'Correlativo de Clientes',
        ], [
            'last' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return view('huespedes.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        correlativo::firstOrCreate([
            'codigo' => 'CLIE',
            'description' => 'Correlativo de Clientes',
        ], [
            'last' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        return view('huespedes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        // Validación de los datos del formulario
        $request->validate([
            'codigo_cliente' => 'required|max:9|unique:clientes',
            'rtn' => 'required|max:14|unique:clientes',
            'nombre_completo' => 'required|string|max:255',
            'tipo_id' => 'required|string|max:50',
            'identificacion' => 'required|string|max:50|unique:clientes',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clientes,email',
        ], [
            'codigo_cliente.required' => 'El código de cliente es obligatorio.',
            'codigo_cliente.max' => 'El código de cliente no debe exceder los 9 caracteres.',
            'rtn.max' => 'El RTN del cliente no debe exceder los 14 caracteres.',
            'nombre_completo.required' => 'El nombre completo es obligatorio.',
            'nombre_completo.max' => 'El nombre completo no debe exceder los 255 caracteres.',
            'tipo_id.required' => 'El tipo de identificación es obligatorio.',
            'identificacion.required' => 'La identificación es obligatoria.',
            'rtn.required' => 'El RTN es obligatorio.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'direccion.required' => 'La dirección es obligatoria.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'identificacion.unique' => 'Esta identificación ya está registrada.',
            'rtn.unique' => 'Este RTN ya está registrado.',
            'codigo_cliente.unique' => 'Este código ya está registrado.'
        ]);

        $huesped = new cliente();
        $huesped->codigo_cliente = $request->codigo_cliente;
        $huesped->nombre_completo = $request->nombre_completo;
        $huesped->rtn = $request->rtn;
        $huesped->tipo_id = $request->tipo_id;
        $huesped->identificacion = $request->identificacion;
        $huesped->telefono = $request->telefono;
        $huesped->direccion = $request->direccion;
        $huesped->email = $request->email;

        if ($request->has('chkGenerarAuto')) {
            $correlativo = correlativo::where('codigo', 'CLIE')->first();
            if ($correlativo) {
                $correlativo->increment('last', 1);
            }
        }

        // Guardar el cliente en la base de datos
        $huesped->save();

        // Redirigir al usuario con un mensaje de éxito
        return redirect()->route('huespedes.index')->with('success', 'Huésped registrado correctamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show($huesped_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $huesped = cliente::findOrFail($huesped_id);
        return view('huespedes.destroy', compact('huesped'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($huesped_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $huesped = cliente::findOrFail($huesped_id);
        return view('huespedes.edit', compact('huesped'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($huesped_id, Request $request)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        // dd($huesped_id);
        $request->validate([
            'codigo_cliente' => 'required|max:9',
            'nombre_completo' => 'required|string|max:255',
            'tipo_id' => 'required|string|max:50',
            'rtn' => 'required|max:14|unique:clientes,rtn,' . $huesped_id . ',id',
            'identificacion' => 'required|string|max:50|unique:clientes,identificacion,' . $huesped_id . ',id',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clientes,email,' . $huesped_id . ',id',
        ], [
            'codigo_cliente.required' => 'El código de cliente es obligatorio.',
            'codigo_cliente.max' => 'El código de cliente no debe exceder los 9 caracteres.',
            'nombre_completo.required' => 'El nombre completo es obligatorio.',
            'nombre_completo.max' => 'El nombre completo no debe exceder los 255 caracteres.',
            'tipo_id.required' => 'El tipo de identificación es obligatorio.',
            'identificacion.required' => 'La identificación es obligatoria.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'direccion.required' => 'La dirección es obligatoria.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'identificacion.unique' => 'Esta identificación ya está registrada.',
            'rtn.max' => 'El RTN del cliente no debe exceder los 14 caracteres.',
            'rtn.required' => 'El RTN es obligatorio.',
            'rtn.unique' => 'Este RTN ya está registrado.',
        ]);

        $huesped = cliente::findOrFail($huesped_id);
        $huesped->nombre_completo = $request->nombre_completo;
        $huesped->rtn = $request->rtn;
        $huesped->tipo_id = $request->tipo_id;
        $huesped->identificacion = $request->identificacion;
        $huesped->telefono = $request->telefono;
        $huesped->direccion = $request->direccion;
        $huesped->email = $request->email;

        $huesped->save();

        // Redirigir al usuario con un mensaje de éxito
        return redirect()->route('huespedes.index')->with('success', 'Huésped actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($cliente_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $cliente = cliente::findOrFail($cliente_id);

        $cliente->del = "S";
        $papelera = new PapeleraController();
        $papelera->agregarAPapelera($cliente->nombre_completo, "clientes", $cliente_id, "id");
        $cliente->save();
        return redirect()->route('huespedes.index')->with('success', 'Huésped eliminado correctamente.');
    }

    public function getHuespedeshabituales()
    {
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        if ($this->rol->reimprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }

        return view('huespedes.huespedeshabituales');
    }

    public function getHuespedesXDia(){
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        if ($this->rol->reimprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }

        return view('huespedes.huespedesxdia');
    }

    public function getHuespedesXFecha(){
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        if ($this->rol->reimprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }

        return view('huespedes.huespedesxfechas');
    }

    public function getHuespedesConMasIngresos(){
        if ($this->rol->imprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        if ($this->rol->reimprimir == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }

        return view('huespedes.huespedesconmasingresos');
    }
}
