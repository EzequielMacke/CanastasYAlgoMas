<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;

    protected $table = 'permisos';

    protected $fillable = [
        'nombre',
        'abr',
        'ver',
        'agregar',
        'editar',
        'eliminar',
        'modulo_id',
    ];

    

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }
}