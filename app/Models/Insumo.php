<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    protected $table = 'insumos';

    protected $fillable = [
        'descripcion',
        'estado_id',
        'usuario_id',
        'fecha_carga',
        'fotografia',
    ];


    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
