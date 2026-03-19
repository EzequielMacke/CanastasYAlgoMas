<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TipoArticulo;
use App\Models\UnidadMedida;
use App\Models\Estado;

class Articulo extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'foto', 'tipo_articulo_id', 'unidad_medida_id', 'estado_id'];

    public function tipoArticulo()
    {
        return $this->belongsTo(TipoArticulo::class);
    }

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class);
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    public function receta()
    {
        return $this->hasOne(\App\Models\Receta::class);
    }

    public function precioCosto()
    {
        return $this->hasOne(\App\Models\PrecioCosto::class);
    }

    public function latestPrecioVenta()
    {
        return $this->hasOne(\App\Models\PrecioVenta::class)->latestOfMany();
    }

    public function stock()
    {
        return $this->hasOne(\App\Models\Stock::class);
    }

    public function esProduccion(): bool
    {
        return str_contains(strtolower($this->tipoArticulo->nombre ?? ''), 'produc');
    }

    public function esServicio(): bool
    {
        return str_contains(strtolower($this->tipoArticulo->nombre ?? ''), 'servic');
    }
}
