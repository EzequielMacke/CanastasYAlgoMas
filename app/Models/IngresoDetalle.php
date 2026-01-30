<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngresoDetalle extends Model
{
    protected $fillable = [
        'ingreso_id',
        'insumo_id',
        'cantidad',
        'precio',
        'unidad_medida_id'
    ];

    // Relación con ingreso
    public function ingreso()
    {
        return $this->belongsTo(Ingreso::class);
    }

    // Relación con insumo
    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }

    // Relación con unidad de medida
    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class);
    }
}
