<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Ingreso;
use App\Models\PrecioCosto;
use App\Models\Receta;
use App\Models\Stock;
use App\Models\TipoArticulo;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IngresoController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'activos');

        $activos   = Ingreso::with(['articulo.unidadMedida'])
            ->where('estado_id', 1)->orderBy('fecha', 'desc')->orderBy('id', 'desc')->get();
        $inactivos = Ingreso::with(['articulo.unidadMedida'])
            ->where('estado_id', 2)->orderBy('fecha', 'desc')->orderBy('id', 'desc')->get();

        return view('ingresos.index', compact('activos', 'inactivos', 'tab'));
    }

    public function create()
    {
        $articulos      = Articulo::with(['tipoArticulo', 'unidadMedida'])->where('estado_id', 1)->orderBy('nombre')->get();
        $tipos          = TipoArticulo::orderBy('nombre')->get();
        $unidades       = UnidadMedida::orderBy('nombre')->get();
        $recetasPreview = $this->recetasParaPreview();

        return view('ingresos.create', compact('articulos', 'tipos', 'unidades', 'recetasPreview'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha'       => 'required|date',
            'articulo_id' => 'required|exists:articulos,id',
            'cantidad'    => 'required|numeric|min:0.0001',
            'precio'      => 'nullable|numeric|min:0',
            'observacion' => 'nullable|string|max:500',
        ]);

        $articulo = Articulo::with('tipoArticulo')->findOrFail($request->articulo_id);
        $cantidad = (float) $request->cantidad;
        $receta   = null;

        if ($articulo->esProduccion()) {
            $receta = $this->cargarReceta($articulo->id);
            if ($receta) {
                $errores = $this->verificarStock($receta, $cantidad);
                if (!empty($errores)) {
                    return back()->withInput()->with('errores_stock', $errores);
                }
            }
            $precioCosto = $receta ? $this->calcularCostoUnitarioReceta($receta) : 0;
            $precio      = round($precioCosto * $cantidad, 4);
        } else {
            if (is_null($request->precio)) {
                return back()->withInput()->withErrors(['precio' => 'El precio es obligatorio.']);
            }
            $precio      = (float) $request->precio;
            $precioCosto = $cantidad > 0 ? round($precio / $cantidad, 6) : 0;
        }

        $ingreso = Ingreso::create([
            'fecha'        => $request->fecha,
            'articulo_id'  => $request->articulo_id,
            'cantidad'     => $cantidad,
            'precio'       => $precio,
            'precio_costo' => $precioCosto,
            'observacion'  => $request->observacion,
            'estado_id'    => 1,
        ]);

        Stock::sumar($ingreso->articulo_id, $cantidad);

        if ($articulo->esProduccion()) {
            $receta = $receta ?? $this->cargarReceta($articulo->id);
            if ($receta) $this->descontarIngredientes($receta, $cantidad);
        }

        PrecioCosto::updateOrCreate(
            ['articulo_id' => $ingreso->articulo_id],
            ['costo'       => $precioCosto]
        );

        return redirect()->route('ingresos.index')->with('exito', 'Ingreso registrado correctamente.');
    }

    public function edit(Ingreso $ingreso)
    {
        $articulos      = Articulo::with(['tipoArticulo', 'unidadMedida'])->where('estado_id', 1)->orderBy('nombre')->get();
        $tipos          = TipoArticulo::orderBy('nombre')->get();
        $unidades       = UnidadMedida::orderBy('nombre')->get();
        $recetasPreview = $this->recetasParaPreview();

        return view('ingresos.edit', compact('ingreso', 'articulos', 'tipos', 'unidades', 'recetasPreview'));
    }

    public function update(Request $request, Ingreso $ingreso)
    {
        $request->validate([
            'fecha'       => 'required|date',
            'articulo_id' => 'required|exists:articulos,id',
            'cantidad'    => 'required|numeric|min:0.0001',
            'precio'      => 'nullable|numeric|min:0',
            'observacion' => 'nullable|string|max:500',
        ]);

        $articuloViejo = Articulo::with('tipoArticulo')->findOrFail($ingreso->articulo_id);
        $articuloNuevo = Articulo::with('tipoArticulo')->findOrFail($request->articulo_id);
        $nuevaCantidad = (float) $request->cantidad;
        $erroresStock  = [];

        if (!$articuloNuevo->esProduccion() && is_null($request->precio)) {
            return back()->withInput()->withErrors(['precio' => 'El precio es obligatorio.']);
        }

        try {
            DB::transaction(function () use ($request, $ingreso, $articuloViejo, $articuloNuevo, $nuevaCantidad, &$erroresStock) {

                // 1. Revertir stock del ingreso anterior
                if ($ingreso->estado_id === 1) {
                    Stock::restar($ingreso->articulo_id, (float) $ingreso->cantidad);

                    if ($articuloViejo->esProduccion()) {
                        $recetaVieja = $this->cargarReceta($ingreso->articulo_id);
                        if ($recetaVieja) $this->devolverIngredientes($recetaVieja, (float) $ingreso->cantidad);
                    }
                }

                // 2. Verificar stock para los nuevos valores
                $recetaNueva = null;
                if ($articuloNuevo->esProduccion()) {
                    $recetaNueva = $this->cargarReceta($articuloNuevo->id);
                    if ($recetaNueva) {
                        $erroresStock = $this->verificarStock($recetaNueva, $nuevaCantidad);
                        if (!empty($erroresStock)) {
                            throw new \RuntimeException('stock_insuficiente');
                        }
                    }
                }

                // 3. Guardar cambios
                if ($articuloNuevo->esProduccion() && $recetaNueva) {
                    $precioCosto = $this->calcularCostoUnitarioReceta($recetaNueva);
                    $precio      = round($precioCosto * $nuevaCantidad, 4);
                } else {
                    $precio      = (float) $request->precio;
                    $precioCosto = $nuevaCantidad > 0 ? round($precio / $nuevaCantidad, 6) : 0;
                }

                $ingreso->update([
                    'fecha'        => $request->fecha,
                    'articulo_id'  => $request->articulo_id,
                    'cantidad'     => $nuevaCantidad,
                    'precio'       => $precio,
                    'precio_costo' => $precioCosto,
                    'observacion'  => $request->observacion,
                ]);

                // 4. Aplicar nuevo stock
                if ($ingreso->estado_id === 1) {
                    Stock::sumar($ingreso->articulo_id, $nuevaCantidad);

                    if ($recetaNueva) $this->descontarIngredientes($recetaNueva, $nuevaCantidad);

                    PrecioCosto::updateOrCreate(
                        ['articulo_id' => $ingreso->articulo_id],
                        ['costo'       => $precioCosto]
                    );
                }
            });
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'stock_insuficiente') {
                return back()->withInput()->with('errores_stock', $erroresStock);
            }
            throw $e;
        }

        $tab = $ingreso->estado_id === 1 ? 'activos' : 'inactivos';

        return redirect()->route('ingresos.index', ['tab' => $tab])->with('exito', 'Ingreso actualizado.');
    }

    public function destroy(Ingreso $ingreso)
    {
        $articulo = Articulo::with('tipoArticulo')->findOrFail($ingreso->articulo_id);

        Stock::restar($ingreso->articulo_id, (float) $ingreso->cantidad);

        if ($articulo->esProduccion()) {
            $receta = $this->cargarReceta($ingreso->articulo_id);
            if ($receta) $this->devolverIngredientes($receta, (float) $ingreso->cantidad);
        }

        $ingreso->update(['estado_id' => 2]);

        return redirect()->route('ingresos.index', ['tab' => 'inactivos'])->with('exito', 'Ingreso desactivado.');
    }

    public function reactivar(Ingreso $ingreso)
    {
        $articulo = Articulo::with('tipoArticulo')->findOrFail($ingreso->articulo_id);

        if ($articulo->esProduccion()) {
            $receta = $this->cargarReceta($ingreso->articulo_id);
            if ($receta) {
                $errores = $this->verificarStock($receta, (float) $ingreso->cantidad);
                if (!empty($errores)) {
                    return redirect()->route('ingresos.index', ['tab' => 'inactivos'])
                        ->with('errores_stock', $errores);
                }
            }
        }

        Stock::sumar($ingreso->articulo_id, (float) $ingreso->cantidad);

        if ($articulo->esProduccion()) {
            $receta = $receta ?? $this->cargarReceta($ingreso->articulo_id);
            if ($receta) $this->descontarIngredientes($receta, (float) $ingreso->cantidad);
        }

        $ingreso->update(['estado_id' => 1]);

        return redirect()->route('ingresos.index', ['tab' => 'activos'])->with('exito', 'Ingreso reactivado.');
    }

    // ── Helpers privados ──────────────────────────────────────────────────

    private function cargarReceta(int $articuloId): ?Receta
    {
        return Receta::with(['items.articulo.unidadMedida', 'items.articulo.precioCosto'])
            ->where('articulo_id', $articuloId)
            ->where('estado_id', 1)
            ->first();
    }

    private function calcularCostoUnitarioReceta(Receta $receta): float
    {
        $costo = 0;
        foreach ($receta->items->where('estado_id', 1) as $item) {
            $costo += $item->cantidad * (float) ($item->articulo->precioCosto->costo ?? 0);
        }
        return round($costo, 6);
    }

    private function verificarStock(Receta $receta, float $cantidad): array
    {
        $errores = [];
        foreach ($receta->items->where('estado_id', 1) as $item) {
            $necesario  = $cantidad * $item->cantidad;
            $disponible = (float) (Stock::where('articulo_id', $item->articulo_id)->value('cantidad') ?? 0);
            if ($disponible < $necesario) {
                $errores[] = [
                    'nombre'     => $item->articulo->nombre,
                    'necesario'  => $necesario,
                    'disponible' => $disponible,
                    'falta'      => round($necesario - $disponible, 4),
                    'unidad'     => $item->articulo->unidadMedida->abreviatura,
                ];
            }
        }
        return $errores;
    }

    private function descontarIngredientes(Receta $receta, float $cantidad): void
    {
        foreach ($receta->items->where('estado_id', 1) as $item) {
            Stock::restar($item->articulo_id, $cantidad * $item->cantidad);
        }
    }

    private function devolverIngredientes(Receta $receta, float $cantidad): void
    {
        foreach ($receta->items->where('estado_id', 1) as $item) {
            Stock::sumar($item->articulo_id, $cantidad * $item->cantidad);
        }
    }

    /**
     * Devuelve [articulo_id => [items con stock actual]] para preview JS.
     */
    private function recetasParaPreview(): array
    {
        $recetas = Receta::with(['items.articulo.unidadMedida', 'items.articulo.precioCosto'])
            ->where('estado_id', 1)
            ->get();

        $resultado = [];
        foreach ($recetas as $receta) {
            $items = [];
            foreach ($receta->items->where('estado_id', 1) as $item) {
                $stock = (float) (Stock::where('articulo_id', $item->articulo_id)->value('cantidad') ?? 0);
                $items[] = [
                    'nombre'   => $item->articulo->nombre,
                    'cantidad' => $item->cantidad,
                    'unidad'   => $item->articulo->unidadMedida->abreviatura,
                    'stock'    => $stock,
                    'costo'    => (float) ($item->articulo->precioCosto->costo ?? 0),
                ];
            }
            $resultado[$receta->articulo_id] = $items;
        }
        return $resultado;
    }
}
