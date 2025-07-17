<?php

namespace App\Http\Controllers;

use App\Models\departamento;
use App\Models\empleado;
use App\Models\role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpleadoController extends Controller
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
        return view('empleados.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $departamentos = departamento::where('del', 'N')->get();
        return view('empleados.create', compact('departamentos'));
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
            'dni' => 'required|unique:empleados,dni|max:20',
            'nombrecompleto' => 'required|max:255',
            'direccion' => 'nullable|max:255',
            'fechanac' => 'nullable|date',
            'fechaing' => 'nullable|date',
            'telefono' => 'nullable|max:20',
            'genero' => 'required|in:MASCULINO,FEMENINO',
            'departamento_id' => 'required|exists:departamentos,id',
            'tipo' => 'required|in:PERMANENTE,DIARIO,TEMPORAL,POR HORA',
            'salario' => 'required|numeric|min:1',
            'estado' => 'required|in:ACTIVO,INACTIVO',
        ], [
            'dni.required' => 'El campo N° Identificación es obligatorio.',
            'dni.unique' => 'El N° Identificación ya está registrado.',
            'dni.max' => 'El N° Identificación no puede tener más de 20 caracteres.',

            'nombrecompleto.required' => 'El campo Nombre Completo es obligatorio.',
            'nombrecompleto.max' => 'El Nombre Completo no puede tener más de 255 caracteres.',

            'direccion.max' => 'La Dirección no puede tener más de 255 caracteres.',

            'fechanac.date' => 'La Fecha de Nacimiento debe ser una fecha válida.',

            'fechaing.date' => 'La Fecha de Ingreso debe ser una fecha válida.',

            'telefono.max' => 'El Teléfono no puede tener más de 20 caracteres.',

            'genero.required' => 'Debe seleccionar un género.',
            'genero.in' => 'El género seleccionado no es válido.',

            'departamento_id.required' => 'Debe seleccionar un departamento.',
            'departamento_id.exists' => 'El departamento seleccionado no existe.',

            'tipo.required' => 'Debe seleccionar un tipo de contratación.',
            'tipo.in' => 'El tipo de contratación seleccionado no es válido.',

            'salario.required' => 'El salario es obligatorio.',
            'salario.numeric' => 'El salario debe ser un número.',
            'salario.min' => 'El salario debe ser mayor que 0.',

            'estado.required' => 'Debe seleccionar un estado.',
            'estado.in' => 'El estado seleccionado no es válido.',
        ]);

        $empleado = new Empleado();
        $empleado->dni = $request->dni;
        $empleado->nombrecompleto = strtoupper($request->nombrecompleto);
        $empleado->direccion = $request->direccion;
        $empleado->fechanac = $request->fechanac;
        $empleado->fechaingreso = $request->fechaing;
        $empleado->telefono = $request->telefono;
        $empleado->genero = $request->genero;
        $empleado->departamento_id = $request->departamento_id;
        $empleado->trabajotipo = $request->tipo;
        $empleado->salario = $request->salario;
        $empleado->estado = $request->estado;
        $empleado->save();

        return redirect()->route('empleados.index')->with('success', 'Empleado creado con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show($empleado_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $empleado = Empleado::findOrFail($empleado_id);
        $departamento = departamento::findOrFail($empleado->departamento_id);
        return view('empleados.destroy', compact('departamento', 'empleado'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($empleado_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $empleado = Empleado::findOrFail($empleado_id);
        $departamentos = departamento::where('del', 'N')->get();
        return view('empleados.edit', compact('departamentos', 'empleado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $empleado_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $request->validate([
            'nombrecompleto' => 'required|max:255',
            'direccion' => 'nullable|max:255',
            'fechanac' => 'nullable|date',
            'fechaing' => 'nullable|date',
            'telefono' => 'nullable|max:20',
            'genero' => 'required|in:MASCULINO,FEMENINO',
            'departamento_id' => 'required|exists:departamentos,id',
            'tipo' => 'required|in:PERMANENTE,DIARIO,TEMPORAL,POR HORA',
            'salario' => 'required|numeric|min:1',
            'estado' => 'required|in:ACTIVO,INACTIVO',
        ], [
            'nombrecompleto.required' => 'El campo Nombre Completo es obligatorio.',
            'nombrecompleto.max' => 'El Nombre Completo no puede tener más de 255 caracteres.',

            'direccion.max' => 'La Dirección no puede tener más de 255 caracteres.',

            'fechanac.date' => 'La Fecha de Nacimiento debe ser una fecha válida.',

            'fechaing.date' => 'La Fecha de Ingreso debe ser una fecha válida.',

            'telefono.max' => 'El Teléfono no puede tener más de 20 caracteres.',

            'genero.required' => 'Debe seleccionar un género.',
            'genero.in' => 'El género seleccionado no es válido.',

            'departamento_id.required' => 'Debe seleccionar un departamento.',
            'departamento_id.exists' => 'El departamento seleccionado no existe.',

            'tipo.required' => 'Debe seleccionar un tipo de contratación.',
            'tipo.in' => 'El tipo de contratación seleccionado no es válido.',

            'salario.required' => 'El salario es obligatorio.',
            'salario.numeric' => 'El salario debe ser un número.',
            'salario.min' => 'El salario debe ser mayor que 0.',

            'estado.required' => 'Debe seleccionar un estado.',
            'estado.in' => 'El estado seleccionado no es válido.',
        ]);

        $empleado = Empleado::findOrFail($empleado_id);
        $empleado->nombrecompleto = strtoupper($request->nombrecompleto);
        $empleado->direccion = $request->direccion;
        $empleado->fechanac = $request->fechanac;
        $empleado->fechaingreso = $request->fechaing;
        $empleado->telefono = $request->telefono;
        $empleado->genero = $request->genero;
        $empleado->departamento_id = $request->departamento_id;
        $empleado->trabajotipo = $request->tipo;
        $empleado->salario = $request->salario;
        $empleado->estado = $request->estado;
        $empleado->save();

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($empleado_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $empleado = Empleado::findOrFail($empleado_id);
        $empleado->del = "S";
        $papelera = new PapeleraController();
        $papelera->agregarAPapelera($empleado->nombrecompleto, "empleados", $empleado_id, "id");
        $empleado->save();
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado con éxito.');
    }

    public function getInfoEmpleado($id){
        $empleado = empleado::findOrFail($id);
        return $empleado;
    }
}
