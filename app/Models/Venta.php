<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = ['numero', 'vendedor_id', 'apertura_caja_id', 'cliente_nombre', 'total', 'estado_id'];

    public function aperturaCaja()
    {
        return $this->belongsTo(AperturaCaja::class);
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class);
    }
}
