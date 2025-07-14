<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria',
        'stock',
        'precio',
    ];

    protected $casts = [
        'stock' => 'integer',
        'precio' => 'decimal:2',
    ];
}
