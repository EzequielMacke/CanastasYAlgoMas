<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccesoController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UsuarioController;

// Ruta para mostrar el formulario de login
Route::get('/', [AccesoController::class, 'loginForm'])->name('login.form');

// Ruta para procesar el login
Route::post('/login', [AccesoController::class, 'login'])->name('login.process');

// Ruta para cerrar sesión
Route::post('/logout', [AccesoController::class, 'logout'])->name('logout');

// Ruta para el index del menú
Route::get('/menu/index', [MenuController::class, 'index'])->name('menu.index');

// Ruta para el index de usuarios
Route::get('/usuarios/index', [UsuarioController::class, 'index'])->name('usuarios.index');

// Ruta para mostrar el formulario de agregar usuario
Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');

// Ruta para procesar la invitación de usuario
Route::post('/usuarios/invitar', [UsuarioController::class, 'invitar'])->name('usuarios.invitar');

// Ruta para el index de ingresos
Route::get('/ingresos/index', [IngresoController::class, 'index'])->name('ingreso.index');

// Ruta para el formulario de creación de ingresos
Route::get('/ingresos/create', [IngresoController::class, 'create'])->name('ingreso.create');

//Ruta para almacenar un nuevo ingreso
Route::post('/ingresos/store', [IngresoController::class, 'store'])->name('ingreso.store');

// Ruta para crear un insumo desde la interfaz de ingreso (AJAX)
Route::post('/ingresos/crear-insumo', [IngresoController::class, 'crearInsumo'])->name('ingreso.crearInsumo');

// Ruta para el index de insumos
Route::get('/insumos/index', [InsumoController::class, 'index'])->name('insumo.index');

// Ruta para mostrar el formulario de agregar insumo
Route::get('/insumos/create', [InsumoController::class, 'create'])->name('insumo.create');

// Ruta para el index de inventario
Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');

