<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsumoCosto extends Model
{
    protected $table = 'insumo_costos';
    protected $fillable = [
        'insumo_id',
        'ingreso_id',
        'costo_unitario',
    ];

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }

    public function ingreso()
    {
        return $this->belongsTo(Ingreso::class, 'ingreso_id');
    }
}
