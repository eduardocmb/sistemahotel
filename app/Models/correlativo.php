<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class correlativo extends Model
{
    protected $fillable = [
        'codigo',
        'description',
        'last',
        'created_at',
        'updated_at',
    ];
}
