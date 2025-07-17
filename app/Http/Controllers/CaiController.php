<?php

namespace App\Http\Controllers;

use App\Models\cai;
use App\Models\correlativo;
use App\Models\role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaiController extends Controller
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
            'codigo' => 'CAIS',
            'description' => 'Correlativo de CAIS',
        ], [
            'last' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        correlativo::firstOrCreate([
            'codigo' => 'CAII',
            'description' => 'Correlativo de CAIS interno',
        ], [
            'last' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return view('cais.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        if (cai::where('del', 'N')->where('estado', 'ACTIVO')->exists()) {
            return back()->with('error', 'Ya existe un CAI activo. Por favor, márquelo como CANCELADO o VENCIDO antes de crear uno nuevo.');
        }
        return view('cais.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        if (cai::where('del', 'N')->where('estado', 'ACTIVO')->exists()) {
            return redirect()->route('cais.index')->with('error', 'Ya existe un CAI activo. Por favor, márquelo como CANCELADO o VENCIDO antes de crear uno nuevo.');
        }

        $request->validate([
            'cai' => 'required|string|max:50|unique:cais',
            'prefijo' => 'required|string|max:50',
            'numeroinicial' => 'required|string|min:1',
            'numerofinal' => 'required|string|min:1|gte:numeroinicial',
            'fecharecibido' => 'required|date',
            'fechalimite' => 'required|date|after_or_equal:fecharecibido',
        ], [
            'cai.required' => 'El campo CAI es obligatorio.',
            'cai.max' => 'El CAI no puede exceder los 50 caracteres.',
            'cai.unique' => 'Este CAI ya está registrado.',
            'prefijo.required' => 'El campo prefijo es obligatorio.',
            'prefijo.max' => 'El prefijo no puede exceder los 50 caracteres.',
            'numeroinicial.required' => 'El número inicial es obligatorio.',
            'numeroinicial.integer' => 'El número inicial debe ser un número entero.',
            'numeroinicial.min' => 'El número inicial debe ser al menos 1.',
            'numerofinal.required' => 'El número final es obligatorio.',
            'numerofinal.integer' => 'El número final debe ser un número entero.',
            'numerofinal.min' => 'El número final debe ser al menos 1.',
            'numerofinal.gte' => 'El número final no puede ser menor que el número inicial.',
            'fecharecibido.required' => 'La fecha recibido es obligatoria.',
            'fecharecibido.date' => 'La fecha recibido no tiene un formato válido.',
            'fechalimite.required' => 'La fecha límite es obligatoria.',
            'fechalimite.date' => 'La fecha límite no tiene un formato válido.',
            'fechalimite.after_or_equal' => 'La fecha límite debe ser igual o posterior a la fecha recibido.',
        ]);

        $correlativoController = new CorrelativoController();
        $nextCodigo = $correlativoController->generateClientCode('CAIS', 'correlativos', 'CAI');

        $inicio = intval($request->numeroinicial) - 1;
        correlativo::where('codigo', 'CAIS')->update(['last' => $inicio]);
        correlativo::where('codigo', 'CAII')->update(['last' => $inicio]);

        $cai = new Cai();
        $cai->codigo = $nextCodigo->getData()->codigo;
        $cai->cai = $request->cai;
        $cai->prefijo = $request->prefijo;
        $cai->numeroinicial = $request->numeroinicial;
        $cai->numerofinal = $request->numerofinal;
        $cai->facturainicial = $request->prefijo . $request->numeroinicial;
        $cai->facturafinal = $request->prefijo . $request->numerofinal;
        $cai->fecharecibido = $request->fecharecibido;
        $cai->fechalimite = $request->fechalimite;
        $cai->estado = "ACTIVO";
        $cai->posicion = $request->numeroinicial;
        $cai->save();

        return redirect()->route('cais.index')->with('success', 'CAI registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(cai $cai)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($cai_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }

        $cai = cai::findOrFail($cai_id);
        return view('cais.edit', compact('cai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $cai_id)
    {
        if ($this->rol->actualizar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }

        $request->validate([
            'cai' => 'required|string|max:50|unique:cais,cai,' . $cai_id,
            'prefijo' => 'required|string|max:50',
            'numeroinicial' => 'required|string|min:1',
            'numerofinal' => 'required|string|min:1|gte:numeroinicial',
            'fecharecibido' => 'required|date',
            'fechalimite' => 'required|date|after_or_equal:fecharecibido',
            'estado' => 'required',
        ], [
            'estado.required' => 'El estado es obligatorio.',
            'cai.required' => 'El campo CAI es obligatorio.',
            'cai.max' => 'El CAI no puede exceder los 50 caracteres.',
            'cai.unique' => 'Este CAI ya está registrado.',
            'prefijo.required' => 'El campo prefijo es obligatorio.',
            'prefijo.max' => 'El prefijo no puede exceder los 50 caracteres.',
            'numeroinicial.required' => 'El número inicial es obligatorio.',
            'numeroinicial.integer' => 'El número inicial debe ser un número entero.',
            'numeroinicial.min' => 'El número inicial debe ser al menos 1.',
            'numerofinal.required' => 'El número final es obligatorio.',
            'numerofinal.integer' => 'El número final debe ser un número entero.',
            'numerofinal.min' => 'El número final debe ser al menos 1.',
            'numerofinal.gte' => 'El número final no puede ser menor que el número inicial.',
            'fecharecibido.required' => 'La fecha recibido es obligatoria.',
            'fecharecibido.date' => 'La fecha recibido no tiene un formato válido.',
            'fechalimite.required' => 'La fecha límite es obligatoria.',
            'fechalimite.date' => 'La fecha límite no tiene un formato válido.',
            'fechalimite.after_or_equal' => 'La fecha límite debe ser igual o posterior a la fecha recibido.',
        ]);

        if ($request->estado == "ACTIVO") {
            $cais = cai::where('del', 'N')
                ->where('id', '!=', $cai_id)
                ->get();

            foreach ($cais as $cai) {
                if ($cai->estado == "ACTIVO") {
                    return redirect()->route('cais.index')->with('error', 'Ya existe un CAI activo. Por favor, márquelo como CANCELADO o VENCIDO antes de activar uno de nuevo.');
                }
            }
        }

        $cai = cai::findOrFail($cai_id);
        $cai->estado = $request->estado;
        $cai->save();

        return redirect()->route('cais.index')->with('success', 'CAI actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($cai_id)
    {
        if ($this->rol->eliminar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la información solicitada.');
        }
        $cai = cai::findOrFail($cai_id);
        $cai->del = "S";
        $papelera = new PapeleraController();
        $papelera->agregarAPapelera($cai->cai, "cais", $cai_id, "id");
        $cai->save();
        return redirect()->route('cais.index')->with('success', 'CAI eliminado exitosamente.');
    }

    public function getInfoCai(){
        $cai = cai::where('estado', 'ACTIVO')->first();
        $correlativo = correlativo::where('codigo', 'CAIS')->first();
        $restantes = intval($cai->numerofinal)-intval($correlativo->last);

        $restantesInt = intval($cai->numerofinal) - intval(correlativo::where('codigo', 'CAII')->first()->last);
        if($restantesInt == 0){
            $inicio = intval($cai->numeroinicial) - 1;
            correlativo::where('codigo', 'CAII')->update(['last' => $inicio]);
        }
       // dd($restantes);
        if ($cai) {
            return response()->json(['cai'=>$cai,
        'restantes'=>$restantes]);
        } else {
            return response()->json(['message' => 'No se encontró un CAI activo.'], 404);
        }
    }
}
