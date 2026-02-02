<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index()
    {
        $inventarios = Inventario::with(['insumo', 'ingreso', 'unidadMedida'])->get();
        return view('inventario.index', compact('inventarios'));
    }
}
