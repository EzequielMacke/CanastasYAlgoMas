<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccesoController;
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
