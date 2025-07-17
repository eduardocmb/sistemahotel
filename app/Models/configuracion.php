<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class configuracion extends Model
{
    protected $fillable = [
        'codigo',
        'detalle',
        'valor',
    ];
}
