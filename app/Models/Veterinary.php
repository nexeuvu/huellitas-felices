<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veterinary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'especialidad',
        'licencia',
    ];

    /**
     * Relación con el modelo Employee.
     * Un veterinario pertenece a un empleado.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
