<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class reservacion extends Model
{
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function habitacion()
    {
        return $this->belongsTo(habitacione::class, 'habitacion_id');
    }
}
