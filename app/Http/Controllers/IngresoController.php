<?php

namespace App\Http\Controllers;

use App\Models\Deposito;
use App\Models\Ingreso;
use App\Models\IngresoDetalle;
use App\Models\Insumo;
use App\Models\InsumoCosto;
use App\Models\Inventario;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class IngresoController extends Controller
{
	public function index()
	{
		$ingresos = Ingreso::all();
		
		return view('ingreso.index', compact('ingresos'));
	}

	public function create()
	{
		$insumos = Insumo::all();
		$unidades = UnidadMedida::all();
		return view('ingreso.create', compact('insumos', 'unidades'));
	}
	public function store(Request $request)
	{
		// Validar datos principales
		$request->validate([
			'observacion' => 'nullable|string',
			'insumos' => 'required|array|min:1',
			'insumos.*.insumo_nombre' => 'required|string',
			'insumos.*.unidad_medida_id' => 'required|integer|exists:unidad_medidas,id',
			'insumos.*.cantidad' => 'required|numeric|min:0.01',
			'insumos.*.precio' => 'required|numeric|min:0',
		]);

		// Obtener usuario y depósito desde la sesión
		$usuarioId = session('usuario.id');
		$sucursalId = session('usuario.id_sucursal');
		$deposito = Deposito::where('sucursal_id', $sucursalId)->first();
		if (!$deposito) {
			return back()->with('status', 'No se encontró un depósito para la sucursal.');
		}

		// Guardar cabecera de ingreso
		$ingreso = new Ingreso();
		$ingreso->fecha_ingreso = now();
		$ingreso->usuario_id = $usuarioId;
		$ingreso->deposito_id = $deposito->id;
		$ingreso->observacion = $request->observacion;
		$ingreso->estado_id = 1; // Estado 'registrado', ajustar si es necesario
		$ingreso->save();


		// Procesar cada insumo del detalle
		foreach ($request->insumos as $detalle) {
			// Buscar o crear insumo por nombre
			$insumo = Insumo::firstOrCreate(
				['descripcion' => $detalle['insumo_nombre']],
				[
					'estado_id' => 1,
					'usuario_id' => $usuarioId,
					'fecha_carga' => now(),
					'fotografia' => null
				]
			);

			// Guardar detalle de ingreso
			$ingresoDetalle = new IngresoDetalle();
			$ingresoDetalle->ingreso_id = $ingreso->id;
			$ingresoDetalle->insumo_id = $insumo->id;
			$ingresoDetalle->cantidad = $detalle['cantidad'];
			$ingresoDetalle->precio = $detalle['precio'];
			$ingresoDetalle->unidad_medida_id = $detalle['unidad_medida_id'];
			$ingresoDetalle->save();

			// Inventario: no sumar cantidades, solo registrar el movimiento por ingreso
			$inventario = new Inventario();
			$inventario->insumo_id = $insumo->id;
			$inventario->unidad_medida_id = $detalle['unidad_medida_id'];
			$inventario->ingreso_id = $ingreso->id;
			$inventario->cantidad = $detalle['cantidad'];
			$inventario->save();

			// Calcular costo unitario convertido a la unidad del sistema internacional
			$unidad = UnidadMedida::find($detalle['unidad_medida_id']);
			$precio_unitario = $detalle['precio'] / $detalle['cantidad'];
			$costo_unitario_si = $precio_unitario;
			if ($unidad && $unidad->operacion_correccion && $unidad->correccion) {
				if ($unidad->operacion_correccion === 'multiplicacion') {
					$costo_unitario_si = $precio_unitario * $unidad->correccion;
				} elseif ($unidad->operacion_correccion === 'division') {
					$costo_unitario_si = $precio_unitario / $unidad->correccion;
				}
			}

			// Guardar costo del insumo
			$insumoCosto = new InsumoCosto();
			$insumoCosto->insumo_id = $insumo->id;
			$insumoCosto->ingreso_id = $ingreso->id;
			$insumoCosto->costo_unitario = $costo_unitario_si;
			$insumoCosto->save();
		}

		return redirect()->route('ingreso.index')->with('status', 'Ingreso registrado correctamente.');
	}

	//crear nuevo articulo (esto se utiliza cuando el usuario no encuentra el insumo que busca)
	public function crearInsumo(Request $request)
	{
		$request->validate([
			'descripcion' => 'required|string|max:255',
		]);

		$insumo = new Insumo();
		$insumo->descripcion = $request->descripcion;
		$insumo->estado_id = 1; // Estado activo por defecto, ajustar si es necesario
		$insumo->usuario_id = session('usuario.id') ?? 1; // Usuario actual desde sesión personalizada o 1 por defecto
		$insumo->fecha_carga = now();
		$insumo->fotografia = null;
		$insumo->save();

		return response()->json([
			'success' => true,
			'insumo' => $insumo
		]);
	}
	
}
