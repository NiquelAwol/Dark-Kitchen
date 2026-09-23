<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DomiciliarioController;
use App\Http\Controllers\MetodoPagoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - QuickFood ERP
|--------------------------------------------------------------------------
| Sistema de Gestión Empresarial para Dark Kitchen / Venta de Alimentos
| Rutas RESTful y control de flujo de pedidos.
*/

// Redirección principal al Dashboard operativo
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Panel de control / Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// CRUD Resource Principal: Pedidos
Route::resource('pedidos', PedidoController::class);
Route::patch('pedidos/{pedido}/cambiar-estado', [PedidoController::class, 'cambiarEstado'])->name('pedidos.cambiar-estado');

// CRUD Resources del Catálogo y Gestión
Route::resource('productos', ProductoController::class);
Route::resource('clientes', ClienteController::class);
Route::resource('categorias', CategoriaController::class);
Route::resource('domiciliarios', DomiciliarioController::class);
Route::resource('metodos-pago', MetodoPagoController::class);
