<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->query('q');

        $articulos = Articulo::with(['tipoArticulo', 'unidadMedida', 'latestPrecioVenta', 'stock'])
            ->where('estado_id', 1)
            ->where('visible_catalogo', true)
            ->when($buscar, fn($q) => $q->where('nombre', 'like', "%{$buscar}%"))
            ->orderBy('nombre')
            ->get();

        return view('catalogo.index', compact('articulos', 'buscar'));
    }

    public function show(Articulo $articulo)
    {
        if ($articulo->estado_id !== 1 || !$articulo->visible_catalogo) {
            abort(404);
        }

        $articulo->load(['tipoArticulo', 'unidadMedida', 'latestPrecioVenta', 'stock']);

        return view('catalogo.show', compact('articulo'));
    }
}
