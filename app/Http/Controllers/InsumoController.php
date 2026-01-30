<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\Insumo;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class InsumoController extends Controller
{
    public function index()
    {
        $insumos = Insumo::all();
        return view('insumo.index', compact('insumos'));
    }

    public function create()
    {
        return view('insumo.create');
    }
}