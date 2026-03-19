<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoArticulo extends Model
{
    protected $table = 'tipos_articulo';

    protected $fillable = ['nombre'];
}
