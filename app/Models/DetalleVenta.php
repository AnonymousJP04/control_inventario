<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'descuento',
        'total',
        'user_id',
    ];

    // //protected es para que no se pueda modificar desde el formulario
    // protected $casts = [
    //     'cantidad' => 'integer',
    //     'precio_unitario' => 'decimal:2',
    //     'subtotal' => 'decimal:2',
    //     'descuento' => 'decimal:2',
    //     'total' => 'decimal:2',
    // ];

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class); // Relación de muchos a uno, una venta puede tener muchos detalles
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class); // Relación de muchos a uno, un detalle pertenece a un producto
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); // Relación de muchos a uno, un detalle es creado por un usuario
    }
}