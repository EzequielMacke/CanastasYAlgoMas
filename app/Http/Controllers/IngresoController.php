<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use App\Models\Insumo;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class IngresoController extends Controller
{
	public function index()
	{
		$ingresos = Ingreso::with(['proveedor', 'usuario', 'estado'])->get();
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
		// Aquí irá la lógica para guardar el ingreso y sus detalles
	}
}
