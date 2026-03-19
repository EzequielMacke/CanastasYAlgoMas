<?php

namespace App\Http\Controllers;

use App\Models\Vendedor;
use Illuminate\Http\Request;

class VendedorController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'activos');

        $activos   = Vendedor::where('estado_id', 1)->orderBy('apellido')->get();
        $inactivos = Vendedor::where('estado_id', 2)->orderBy('apellido')->get();

        return view('vendedores.index', compact('activos', 'inactivos', 'tab'));
    }

    public function create()
    {
        return view('vendedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
        ]);

        Vendedor::create([
            'nombre'    => $request->nombre,
            'apellido'  => $request->apellido,
            'estado_id' => 1,
        ]);

        return redirect()->route('vendedores.index')->with('exito', 'Vendedor creado correctamente.');
    }

    public function edit(Vendedor $vendedor)
    {
        return view('vendedores.edit', compact('vendedor'));
    }

    public function update(Request $request, Vendedor $vendedor)
    {
        $request->validate([
            'nombre'   => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
        ]);

        $vendedor->update([
            'nombre'   => $request->nombre,
            'apellido' => $request->apellido,
        ]);

        $tab = $vendedor->estado_id === 1 ? 'activos' : 'inactivos';

        return redirect()->route('vendedores.index', ['tab' => $tab])->with('exito', 'Vendedor actualizado.');
    }

    public function destroy(Vendedor $vendedor)
    {
        $vendedor->update(['estado_id' => 2]);

        return redirect()->route('vendedores.index', ['tab' => 'inactivos'])->with('exito', 'Vendedor desactivado.');
    }

    public function reactivar(Vendedor $vendedor)
    {
        $vendedor->update(['estado_id' => 1]);

        return redirect()->route('vendedores.index', ['tab' => 'activos'])->with('exito', 'Vendedor reactivado.');
    }
}
