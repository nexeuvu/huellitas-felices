<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'veterinary_id',
        'fecha',
        'diagnostico',
        'tratamiento',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
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
}
