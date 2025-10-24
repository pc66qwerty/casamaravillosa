<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ConfiguracionController;

// Incluir las rutas de autenticación de Breeze
require __DIR__.'/auth.php';

// Ruta principal - redirige al login si no está autenticado
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Clientes
    Route::resource('clientes', ClienteController::class);

    // Productos
    Route::resource('productos', ProductoController::class);

    // Categorías
    Route::resource('categorias', CategoriaController::class);

    // Inventario
    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::get('/inventario/ajuste/{id}', [InventarioController::class, 'ajuste'])->name('inventario.ajuste');
    Route::post('/inventario/actualizar/{id}', [InventarioController::class, 'actualizar'])->name('inventario.actualizar');

    // Ventas
    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
    Route::get('/ventas/crear', [VentaController::class, 'create'])->name('ventas.create');
    Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');
    Route::get('/ventas/{id}', [VentaController::class, 'show'])->name('ventas.show');

    // Reportes
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/exportar', [ReporteController::class, 'exportar'])->name('reportes.exportar');

    // Configuración
    Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::put('/configuracion/perfil', [ConfiguracionController::class, 'actualizarPerfil'])->name('configuracion.perfil');
    Route::put('/configuracion/password', [ConfiguracionController::class, 'actualizarPassword'])->name('configuracion.password');
});