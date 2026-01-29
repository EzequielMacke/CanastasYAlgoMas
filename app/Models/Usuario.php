<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'cedula',
        'nick',
        'password',
        'correo',
        'verificado',
        'temporal',
        'id_sucursal',
        'id_estado',
        'id_cargo',
    ];





    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado');
    }
    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'id_cargo');
    }
}
