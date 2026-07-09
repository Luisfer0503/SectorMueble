<?php

use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\AdminController;
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

// Aplicación de cupones por el cliente
Route::post('/carrito/aplicar-cupon', [PrincipalController::class, 'aplicarCupon'])->name('carrito.cupon.aplicar');
Route::post('/carrito/quitar-cupon', [PrincipalController::class, 'quitarCupon'])->name('carrito.cupon.quitar');

// Rutas de Checkout y Procesamiento de Pedido (Protegidas por Autenticación)
Route::get('/finalizar-compra', [PrincipalController::class, 'finalizarCompra'])->name('checkout')->middleware('auth');
Route::post('/finalizar-compra', [PrincipalController::class, 'procesarCompra'])->name('checkout.procesar')->middleware('auth');

// Página de éxito del Pedido
Route::get('/pedido-confirmado/{id}', [PrincipalController::class, 'pedidoConfirmado'])->name('pedido.confirmado');

// Rutas de Autenticación
Route::get('/iniciar-sesion', [PrincipalController::class, 'mostrarLogin'])->name('login');
Route::post('/iniciar-sesion', [PrincipalController::class, 'login'])->name('login.procesar');

Route::get('/registro', [PrincipalController::class, 'mostrarRegistro'])->name('registro');
Route::post('/registro', [PrincipalController::class, 'registro'])->name('registro.procesar');

Route::get('/cerrar-sesion', [PrincipalController::class, 'logout'])->name('logout');
Route::post('/cerrar-sesion', [PrincipalController::class, 'logout'])->name('logout.post');


// --- RUTAS DE ADMINISTRACIÓN (Protegidas por verificación de rol de administrador) ---
Route::prefix('admin')->middleware('es_admin')->group(function () {
    // Dashboard general
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // CRUD de Muebles / Artículos
    Route::get('/muebles', [AdminController::class, 'productosIndex'])->name('admin.productos');
    Route::get('/muebles/crear', [AdminController::class, 'productosCrear'])->name('admin.productos.crear');
    Route::post('/muebles/guardar', [AdminController::class, 'productosGuardar'])->name('admin.productos.guardar');
    Route::get('/muebles/editar/{id}', [AdminController::class, 'productosEditar'])->name('admin.productos.editar');
    Route::post('/muebles/actualizar/{id}', [AdminController::class, 'productosActualizar'])->name('admin.productos.actualizar');
    Route::get('/muebles/eliminar/{id}', [AdminController::class, 'productosEliminar'])->name('admin.productos.eliminar');
    Route::get('/muebles/descuento/{id}', [AdminController::class, 'productosDescuento'])->name('admin.productos.descuento');
    Route::post('/muebles/descuento/{id}', [AdminController::class, 'productosAplicarDescuento'])->name('admin.productos.aplicar_descuento');

    // CRUD de Cupones / Descuentos
    Route::get('/cupones', [AdminController::class, 'cuponesIndex'])->name('admin.cupones');
    Route::get('/cupones/crear', [AdminController::class, 'cuponesCrear'])->name('admin.cupones.crear');
    Route::post('/cupones/guardar', [AdminController::class, 'cuponesGuardar'])->name('admin.cupones.guardar');
    Route::get('/cupones/editar/{id}', [AdminController::class, 'cuponesEditar'])->name('admin.cupones.editar');
    Route::post('/cupones/actualizar/{id}', [AdminController::class, 'cuponesActualizar'])->name('admin.cupones.actualizar');
    Route::get('/cupones/eliminar/{id}', [AdminController::class, 'cuponesEliminar'])->name('admin.cupones.eliminar');

    // Gestión y Seguimiento de Pedidos
    Route::get('/pedidos', [AdminController::class, 'pedidosIndex'])->name('admin.pedidos');
    Route::get('/pedidos/detalle/{id}', [AdminController::class, 'pedidosDetalle'])->name('admin.pedidos.detalle');
    Route::post('/pedidos/actualizar-estado/{id}', [AdminController::class, 'pedidosActualizarEstado'])->name('admin.pedidos.actualizar_estado');
});
