<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\PrecioCosto;
use App\Models\TipoArticulo;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticuloController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'activos');

        $activos   = Articulo::with(['tipoArticulo', 'unidadMedida', 'receta'])->where('estado_id', 1)->orderBy('nombre')->get();
        $inactivos = Articulo::with(['tipoArticulo', 'unidadMedida', 'receta'])->where('estado_id', 2)->orderBy('nombre')->get();

        return view('articulos.index', compact('activos', 'inactivos', 'tab'));
    }

    public function create()
    {
        $tipos   = TipoArticulo::orderBy('nombre')->get();
        $unidades = UnidadMedida::orderBy('nombre')->get();

        return view('articulos.create', compact('tipos', 'unidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:150',
            'descripcion'      => 'nullable|string|max:1000',
            'tipo_articulo_id' => 'required|exists:tipos_articulo,id',
            'unidad_medida_id' => 'required|exists:unidades_medida,id',
            'foto'             => 'nullable|image|max:2048',
            'precio_costo'     => 'nullable|numeric|min:0',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('articulos', 'public');
        }

        $articulo = Articulo::create([
            'nombre'           => $request->nombre,
            'descripcion'      => $request->descripcion,
            'tipo_articulo_id' => $request->tipo_articulo_id,
            'unidad_medida_id' => $request->unidad_medida_id,
            'foto'             => $fotoPath,
            'estado_id'        => 1,
        ]);

        $articulo->load('tipoArticulo');
        if ($articulo->esServicio() && !is_null($request->precio_costo)) {
            PrecioCosto::updateOrCreate(
                ['articulo_id' => $articulo->id],
                ['costo'       => $request->precio_costo]
            );
        }

        return redirect()->route('articulos.index')->with('exito', 'Artículo creado correctamente.');
    }

    public function edit(Articulo $articulo)
    {
        $articulo->load(['tipoArticulo', 'precioCosto']);
        $tipos    = TipoArticulo::orderBy('nombre')->get();
        $unidades = UnidadMedida::orderBy('nombre')->get();

        return view('articulos.edit', compact('articulo', 'tipos', 'unidades'));
    }

    public function update(Request $request, Articulo $articulo)
    {
        $request->validate([
            'nombre'           => 'required|string|max:150',
            'descripcion'      => 'nullable|string|max:1000',
            'tipo_articulo_id' => 'required|exists:tipos_articulo,id',
            'unidad_medida_id' => 'required|exists:unidades_medida,id',
            'foto'             => 'nullable|image|max:2048',
            'precio_costo'     => 'nullable|numeric|min:0',
        ]);

        $fotoPath = $articulo->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('articulos', 'public');
        }

        $articulo->update([
            'nombre'           => $request->nombre,
            'descripcion'      => $request->descripcion,
            'tipo_articulo_id' => $request->tipo_articulo_id,
            'unidad_medida_id' => $request->unidad_medida_id,
            'foto'             => $fotoPath,
        ]);

        $articulo->load('tipoArticulo');
        if ($articulo->esServicio() && !is_null($request->precio_costo)) {
            PrecioCosto::updateOrCreate(
                ['articulo_id' => $articulo->id],
                ['costo'       => $request->precio_costo]
            );
        }

        $tab = $articulo->estado_id === 1 ? 'activos' : 'inactivos';

        return redirect()->route('articulos.index', ['tab' => $tab])->with('exito', 'Artículo actualizado.');
    }

    public function destroy(Articulo $articulo)
    {
        $articulo->update(['estado_id' => 2]);

        return redirect()->route('articulos.index', ['tab' => 'inactivos'])->with('exito', 'Artículo desactivado.');
    }

    public function reactivar(Articulo $articulo)
    {
        $articulo->update(['estado_id' => 1]);

        return redirect()->route('articulos.index', ['tab' => 'activos'])->with('exito', 'Artículo reactivado.');
    }

    public function toggleCatalogo(Articulo $articulo)
    {
        $articulo->update(['visible_catalogo' => !$articulo->visible_catalogo]);

        return back()->with('exito', $articulo->visible_catalogo
            ? "'{$articulo->nombre}' ahora es visible en el catálogo."
            : "'{$articulo->nombre}' ocultado del catálogo."
        );
    }

    /**
     * Creación rápida de artículo vía AJAX (modal en ingresos).
     */
    public function storeRapido(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:150',
            'tipo_articulo_id' => 'required|exists:tipos_articulo,id',
            'unidad_medida_id' => 'required|exists:unidades_medida,id',
        ]);

        $articulo = Articulo::create([
            'nombre'           => $request->nombre,
            'tipo_articulo_id' => $request->tipo_articulo_id,
            'unidad_medida_id' => $request->unidad_medida_id,
            'estado_id'        => 1,
        ]);

        $articulo->load(['tipoArticulo', 'unidadMedida']);

        return response()->json([
            'id'           => $articulo->id,
            'nombre'       => $articulo->nombre,
            'tipo'         => $articulo->tipoArticulo->nombre,
            'unidad_id'    => $articulo->unidad_medida_id,
            'unidad_nombre'=> $articulo->unidadMedida->nombre,
            'unidad_abrev' => $articulo->unidadMedida->abreviatura,
            'unidad_factor'=> $articulo->unidadMedida->factor_conversion,
        ]);
    }
}
