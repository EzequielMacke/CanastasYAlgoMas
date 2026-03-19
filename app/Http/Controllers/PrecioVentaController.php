<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\PrecioVenta;
use Illuminate\Http\Request;

class PrecioVentaController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->query('buscar');

        $query = Articulo::with(['tipoArticulo', 'unidadMedida', 'precioCosto', 'latestPrecioVenta'])
            ->where('estado_id', 1);

        if ($buscar) {
            $query->where('nombre', 'like', "%{$buscar}%");
        }

        $articulos = $query->orderBy('nombre')->get();

        return view('precios-venta.index', compact('articulos', 'buscar'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'articulo_id' => 'required|exists:articulos,id',
            'precio'      => 'required|numeric|min:0',
        ]);

        PrecioVenta::create([
            'articulo_id' => $request->articulo_id,
            'precio'      => $request->precio,
        ]);

        return redirect()->route('precios-venta.index', array_filter(['buscar' => $request->query('from_buscar')]))
            ->with('exito', 'Precio de venta actualizado correctamente.');
    }
}
