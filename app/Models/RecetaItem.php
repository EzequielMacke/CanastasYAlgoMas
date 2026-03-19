<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecetaItem extends Model
{
    protected $fillable = ['receta_id', 'articulo_id', 'cantidad', 'unidad_medida_id', 'estado_id'];

    public function receta()
    {
        return $this->belongsTo(Receta::class);
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class);
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }
}
