<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CodigoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

    Route::get('/registro', [RegistroController::class, 'showRegister'])->name('registro');
    Route::post('/registro', [RegistroController::class, 'register'])
        ->middleware('throttle:10,1')
        ->name('registro.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/verificar-codigo', [CodigoController::class, 'showForm'])->name('codigo.form');
    Route::post('/verificar-codigo', [CodigoController::class, 'verificar'])
        ->middleware('throttle:5,1')
        ->name('codigo.verificar');
        Route::post('/reenviar-codigo', [CodigoController::class, 'reenviar'])
    ->middleware('throttle:3,1')
    ->name('codigo.reenviar');

    Route::get('/home', [HomeController::class, 'index'])
        ->middleware('admin.codigo')
        ->name('home');
});

Route::middleware(['auth', 'solo.admin', 'admin.codigo'])->group(function () {
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::patch('/usuarios/{user}/rol', [UsuarioController::class, 'updateRole'])->name('usuarios.rol');
});