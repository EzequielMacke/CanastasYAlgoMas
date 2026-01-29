<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\Estado;
use App\Models\Sucursal;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::all();
        $estados = Estado::all();
        return view('usuario.index', compact('usuarios', 'estados'));
    }
    public function create()
    {
        $cargos = Cargo::all();
        $sucursales = Sucursal::all();
        return view('usuario.create', compact('cargos', 'sucursales'));
    }

    public function invitar(Request $request)
    {
        $request->validate([
            'correo' => 'required|email|unique:usuarios,correo',
            'id_sucursal' => 'required|exists:sucursales,id',
            'id_cargo' => 'required|exists:cargos,id',
        ]);

        $usuario = new Usuario();
        $usuario->correo = $request->correo;
        $usuario->id_sucursal = $request->id_sucursal;
        $usuario->id_cargo = $request->id_cargo;
        $usuario->id_estado = '3'; // Pendiente
        $usuario->save();

        // Aquí podrías enviar la invitación por correo si lo deseas

        return redirect()->route('usuarios.index')->with('status', 'Usuario invitado correctamente.');
    }
}
