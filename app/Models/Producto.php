<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    #Relación de uno a muchos con StockEntrada
    public function stockEntradas()
    {
        return $this->hasMany(StockEntrada::class);
    }

    #Relación de uno a muchos con StockSalida
    public function stockSalidas()
    {
        return $this->hasMany(StockSalida::class);
    }

    #Stock actual del producto
    public function stockActual()
    {
        $entradas = $this->stockEntradas()->sum('cantidad');
        $salidas = $this->stockSalidas()->sum('cantidad');
        return $entradas - $salidas;
    }
}
