<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $fillable = [
        'fecha_ingreso',
        'usuario_id',
        'deposito_id',
        'observacion',
        'estado_id'
    ];

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    // Relación con depósito
    public function deposito()
    {
        return $this->belongsTo(Deposito::class);
    }

    // Relación con detalles de ingreso
    public function detalles()
    {
        return $this->hasMany(IngresoDetalle::class);
    }
    // Relación con estado
    public function estado()
    {
        return $this->belongsTo(Estado::class);     
    }
}
