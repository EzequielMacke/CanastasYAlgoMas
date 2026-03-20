<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AperturaCaja extends Model
{
    protected $table = 'aperturas_caja';

    protected $fillable = [
        'fecha',
        'abierto_at',
        'cerrado_at',
        'observaciones',
    ];

    protected $casts = [
        'fecha'      => 'date',
        'abierto_at' => 'datetime',
        'cerrado_at' => 'datetime',
    ];

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function estaAbierta(): bool
    {
        return is_null($this->cerrado_at);
    }
}
