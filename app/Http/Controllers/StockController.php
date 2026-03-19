<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->query('buscar');

        $query = Stock::with(['articulo.tipoArticulo', 'articulo.unidadMedida', 'articulo.precioCosto'])
            ->whereHas('articulo', fn($q) => $q->where('estado_id', 1));

        if ($buscar) {
            $query->whereHas('articulo', fn($q) => $q->where('nombre', 'like', '%' . $buscar . '%'));
        }

        $stocks = $query->get()->sortBy(fn($s) => $s->articulo->nombre);

        return view('stock.index', compact('stocks', 'buscar'));
    }
}
