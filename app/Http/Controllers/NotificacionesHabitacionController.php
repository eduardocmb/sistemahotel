<?php

namespace App\Http\Controllers;

use App\Models\NotificacionesHabitacion;
use App\Models\reservacion;
use App\Models\role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificacionesHabitacionController extends Controller
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
        return view('notificaciones.index');
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
    public function store(Request $request) {}


    /**
     * Display the specified resource.
     */
    public function show(NotificacionesHabitacion $notificacionesHabitacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NotificacionesHabitacion $notificacionesHabitacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NotificacionesHabitacion $notificacionesHabitacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NotificacionesHabitacion $notificacionesHabitacion)
    {
        //
    }

    public function CrearNotificaciones()
    {
        $hoy = Carbon::today();

        $fechaLimite = Carbon::today()->subDays(30);
        NotificacionesHabitacion::where('created_at', '<', $fechaLimite)->delete();

        $notificacionesHoy = NotificacionesHabitacion::where('title', 'like', 'Habitacion%lista')
            ->count();

        if ($notificacionesHoy > 0) {
            return response()->json(['message' => 'Ya se crearon las notificaciones hoy'], 200);
        }
        $reservacionesHoy = Reservacion::whereDate('salida', $hoy)
            ->whereIn('estado', ['FINALIZADA', 'PENDIENTE', 'CONFIRMADA'])
            ->get();

        foreach ($reservacionesHoy as $reservacion) {
            $habitacion = $reservacion->habitacion;

            $notificacionExistente = NotificacionesHabitacion::whereDate('created_at', $hoy)
                ->where('title', 'like', "Habitación {$habitacion->numero_habitacion}%")
                ->exists();

            if ($notificacionExistente) {
                continue;
            }

            NotificacionesHabitacion::create([
                'title' => "Habitación {$habitacion->numero_habitacion} Lista",
                'description' => "La habitación {$habitacion->numero_habitacion} estará lista hoy para su mantenimiento."
            ]);
        }

        return response()->json(['message' => 'Notificaciones creadas exitosamente'], 200);
    }

    public function getNotificaciones()
    {
        $notificaciones = DB::table('notificaciones_habitacions')
            ->where('leido', '=', 'N')
            ->get();

        $totalNotificaciones = $notificaciones->count();

        return response()->json([
            'total_notificaciones' => $totalNotificaciones,
            'notificaciones' => $notificaciones
        ]);
    }

    public function markAsRead()
    {
        $updated = DB::table('notificaciones_habitacions')
            ->where('leido', '=', 'N')
            ->update(['leido' => 'S']);

        if ($updated) {
            return response()->json([
                'status' => 'success',
                'message' => 'Notificaciones marcadas como leídas.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No se encontraron notificaciones para marcar como leídas.'
            ]);
        }
    }
}
