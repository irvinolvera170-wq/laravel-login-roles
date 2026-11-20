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

// Admin y empleado: listar, ver, crear y editar
Route::middleware(['auth', 'role:admin,empleado', 'admin.codigo'])
    ->prefix('usuarios')->name('usuarios.')
    ->where(['usuario' => '[0-9]+'])
    ->group(function () {
        Route::get('/', [UsuarioController::class, 'index'])->name('index');
        Route::get('/crear', [UsuarioController::class, 'create'])->name('create');
        Route::post('/', [UsuarioController::class, 'store'])->name('store');
        Route::get('/{usuario}', [UsuarioController::class, 'show'])->name('show');
        Route::get('/{usuario}/editar', [UsuarioController::class, 'edit'])->name('edit');
        Route::put('/{usuario}', [UsuarioController::class, 'update'])->name('update');
    });

// Solo admin: eliminar, papelera y restaurar
Route::middleware(['auth', 'role:admin', 'admin.codigo'])
    ->prefix('usuarios')->name('usuarios.')
    ->where(['usuario' => '[0-9]+'])
    ->group(function () {
        Route::get('/papelera', [UsuarioController::class, 'papelera'])->name('papelera');
        Route::delete('/{usuario}', [UsuarioController::class, 'destroy'])->name('destroy');
        Route::patch('/{usuario}/restaurar', [UsuarioController::class, 'restore'])
            ->withTrashed()->name('restore');
        Route::delete('/{usuario}/permanente', [UsuarioController::class, 'forceDelete'])
            ->withTrashed()->name('forceDelete');
    });