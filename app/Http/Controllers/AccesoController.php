<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccesoController extends Controller
{
	// Muestra el formulario de login
	public function loginForm()
	{
		return view('login.index');
	}

	// Procesa el login
	public function login(Request $request)
	{
		$nick = $request->input('nick');
		$password = $request->input('password');

		// Buscar usuario por nick

		$usuario = Usuario::where('nick', $nick)->first();
		if ($usuario && Hash::check($password, $usuario->password)) {
			// Autenticación exitosa usando hash
			// Guardar todos los datos del usuario en la sesión
			session(['usuario' => $usuario->toArray()]);
			return redirect()->route('menu.index');
		} else {
			// Autenticación fallida
			return back()->withErrors(['nick' => 'Credenciales incorrectas'])->withInput();
		}
	}

	// Cierra la sesión
	public function logout()
	{
		session()->flush();
		return redirect()->route('login.form');
	}
}
