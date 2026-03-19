<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Vendedor;
use Illuminate\Http\Request;

class ComisionController extends Controller
{
    public function index(Request $request)
    {
        $vendedores = Vendedor::orderBy('nombre')->get();

        $vendedorId = $request->query('vendedor_id');
        $mes        = (int) $request->query('mes',  now()->month);
        $anio       = (int) $request->query('anio', now()->year);

        $vendedor = $vendedorId ? Vendedor::find($vendedorId) : null;

        $ventas = collect();
        $totalVendido   = 0;
        $cantidadVentas = 0;

        if ($vendedor) {
            $ventas = Venta::with(['detalles.articulo'])
                ->where('vendedor_id', $vendedor->id)
                ->where('estado_id', 1)
                ->whereMonth('created_at', $mes)
                ->whereYear('created_at',  $anio)
                ->orderBy('created_at', 'desc')
                ->get();

            $totalVendido   = $ventas->sum('total');
            $cantidadVentas = $ventas->count();
        }

        return view('comisiones.index', compact(
            'vendedores', 'vendedor', 'vendedorId', 'ventas',
            'totalVendido', 'cantidadVentas',
            'mes', 'anio'
        ));
    }
}
