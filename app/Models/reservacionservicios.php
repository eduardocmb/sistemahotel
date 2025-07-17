<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class reservacionservicios extends Model
{
    protected $fillable = [
        'reservacion_id',
        'producto_id',
        'cantidad',
        'subtotal',
        'del',
    ];
}
