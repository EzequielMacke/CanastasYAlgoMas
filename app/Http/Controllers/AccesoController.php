<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\Deposito;
use App\Models\Estado;
use App\Models\Insumo;
use App\Models\Sucursal;
use App\Models\UnidadMedida;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccesoController extends Controller
{
	// Muestra el formulario de login
	public function loginForm()
	{
		// Verificar si existen estados, si no, crearlos
		if (Estado::count() === 0) {
			Estado::insert([
				['descripcion' => 'Activo', 'created_at' => now(), 'updated_at' => now()],
				['descripcion' => 'Inactivo', 'created_at' => now(), 'updated_at' => now()],
				['descripcion' => 'Pendiente', 'created_at' => now(), 'updated_at' => now()],
			]);
		}
		// Verificar si existen cargos, si no, crear 'Administrador'
		if (Cargo::count() === 0) {
			// Buscar el id del estado 'Activo'
			$estadoActivo = Estado::where('descripcion', 'Activo')->first();
			$estadoId = $estadoActivo ? $estadoActivo->id : null;
			Cargo::create([
				'descripcion' => 'Administrador',
				'estado_id' => $estadoId,
			]);
		}
		// Verificar si existen sucursales, si no, crear 'Central'
			if (Sucursal::count() === 0) {
				Sucursal::create([
					'descripcion' => 'Central',
				]);
			}
		
		// Verificar si existen depósitos, si no, crear uno llamado 'central'
			if (Deposito::count() === 0) {
				$sucursal = Sucursal::where('descripcion', 'Central')->first();
				$estado = Estado::where('descripcion', 'Activo')->first();
				$sucursalId = $sucursal ? $sucursal->id : null;
				$estadoId = $estado ? $estado->id : null;
				Deposito::create([
					'descripcion' => 'central',
					'sucursal_id' => $sucursalId,
					'estado_id' => $estadoId,
				]);
			}

		// Verificar si existen usuarios, si no, crear 'admin'
		if (Usuario::count() === 0) {
			$sucursal = Sucursal::where('descripcion', 'Central')->first();
			$estado = Estado::where('descripcion', 'Activo')->first();
			$cargo = Cargo::where('descripcion', 'Administrador')->first();
			Usuario::create([
				'nombre' => 'Ezequiel Federico',
				'apellido' => 'Macke Ruffinelli',
				'fecha_nacimiento' => '2000-09-15',
				'cedula' => '5412843',
				'nick' => 'admin',
				'password' => Hash::make('admin'),
				'correo' => 'Ezequiel.macke@gmail.com',
				'verificado' => 1,
				'temporal' => 1,
				'id_sucursal' => $sucursal ? $sucursal->id : null,
				'id_estado' => $estado ? $estado->id : null,
				'id_cargo' => $cargo ? $cargo->id : null,
			]);
		}

		// Verificar si existen insumos, si no, crear 25 insumos de ejemplo
		if (Insumo::count() === 0) {
			$estado = Estado::where('descripcion', 'Activo')->first();
			$estadoId = $estado ? $estado->id : null;
			$unidad = UnidadMedida::where('abreviacion', 'unid')->first();
			$unidadId = $unidad ? $unidad->id : null;
			$insumos = [
				'Arroz', 'Azúcar', 'Sal', 'Harina', 'Aceite', 'Leche', 'Huevos', 'Fideos', 'Polenta', 'Yerba',
				'Café', 'Té', 'Pan', 'Queso', 'Jamón', 'Manteca', 'Galletitas', 'Cereal', 'Lentejas', 'Porotos',
				'Garbanzos', 'Choclo', 'Tomate', 'Papa', 'Cebolla'
			];
			foreach ($insumos as $nombre) {
				Insumo::create([
					'descripcion' => $nombre,
					'estado_id' => $estadoId,
					'unidad_medida_id' => $unidadId,
					'usuario_id' => 1, // Asignar al usuario admin
					'fecha_carga' => now(),
				]);
			}
		}

		
		

		//Unidad de Medida
		if (UnidadMedida::count() === 0) {
			$estado = Estado::where('descripcion', 'Activo')->first();
			$estadoId = $estado ? $estado->id : null;
			UnidadMedida::insert([
				[
					'descripcion' => 'gramos',
					'abreviacion' => 'g',
					'estado_id' => $estadoId,
					'operacion_correccion' => 'multiplicacion',
					'correccion' => 1000,
					'created_at' => now(),
					'updated_at' => now(),
				],
				[
					'descripcion' => 'kilogramos',
					'abreviacion' => 'kg',
					'estado_id' => $estadoId,
					'operacion_correccion' => 'multiplicacion',
					'correccion' => 1,
					'created_at' => now(),
					'updated_at' => now(),
				],
				[
					'descripcion' => 'milímetros',
					'abreviacion' => 'mm',
					'estado_id' => $estadoId,
					'operacion_correccion' => 'division',
					'correccion' => 1000,
					'created_at' => now(),
					'updated_at' => now(),
				],
				[
					'descripcion' => 'centímetros',
					'abreviacion' => 'cm',
					'estado_id' => $estadoId,
					'operacion_correccion' => 'division',
					'correccion' => 100,
					'created_at' => now(),
					'updated_at' => now(),
				],
				[
					'descripcion' => 'metros',
					'abreviacion' => 'm',
					'estado_id' => $estadoId,
					'operacion_correccion' => 'multiplicacion',
					'correccion' => 1,
					'created_at' => now(),
					'updated_at' => now(),
				],
				[
					'descripcion' => 'litros',
					'abreviacion' => 'l',
					'estado_id' => $estadoId,
					'operacion_correccion' => 'multiplicacion',
					'correccion' => 1,
					'created_at' => now(),
					'updated_at' => now(),
				],
				[
					'descripcion' => 'unidad',
					'abreviacion' => 'unid',
					'estado_id' => $estadoId,
					'operacion_correccion' => null,
					'correccion' => null,
					'created_at' => now(),
					'updated_at' => now(),
				],
			]);
		}

			
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
