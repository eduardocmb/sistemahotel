<?php

namespace App\Http\Controllers;

use App\Models\infohotel;
use App\Models\role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InfohotelController extends Controller
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
        if ($this->rol->guardar == "N") {
            return redirect()->route('dashboard')->with('nopermiso', 'No tienes permiso para acceder a la acción solicitada.');
        }
        $infoHotel = infohotel::first();

        return view('sistema.infohotel.index', compact('infoHotel'));
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
        $request->validate([
            'rtn' => 'required|string|max:18',
            'nombre' => 'required|string|max:150',
            'eslogan' => 'nullable|string|max:150',
            'direccion' => 'required|string|max:150',
            'correo' => 'required|email|max:150',
            'propietario' => 'required|string|max:80',
            'telefono' => 'required|string|max:10',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'rtn.required' => 'El campo RTN es obligatorio.',
            'nombre.required' => 'El nombre es obligatorio.',
            'direccion.required' => 'La dirección es obligatoria.',
            'correo.required' => 'El correo es obligatorio.',
            'correo.email' => 'El formato del correo no es válido.',
            'propietario.required' => 'El propietario es obligatorio.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'logo.image' => 'El logo principal debe ser una imagen.',
            'logo2.image' => 'El logo secundario debe ser una imagen.',
        ]);

        $infoHotel = InfoHotel::first();
        if (!$infoHotel) {
            $infoHotel = new InfoHotel();
        }

        if ($request->hasFile('logo')) {
            if ($infoHotel->logo && Storage::disk('public')->exists($infoHotel->logo)) {
                Storage::disk('public')->delete($infoHotel->logo);
            }

            $infoHotel->logo = $request->file('logo')->storeAs('imgs', 'logo.' . $request->file('logo')->extension(), 'public');
        }

        if ($request->hasFile('logo2')) {
            if ($infoHotel->logo2 && Storage::disk('public')->exists($infoHotel->logo2)) {
                $deleted = Storage::disk('public')->delete($infoHotel->logo2);

                if ($deleted) {
                   // dump( "Archivo eliminado correctamente.");
                } else {
                    //dump( "El archivo no pudo ser eliminado o no existe.");
                }
            }

            $infoHotel->logo2 = $request->file('logo2')->storeAs('imgs', 'logo2.' . $request->file('logo2')->extension(), 'public');
        } elseif (!$infoHotel->logo2) {
            $infoHotel->logo2 = 'imgs/logo2.png';
        }

        $infoHotel->rtn = $request->rtn;
        $infoHotel->nombre = $request->nombre;
        $infoHotel->eslogan = $request->eslogan;
        $infoHotel->direccion = $request->direccion;
        $infoHotel->correo = $request->correo;
        $infoHotel->propietario = $request->propietario;
        $infoHotel->telefono = $request->telefono;
        $infoHotel->save();
        return redirect()->route('infohotel.index')->with('success', 'Información del negocio guardada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(infohotel $infohotel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(infohotel $infohotel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, infohotel $infohotel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(infohotel $infohotel)
    {
        //
    }

    public function getInfoHotel()
    {
        $info = InfoHotel::where('del', 'N')->first();
        if ($info) {
            return response()->json($info);
        } else {
            return response()->json(['status' => 'ERROR', 'message' => 'No se encontró información del Hotel'], 404);
        }
    }
}
