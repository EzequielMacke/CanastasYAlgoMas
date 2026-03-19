<?php

use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PrecioVentaController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\ComisionController;
use App\Http\Controllers\VendedorController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;

// Catálogo público (sin autenticación)
Route::get('catalogo',            [CatalogoController::class, 'index'])->name('catalogo.index');
Route::get('catalogo/{articulo}', [CatalogoController::class, 'show'])->name('catalogo.show');

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('ventas',        [VentaController::class, 'index'])->name('ventas.index');
    Route::get('ventas/create', [VentaController::class, 'create'])->name('ventas.create');
    Route::post('ventas',       [VentaController::class, 'store'])->name('ventas.store');
    Route::get('ventas/{venta}', [VentaController::class, 'show'])->name('ventas.show');

    Route::middleware(['solo.admin'])->group(function () {
        Route::get('ventas/{venta}/edit',        [VentaController::class, 'edit'])->name('ventas.edit');
        Route::put('ventas/{venta}',             [VentaController::class, 'update'])->name('ventas.update');
        Route::delete('ventas/{venta}',          [VentaController::class, 'destroy'])->name('ventas.destroy');
        Route::patch('ventas/{venta}/reactivar', [VentaController::class, 'reactivar'])->name('ventas.reactivar');
    });

    Route::middleware(['solo.admin'])->group(function () {
        Route::get('vendedores',                        [VendedorController::class, 'index'])->name('vendedores.index');
        Route::get('vendedores/create',                 [VendedorController::class, 'create'])->name('vendedores.create');
        Route::post('vendedores',                       [VendedorController::class, 'store'])->name('vendedores.store');
        Route::get('vendedores/{vendedor}/edit',        [VendedorController::class, 'edit'])->name('vendedores.edit');
        Route::put('vendedores/{vendedor}',             [VendedorController::class, 'update'])->name('vendedores.update');
        Route::delete('vendedores/{vendedor}',          [VendedorController::class, 'destroy'])->name('vendedores.destroy');
        Route::patch('vendedores/{vendedor}/reactivar', [VendedorController::class, 'reactivar'])->name('vendedores.reactivar');

        Route::get('articulos',                         [ArticuloController::class, 'index'])->name('articulos.index');
        Route::get('articulos/create',                  [ArticuloController::class, 'create'])->name('articulos.create');
        Route::post('articulos',                        [ArticuloController::class, 'store'])->name('articulos.store');
        Route::get('articulos/{articulo}/edit',         [ArticuloController::class, 'edit'])->name('articulos.edit');
        Route::put('articulos/{articulo}',              [ArticuloController::class, 'update'])->name('articulos.update');
        Route::delete('articulos/{articulo}',           [ArticuloController::class, 'destroy'])->name('articulos.destroy');
        Route::patch('articulos/{articulo}/reactivar',  [ArticuloController::class, 'reactivar'])->name('articulos.reactivar');

        Route::get('recetas',                        [RecetaController::class, 'index'])->name('recetas.index');
        Route::get('recetas/create',                 [RecetaController::class, 'create'])->name('recetas.create');
        Route::post('recetas',                       [RecetaController::class, 'store'])->name('recetas.store');
        Route::get('recetas/{receta}/edit',          [RecetaController::class, 'edit'])->name('recetas.edit');
        Route::put('recetas/{receta}',               [RecetaController::class, 'update'])->name('recetas.update');
        Route::delete('recetas/{receta}',            [RecetaController::class, 'destroy'])->name('recetas.destroy');
        Route::patch('recetas/{receta}/reactivar',   [RecetaController::class, 'reactivar'])->name('recetas.reactivar');

        Route::post('articulos/rapido',               [ArticuloController::class, 'storeRapido'])->name('articulos.rapido');

        Route::get('ingresos',                        [IngresoController::class, 'index'])->name('ingresos.index');
        Route::get('ingresos/create',                 [IngresoController::class, 'create'])->name('ingresos.create');
        Route::post('ingresos',                       [IngresoController::class, 'store'])->name('ingresos.store');
        Route::get('ingresos/{ingreso}/edit',         [IngresoController::class, 'edit'])->name('ingresos.edit');
        Route::put('ingresos/{ingreso}',              [IngresoController::class, 'update'])->name('ingresos.update');
        Route::delete('ingresos/{ingreso}',           [IngresoController::class, 'destroy'])->name('ingresos.destroy');
        Route::patch('ingresos/{ingreso}/reactivar',  [IngresoController::class, 'reactivar'])->name('ingresos.reactivar');

        Route::get('stock', [StockController::class, 'index'])->name('stock.index');

        Route::get('precios-venta',  [PrecioVentaController::class, 'index'])->name('precios-venta.index');
        Route::post('precios-venta', [PrecioVentaController::class, 'store'])->name('precios-venta.store');

        Route::get('comisiones', [ComisionController::class, 'index'])->name('comisiones.index');
    });

});
