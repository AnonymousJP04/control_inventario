<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    use HasFactory; //HasFactory sirve para crear instancias de este modelo en pruebas y seeders

    protected $fillable = [
        'cliente_id',
        'user_id',
        'total',
    ];

//!Recordar agregar esto posteriormente como funcionalidad adicional
    // protected $casts = [
    //     'total' => 'decimal:2',
    // ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Get the user (seller) that recorded the sale.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the details for the sale.
     */
    public function detalleVentas(): HasMany
    {
        return $this->hasMany(DetalleVenta::class);
    }
}