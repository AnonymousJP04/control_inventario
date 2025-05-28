<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockSalida extends Model
{
    //
    protected $fillable = [
        'producto_id',
        'user_id',
        'cantidad',
        'motivo',
        'fecha',
    ];

   public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Get the user who registered this stock exit.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}