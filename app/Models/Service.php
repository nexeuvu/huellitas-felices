<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'duracion_min',
        'costo',
    ];

    protected $casts = [
        'duracion_min' => 'integer',
        'costo' => 'decimal:2',
    ];
}
