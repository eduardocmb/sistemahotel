<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class habitacione extends Model
{
    public function reservaciones()
    {
        return $this->hasMany(Reservacion::class);
    }
}
