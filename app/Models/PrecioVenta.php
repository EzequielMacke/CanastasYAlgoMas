<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrecioVenta extends Model
{
    protected $table = 'precios_venta';

    protected $fillable = ['articulo_id', 'precio'];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }
}
