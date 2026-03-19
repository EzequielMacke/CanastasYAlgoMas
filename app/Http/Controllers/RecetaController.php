<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Receta;
use App\Models\RecetaItem;
use Illuminate\Http\Request;

class RecetaController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'activas');

        $activas   = Receta::with(['articulo.tipoArticulo', 'items'])->where('estado_id', 1)->orderBy('id', 'desc')->get();
        $inactivas = Receta::with(['articulo.tipoArticulo', 'items'])->where('estado_id', 2)->orderBy('id', 'desc')->get();

        return view('recetas.index', compact('activas', 'inactivas', 'tab'));
    }

    public function create(Request $request)
    {
        $articulosProduccion = Articulo::with(['tipoArticulo', 'unidadMedida'])->whereHas('tipoArticulo', function ($q) {
            $q->whereRaw('LOWER(nombre) LIKE ?', ['%produc%']);
        })->where('estado_id', 1)->orderBy('nombre')->get();

        $articulos       = Articulo::with(['tipoArticulo', 'unidadMedida'])->where('estado_id', 1)->orderBy('nombre')->get();
        $preseleccionado = $request->query('articulo_id');

        return view('recetas.create', compact('articulosProduccion', 'articulos', 'preseleccionado'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'articulo_id'              => 'required|exists:articulos,id',
            'items'                    => 'required|array|min:1',
            'items.*.articulo_id'      => 'required|exists:articulos,id',
            'items.*.cantidad'         => 'required|numeric|min:0.001',
            'items.*.unidad_medida_id' => 'required|exists:unidades_medida,id',
            'items.*.estado_id'        => 'required|in:1,2',
        ]);

        $receta = Receta::create([
            'articulo_id' => $request->articulo_id,
            'estado_id'   => 1,
        ]);

        foreach ($request->items as $item) {
            RecetaItem::create([
                'receta_id'        => $receta->id,
                'articulo_id'      => $item['articulo_id'],
                'cantidad'         => $item['cantidad'],
                'unidad_medida_id' => $item['unidad_medida_id'],
                'estado_id'        => $item['estado_id'],
            ]);
        }

        return redirect()->route('recetas.index')->with('exito', 'Receta creada correctamente.');
    }

    public function edit(Receta $receta)
    {
        $receta->load(['items.articulo', 'items.unidadMedida', 'items.estado']);

        $articulosProduccion = Articulo::with(['tipoArticulo', 'unidadMedida'])->whereHas('tipoArticulo', function ($q) {
            $q->whereRaw('LOWER(nombre) LIKE ?', ['%produc%']);
        })->where('estado_id', 1)->orderBy('nombre')->get();

        $articulos = Articulo::with(['tipoArticulo', 'unidadMedida'])->where('estado_id', 1)->orderBy('nombre')->get();

        return view('recetas.edit', compact('receta', 'articulosProduccion', 'articulos'));
    }

    public function update(Request $request, Receta $receta)
    {
        $request->validate([
            'articulo_id'              => 'required|exists:articulos,id',
            'items'                    => 'required|array|min:1',
            'items.*.articulo_id'      => 'required|exists:articulos,id',
            'items.*.cantidad'         => 'required|numeric|min:0.001',
            'items.*.unidad_medida_id' => 'required|exists:unidades_medida,id',
            'items.*.estado_id'        => 'required|in:1,2',
        ]);

        $receta->update(['articulo_id' => $request->articulo_id]);

        $receta->items()->delete();

        foreach ($request->items as $item) {
            RecetaItem::create([
                'receta_id'        => $receta->id,
                'articulo_id'      => $item['articulo_id'],
                'cantidad'         => $item['cantidad'],
                'unidad_medida_id' => $item['unidad_medida_id'],
                'estado_id'        => $item['estado_id'],
            ]);
        }

        $tab = $receta->estado_id === 1 ? 'activas' : 'inactivas';

        return redirect()->route('recetas.index', ['tab' => $tab])->with('exito', 'Receta actualizada.');
    }

    public function destroy(Receta $receta)
    {
        $receta->update(['estado_id' => 2]);

        return redirect()->route('recetas.index', ['tab' => 'inactivas'])->with('exito', 'Receta desactivada.');
    }

    public function reactivar(Receta $receta)
    {
        $receta->update(['estado_id' => 1]);

        return redirect()->route('recetas.index', ['tab' => 'activas'])->with('exito', 'Receta reactivada.');
    }
}
