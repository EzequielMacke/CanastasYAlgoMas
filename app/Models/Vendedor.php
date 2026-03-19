<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    protected $table = 'vendedores';

    protected $fillable = ['nombre', 'apellido', 'estado_id'];

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }
}
