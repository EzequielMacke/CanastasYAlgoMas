<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    protected $fillable = ['articulo_id', 'estado_id'];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    public function items()
    {
        return $this->hasMany(RecetaItem::class);
    }
}
