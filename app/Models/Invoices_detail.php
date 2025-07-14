<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices_detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoices_id',
        'service_id',
        'product_id',
        'cantidad',
        'precio_unitario',
        'sub_total',
        'total',
    ];

    /**
     * Relación con la factura (Invoices).
     * Un detalle de factura pertenece a una factura.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoices::class, 'invoices_id');
    }

    /**
     * Relación con el servicio (Service).
     * Un detalle puede estar asociado a un servicio.
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /**
     * Relación con el producto (Product).
     * Un detalle puede estar asociado a un producto.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
