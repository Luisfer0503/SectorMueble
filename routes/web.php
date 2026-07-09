<?php

use App\Http\Controllers\PrincipalController;
use Illuminate\Support\Facades\Route;

// Ruta de Inicio
Route::get('/', [PrincipalController::class, 'inicio'])->name('inicio');
Route::redirect('/inicio', '/');

// Catálogo y filtros
Route::get('/catalogo', [PrincipalController::class, 'catalogo'])->name('catalogo');

// Ficha de detalle de producto
Route::get('/productos/{id}', [PrincipalController::class, 'detalle'])->name('productos.detalle');

// Rutas de Carrito
Route::get('/carrito', [PrincipalController::class, 'carrito'])->name('carrito');
Route::post('/carrito/agregar/{id}', [PrincipalController::class, 'agregarAlCarrito'])->name('carrito.agregar');
Route::post('/carrito/actualizar/{id}', [PrincipalController::class, 'actualizarCarrito'])->name('carrito.actualizar');
Route::get('/carrito/eliminar/{id}', [PrincipalController::class, 'eliminarDelCarrito'])->name('carrito.eliminar');

// Rutas de Checkout y Procesamiento de Pedido
Route::get('/finalizar-compra', [PrincipalController::class, 'finalizarCompra'])->name('checkout');
Route::post('/finalizar-compra', [PrincipalController::class, 'procesarCompra'])->name('checkout.procesar');

// Página de éxito del Pedido
Route::get('/pedido-confirmado/{id}', [PrincipalController::class, 'pedidoConfirmado'])->name('pedido.confirmado');
