<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'breed_id',
        'nombre',
        'fecha_nacimiento',
        'genero',
        'color',
        'peso',
        'foto',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'peso' => 'float',
    ];

    /**
     * Relación con el cliente (propietario)
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relación con la raza
     */
    public function breed()
    {
        return $this->belongsTo(Breed::class);
    }

    /**
     * Accesor para obtener la edad de la mascota
     */
    public function getEdadAttribute()
    {
        return $this->fecha_nacimiento ? $this->fecha_nacimiento->age : null;
    }

    /**
     * Scope para filtrar mascotas por género
     */
    public function scopeGenero($query, $genero)
    {
        return $query->where('genero', $genero);
    }
}
