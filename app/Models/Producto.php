<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Producto extends Model
{
//fillable sirve para definir los campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock_minimo',
        'user_id',
    ];

    #Relación de uno a muchos inversa con User
    /**
     * Get the user who registered this product.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the stock entries for the product.
     */
    public function stockEntradas(): HasMany
    {
        return $this->hasMany(StockEntrada::class);
    }

    /**
     * Get all of the stock exits for the product.
     */
    public function stockSalidas(): HasMany
    {
        return $this->hasMany(StockSalida::class);
    }

    /**
     * Get all of the sale details for the product.
     */
    public function detalleVentas(): HasMany
    {
        return $this->hasMany(DetalleVenta::class);
    }


    #Stock actual del producto
    public function stockActual()
    {
        $entradas = $this->stockEntradas()->sum('cantidad');
        $salidas = $this->stockSalidas()->sum('cantidad');
        return $entradas - $salidas;
    }
}
