<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $fillable = [
        'fecha',
        'articulo_id',
        'cantidad',
        'precio',
        'precio_costo',
        'observacion',
        'estado_id',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }
}
