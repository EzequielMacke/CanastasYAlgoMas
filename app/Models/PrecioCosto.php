<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrecioCosto extends Model
{
    protected $table = 'precios_costo';

    protected $fillable = ['articulo_id', 'costo'];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }
}
