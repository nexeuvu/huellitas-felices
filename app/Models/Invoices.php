<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'fecha',
        'sub_total',
        'impuesto',
        'total_metodo_pago',
    ];

    /**
     * Relación con el modelo Customer.
     * Una factura pertenece a un cliente.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
