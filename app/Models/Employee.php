<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
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
        'fecha_contratacion',
        'puesto'
    ];

    protected $casts = [
        'fecha_contratacion' => 'date',
    ];

    /**
     * Obtener el nombre completo del empleado
     */
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombres} {$this->apellidos}";
    }

    /**
     * Scope para filtrar empleados activos (si se implementa status en el futuro)
     */
    public function scopeActive($query)
    {
        return $query; // Puedes implementar lógica si añades campo status
    }
}