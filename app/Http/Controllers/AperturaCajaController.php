<?php

namespace App\Http\Controllers;

use App\Models\AperturaCaja;
use App\Models\Venta;
use Illuminate\Http\Request;

class AperturaCajaController extends Controller
{
    public function index()
    {
        $activa    = AperturaCaja::whereNull('cerrado_at')->latest('id')->first();
        $aperturas = AperturaCaja::orderBy('id', 'desc')->get();

        return view('apertura-caja.index', compact('activa', 'aperturas'));
    }

    public function abrir(Request $request)
    {
        if (AperturaCaja::whereNull('cerrado_at')->exists()) {
            return back()->with('error', 'Ya existe una apertura de caja activa. Ciérrela antes de abrir una nueva.');
        }

        $request->validate([
            'observaciones' => 'nullable|string|max:500',
        ]);

        AperturaCaja::create([
            'fecha'         => now()->toDateString(),
            'abierto_at'    => now(),
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('apertura-caja.index')
            ->with('exito', 'Caja abierta correctamente.');
    }

    public function cerrar(Request $request, AperturaCaja $aperturaCaja)
    {
        if (!$aperturaCaja->estaAbierta()) {
            return back()->with('error', 'Esta apertura ya fue cerrada.');
        }

        $request->validate([
            'observaciones' => 'nullable|string|max:500',
        ]);

        $aperturaCaja->update([
            'cerrado_at'    => now(),
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('apertura-caja.index')
            ->with('exito', 'Caja cerrada correctamente.');
    }

    public function show(AperturaCaja $aperturaCaja)
    {
        $ventas = Venta::with(['vendedor', 'detalles.articulo.precioCosto'])
            ->where('apertura_caja_id', $aperturaCaja->id)
            ->orderBy('id', 'desc')
            ->get();

        $totalVentas = $ventas->where('estado_id', 1)->sum('total');

        $totalGasto = $ventas->where('estado_id', 1)
            ->flatMap(fn($v) => $v->detalles)
            ->sum(fn($d) => $d->cantidad * ($d->articulo->precioCosto->costo ?? 0));

        $totalGanancia = $totalVentas - $totalGasto;

        return view('apertura-caja.show', compact('aperturaCaja', 'ventas', 'totalVentas', 'totalGasto', 'totalGanancia'));
    }
}
