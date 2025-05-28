<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockEntrada extends Model
{
    //
    protected $fillable = [
        'producto_id',
        'user_id',
        'cantidad',
        'motivo',
        'fecha',
    ];

    /**
     * Get the product that this stock entry belongs to.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Get the user who registered this stock entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}