<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}