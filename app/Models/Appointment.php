<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'veterinary_id',
        'service_id',
        'fecha_hora',
        'estado',
        'notas',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    /**
     * Relación con la mascota
     */
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    /**
     * Relación con el veterinario
     */
    public function veterinary()
    {
        return $this->belongsTo(Veterinary::class);
    }

    /**
     * Relación con el servicio
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope para citas futuras
     */
    public function scopeFuturas($query)
    {
        return $query->where('fecha_hora', '>=', now());
    }

    /**
     * Scope para citas pasadas
     */
    public function scopePasadas($query)
    {
        return $query->where('fecha_hora', '<', now());
    }
}
