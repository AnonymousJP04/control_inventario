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

    public function cliente()
    {
        return $this->belongsTo(Cliente::class); //belongsTo indica que una venta pertenece a un cliente
    }

    public function user()
    {
        return $this->belongsTo(User::class); //belongsTo indica que una venta pertenece a un usuario
    }

    // Relación con DetalleVenta (necesitarás crear este modelo y su tabla)
    public function DetalleVenta()
    {
        return $this->hasMany(DetalleVenta::class); //hasMany indica que una venta puede tener muchos detalles de venta
    }
}