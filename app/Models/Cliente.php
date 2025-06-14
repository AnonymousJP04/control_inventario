<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory; // Es buena práctica añadir HasFactory para 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'nit',
        'telefono',
        'direccion',
        'correo',
        'user_id',
    ];

    /**
     * Get the user that owns the client.
     */

     //Relaciones con los modelos User y Venta
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the sales for the client.
     */
    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }

    // Aquí podrías definir relaciones inversas si un cliente tiene muchas ventas, por ejemplo:
    // public function ventas()
    // {
    //     return $this->hasMany(Venta::class);
    // }
}