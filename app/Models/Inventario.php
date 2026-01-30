<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $fillable = [
        'insumo_id',
        'cantidad',
        'ingreso_id',
        'unidad_medida_id'
    ];

    // Relación con insumo
    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }

    // Relación con ingreso
    public function ingreso()
    {
        return $this->belongsTo(Ingreso::class);
    }

    // Relación con unidad de medida
    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class);
    }
}
