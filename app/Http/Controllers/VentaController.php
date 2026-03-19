<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Stock;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Vendedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'activas');

        $activas  = Venta::with(['vendedor', 'detalles'])->where('estado_id', 1)->orderBy('id', 'desc')->get();
        $anuladas = Venta::with(['vendedor', 'detalles'])->where('estado_id', 2)->orderBy('id', 'desc')->get();

        return view('ventas.index', compact('activas', 'anuladas', 'tab'));
    }

    public function create()
    {
        $vendedores = Vendedor::where('estado_id', 1)->orderBy('nombre')->get();
        $articulos  = Articulo::with(['tipoArticulo', 'unidadMedida', 'latestPrecioVenta', 'precioCosto', 'stock'])
                        ->where('estado_id', 1)->orderBy('nombre')->get();

        return view('ventas.create', compact('vendedores', 'articulos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendedor_id'             => 'required|exists:vendedores,id',
            'cliente_nombre'          => 'nullable|string|max:150',
            'items'                   => 'required|array|min:1',
            'items.*.articulo_id'     => 'required|exists:articulos,id',
            'items.*.cantidad'        => 'required|numeric|min:0.0001',
            'items.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        $erroresStock = $this->verificarStockVenta($request->items);
        if (!empty($erroresStock)) {
            return back()->withInput()->with('errores_stock', $erroresStock);
        }

        $total  = array_sum(array_map(fn($i) => $i['cantidad'] * $i['precio_unitario'], $request->items));
        $numero = (Venta::max('numero') ?? 0) + 1;

        $ventaCreada = null;

        DB::transaction(function () use ($request, $total, $numero, &$ventaCreada) {
            $ventaCreada = Venta::create([
                'numero'         => $numero,
                'vendedor_id'    => $request->vendedor_id,
                'cliente_nombre' => $request->cliente_nombre,
                'total'          => $total,
                'estado_id'      => 1,
            ]);

            foreach ($request->items as $item) {
                VentaDetalle::create([
                    'venta_id'        => $ventaCreada->id,
                    'articulo_id'     => $item['articulo_id'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal'        => $item['cantidad'] * $item['precio_unitario'],
                ]);

                $articulo = Articulo::with('tipoArticulo')->find($item['articulo_id']);
                if ($articulo && !$articulo->esServicio()) {
                    Stock::restar($articulo->id, (float) $item['cantidad']);
                }
            }
        });

        return redirect()->route('ventas.show', $ventaCreada);
    }

    public function show(Venta $venta)
    {
        $venta->load(['vendedor', 'detalles.articulo.tipoArticulo', 'detalles.articulo.unidadMedida']);
        return view('ventas.show', compact('venta'));
    }

    public function edit(Venta $venta)
    {
        $venta->load(['detalles.articulo.tipoArticulo', 'detalles.articulo.unidadMedida']);
        $vendedores = Vendedor::where('estado_id', 1)->orderBy('nombre')->get();
        $articulos  = Articulo::with(['tipoArticulo', 'unidadMedida', 'latestPrecioVenta', 'precioCosto', 'stock'])
                        ->where('estado_id', 1)->orderBy('nombre')->get();

        return view('ventas.edit', compact('venta', 'vendedores', 'articulos'));
    }

    public function update(Request $request, Venta $venta)
    {
        $request->validate([
            'vendedor_id'             => 'required|exists:vendedores,id',
            'cliente_nombre'          => 'nullable|string|max:150',
            'items'                   => 'required|array|min:1',
            'items.*.articulo_id'     => 'required|exists:articulos,id',
            'items.*.cantidad'        => 'required|numeric|min:0.0001',
            'items.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        $erroresStock = [];

        try {
            DB::transaction(function () use ($request, $venta, &$erroresStock) {
                // 1. Revertir stock de ítems anteriores
                if ($venta->estado_id === 1) {
                    foreach ($venta->detalles as $detalle) {
                        $art = Articulo::with('tipoArticulo')->find($detalle->articulo_id);
                        if ($art && !$art->esServicio()) {
                            Stock::sumar($art->id, (float) $detalle->cantidad);
                        }
                    }
                }

                // 2. Verificar stock para nuevos ítems
                $erroresStock = $this->verificarStockVenta($request->items);
                if (!empty($erroresStock)) {
                    throw new \RuntimeException('stock_insuficiente');
                }

                // 3. Actualizar venta
                $total = array_sum(array_map(fn($i) => $i['cantidad'] * $i['precio_unitario'], $request->items));

                $venta->update([
                    'vendedor_id'    => $request->vendedor_id,
                    'cliente_nombre' => $request->cliente_nombre,
                    'total'          => $total,
                ]);

                // 4. Reemplazar detalles
                $venta->detalles()->delete();

                foreach ($request->items as $item) {
                    VentaDetalle::create([
                        'venta_id'        => $venta->id,
                        'articulo_id'     => $item['articulo_id'],
                        'cantidad'        => $item['cantidad'],
                        'precio_unitario' => $item['precio_unitario'],
                        'subtotal'        => $item['cantidad'] * $item['precio_unitario'],
                    ]);

                    if ($venta->estado_id === 1) {
                        $art = Articulo::with('tipoArticulo')->find($item['articulo_id']);
                        if ($art && !$art->esServicio()) {
                            Stock::restar($art->id, (float) $item['cantidad']);
                        }
                    }
                }
            });
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'stock_insuficiente') {
                return back()->withInput()->with('errores_stock', $erroresStock);
            }
            throw $e;
        }

        return redirect()->route('ventas.show', $venta);
    }

    public function destroy(Venta $venta)
    {
        foreach ($venta->detalles as $detalle) {
            $art = Articulo::with('tipoArticulo')->find($detalle->articulo_id);
            if ($art && !$art->esServicio()) {
                Stock::sumar($art->id, (float) $detalle->cantidad);
            }
        }

        $venta->update(['estado_id' => 2]);

        return redirect()->route('ventas.index', ['tab' => 'anuladas'])->with('exito', "Venta #{$venta->numero} anulada.");
    }

    public function reactivar(Venta $venta)
    {
        $items = $venta->detalles->map(fn($d) => [
            'articulo_id' => $d->articulo_id,
            'cantidad'    => $d->cantidad,
        ])->toArray();

        $errores = $this->verificarStockVenta($items);
        if (!empty($errores)) {
            return redirect()->route('ventas.index', ['tab' => 'anuladas'])
                ->with('errores_stock', $errores);
        }

        foreach ($venta->detalles as $detalle) {
            $art = Articulo::with('tipoArticulo')->find($detalle->articulo_id);
            if ($art && !$art->esServicio()) {
                Stock::restar($art->id, (float) $detalle->cantidad);
            }
        }

        $venta->update(['estado_id' => 1]);

        return redirect()->route('ventas.index', ['tab' => 'activas'])->with('exito', "Venta #{$venta->numero} reactivada.");
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function verificarStockVenta(array $items): array
    {
        $errores = [];
        foreach ($items as $item) {
            $art = Articulo::with(['tipoArticulo', 'unidadMedida'])->find($item['articulo_id']);
            if (!$art || $art->esServicio()) continue;

            $disponible = (float) (Stock::where('articulo_id', $art->id)->value('cantidad') ?? 0);
            $necesario  = (float) $item['cantidad'];
            if ($disponible < $necesario) {
                $errores[] = [
                    'nombre'     => $art->nombre,
                    'necesario'  => $necesario,
                    'disponible' => $disponible,
                    'falta'      => round($necesario - $disponible, 4),
                    'unidad'     => $art->unidadMedida->abreviatura,
                ];
            }
        }
        return $errores;
    }
}
