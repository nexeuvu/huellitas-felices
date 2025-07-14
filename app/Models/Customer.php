<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'dni',
        'tipo_documento',
        'nombres',
        'apellidos',
        'direccion',
        'telefono',
        'email',
        'fecha_registro'
    ];

    protected $casts = [
        'fecha_registro' => 'date',
    ];

    /**
     * Obtener el nombre completo del cliente
     */
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombres} {$this->apellidos}";
    }
}