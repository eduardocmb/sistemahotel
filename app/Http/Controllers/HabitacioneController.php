<?php

namespace App\Http\Controllers;

use App\Models\habitacione;
use App\Models\role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HabitacioneController extends Controller
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

        return view('habitaciones.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        return view('habitaciones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $request->validate([
            'numero_habitacion' => 'required|unique:habitaciones|string|max:255',
            'tipo_habitacion' => 'required|string|max:20',
            'descripcion' => 'nullable|string',
            'precio_diario' => 'required|numeric',
            'estado' => 'nullable|string|max:50',
            'capacidad' => 'required|integer',
        ], [
            'numero_habitacion.required' => 'El número de habitación es obligatorio.',
            'numero_habitacion.unique' => 'El número de habitación ya está registrado.',
            'numero_habitacion.string' => 'El número de habitación debe ser una cadena de texto.',
            'numero_habitacion.max' => 'El número de habitación no debe exceder los 255 caracteres.',
            'tipo_habitacion.required' => 'El tipo de habitación es obligatorio.',
            'tipo_habitacion.string' => 'El tipo de habitación debe ser una cadena de texto.',
            'tipo_habitacion.max' => 'El tipo de habitación no debe exceder los 20 caracteres.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'precio_diario.required' => 'El precio diario es obligatorio.',
            'precio_diario.numeric' => 'El precio diario debe ser un número.',
            'estado.string' => 'El estado debe ser una cadena de texto.',
            'estado.max' => 'El estado no debe exceder los 50 caracteres.',
            'capacidad.required' => 'La capacidad es obligatoria.',
            'capacidad.integer' => 'La capacidad debe ser un número entero.',
        ]);

        $habitacion = new habitacione();
        $habitacion->numero_habitacion = $request->numero_habitacion;
        $habitacion->tipo_habitacion = $request->tipo_habitacion;
        $habitacion->descripcion = $request->descripcion;
        $habitacion->precio_diario = $request->precio_diario;
        $habitacion->estado = $request->estado;
        $habitacion->capacidad = $request->capacidad;
        $habitacion->observaciones = $request->observaciones;
        $habitacion->save();

        return redirect()->route('habitaciones.index')->with('success', 'Habitación creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($habitacion_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $habitacion = habitacione::findOrFail($habitacion_id);
        return view('habitaciones.destroy', compact('habitacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($habitacione_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $habitacion = habitacione::findOrFail($habitacione_id);
        return view('habitaciones.edit', compact('habitacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $habitacion_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $request->validate([
            'numero_habitacion' => 'required|string|max:255,unique:habitaciones,'.$habitacion_id,
            'tipo_habitacion' => 'required|string|max:20',
            'descripcion' => 'nullable|string',
            'precio_diario' => 'required|numeric',
            'estado' => 'nullable|string|max:50',
            'capacidad' => 'required|integer',
        ], [
            'numero_habitacion.required' => 'El número de habitación es obligatorio.',
            'numero_habitacion.unique' => 'El número de habitación ya está registrado.',
            'numero_habitacion.string' => 'El número de habitación debe ser una cadena de texto.',
            'numero_habitacion.max' => 'El número de habitación no debe exceder los 255 caracteres.',
            'tipo_habitacion.required' => 'El tipo de habitación es obligatorio.',
            'tipo_habitacion.string' => 'El tipo de habitación debe ser una cadena de texto.',
            'tipo_habitacion.max' => 'El tipo de habitación no debe exceder los 20 caracteres.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'precio_diario.required' => 'El precio diario es obligatorio.',
            'precio_diario.numeric' => 'El precio diario debe ser un número.',
            'estado.string' => 'El estado debe ser una cadena de texto.',
            'estado.max' => 'El estado no debe exceder los 50 caracteres.',
            'capacidad.required' => 'La capacidad es obligatoria.',
            'capacidad.integer' => 'La capacidad debe ser un número entero.',
        ]);

        $habitacion = habitacione::findOrFail($habitacion_id);
        $habitacion->numero_habitacion = $request->numero_habitacion;
        $habitacion->tipo_habitacion = $request->tipo_habitacion;
        $habitacion->descripcion = $request->descripcion;
        $habitacion->precio_diario = $request->precio_diario;
        $habitacion->estado = $request->estado;
        $habitacion->capacidad = $request->capacidad;
        $habitacion->observaciones = $request->observaciones;
        $habitacion->save();

        return redirect()->route('habitaciones.index')->with('success', 'Habitación actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($habitacione_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $habitacion = habitacione::findOrFail($habitacione_id);

        $habitacion->del = "S";
        $papelera = new PapeleraController();
        $papelera->agregarAPapelera($habitacion->numero_habitacion, "habitaciones", $habitacione_id, "id");
        $habitacion->save();
        return redirect()->route('habitaciones.index')->with('success', 'Habitación eliminada correctamente.');
    }
}
