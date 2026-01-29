<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'sucursales';

    protected $fillable = [
        'descripcion',
    ];

    public function depositos()
    {
        return $this->hasMany(Deposito::class, 'sucursal_id');
    }

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_sucursal');
    }
}