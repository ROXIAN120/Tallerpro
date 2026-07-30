<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServicioController;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

// Módulo de Seguimiento Público (Cliente) - Accesible para invitados y autenticados
Route::get('/seguimiento', [\App\Http\Controllers\SeguimientoClienteController::class, 'index'])->name('seguimiento.index');
Route::post('/seguimiento', [\App\Http\Controllers\SeguimientoClienteController::class, 'buscarPlaca'])->name('seguimiento.buscar');

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('servicios', ServicioController::class);
    
    // Dashboard Gerencial
    Route::middleware('role:Administrador,Recepcionista')->get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Directorio de Clientes
    Route::middleware('role:Administrador,Recepcionista')->get('/clientes/directorio', [\App\Http\Controllers\ClienteController::class, 'index'])->name('clientes.directorio');

    // API para autocompletado de vehículo
    Route::get('/api/vehiculos/{placa}', [\App\Http\Controllers\ClienteController::class, 'buscarPorPlaca']);

    // Historial de Cliente/Vehículo
    Route::middleware('role:Administrador,Recepcionista')->get('/clientes/vehiculo/{placa}/historial', [\App\Http\Controllers\ClienteController::class, 'historial'])->name('clientes.historial');
    Route::middleware('role:Administrador,Recepcionista')->delete('/clientes/vehiculo/{placa}', [\App\Http\Controllers\ClienteController::class, 'destroy'])->name('clientes.destroy');

    // Módulo 1: Inventario y Precios
    Route::prefix('inventario')->group(function () {
        Route::middleware('role:Administrador')->group(function () {
            Route::get('/precios', [\App\Http\Controllers\InventarioController::class, 'preciosIndex'])->name('inventario.precios');
            Route::post('/precios/actualizar', [\App\Http\Controllers\InventarioController::class, 'actualizarPrecio'])->name('inventario.actualizarPrecio');
            
            // Módulo de Catálogo Maestro de Inventario
            Route::resource('productos', \App\Http\Controllers\RepuestoController::class)->names('inventario.productos');
            
            // Configuración de Catálogo
            Route::post('/categorias', [\App\Http\Controllers\RepuestoController::class, 'storeCategoria'])->name('inventario.categorias.store');
            Route::put('/categorias/{id}', [\App\Http\Controllers\RepuestoController::class, 'updateCategoria'])->name('inventario.categorias.update');
            Route::delete('/categorias/{id}', [\App\Http\Controllers\RepuestoController::class, 'destroyCategoria'])->name('inventario.categorias.destroy');
            
            Route::post('/unidades', [\App\Http\Controllers\RepuestoController::class, 'storeUnidad'])->name('inventario.unidades.store');
            Route::put('/unidades/{id}', [\App\Http\Controllers\RepuestoController::class, 'updateUnidad'])->name('inventario.unidades.update');
            Route::delete('/unidades/{id}', [\App\Http\Controllers\RepuestoController::class, 'destroyUnidad'])->name('inventario.unidades.destroy');
        });
        
        // Módulo de Kardex
        Route::middleware('role:Administrador,Mecanico')->group(function () {
            Route::get('/kardex', [\App\Http\Controllers\InventarioController::class, 'kardexIndex'])->name('inventario.kardex');
            Route::post('/kardex/movimiento', [\App\Http\Controllers\InventarioController::class, 'registrarMovimiento'])->name('inventario.movimiento');
        });
    });

    // Módulo 2: Taller y Órdenes
    Route::prefix('taller')->group(function () {
        Route::get('/kanban', [\App\Http\Controllers\OrdenTrabajoController::class, 'kanbanIndex'])->name('taller.kanban');
        Route::post('/kanban/estado', [\App\Http\Controllers\OrdenTrabajoController::class, 'cambiarEstado'])->name('taller.estado');
        Route::middleware('role:Administrador,Recepcionista')->group(function () {
            Route::post('/ordenes/cancelar', [\App\Http\Controllers\OrdenTrabajoController::class, 'cancelar'])->name('taller.cancelar');
            Route::get('/ordenes/crear', [\App\Http\Controllers\OrdenTrabajoController::class, 'crearIndex'])->name('taller.ordenes.crear');
            Route::post('/ordenes/guardar', [\App\Http\Controllers\OrdenTrabajoController::class, 'store'])->name('taller.ordenes.guardar');
        });
        
        Route::get('/ordenes/{id}', [\App\Http\Controllers\OrdenTrabajoController::class, 'show'])->name('taller.ordenes.show');
        Route::post('/ordenes/{id}/repuestos', [\App\Http\Controllers\OrdenTrabajoController::class, 'agregarRepuesto'])->name('taller.ordenes.repuestos');
        Route::delete('/ordenes/{id}/repuestos/{detalle_id}/{repuesto_id}', [\App\Http\Controllers\OrdenTrabajoController::class, 'eliminarRepuesto'])->name('taller.ordenes.eliminarRepuesto');
        
        Route::post('/ordenes/{id}/servicios', [\App\Http\Controllers\OrdenTrabajoController::class, 'agregarServicio'])->name('taller.ordenes.servicios');
        Route::delete('/ordenes/{id}/servicios/{detalle_id}', [\App\Http\Controllers\OrdenTrabajoController::class, 'eliminarServicio'])->name('taller.ordenes.eliminarServicio');

        // Gestión de Servicios (Mano de Obra)
        Route::middleware('role:Administrador')->group(function () {
            Route::get('/servicios', [\App\Http\Controllers\ServicioController::class, 'index'])->name('taller.servicios');
            Route::post('/servicios', [\App\Http\Controllers\ServicioController::class, 'storeServicio']);
            Route::put('/servicios/{id}', [\App\Http\Controllers\ServicioController::class, 'updateServicio']);
            Route::delete('/servicios/{id}', [\App\Http\Controllers\ServicioController::class, 'destroyServicio']);
            
            Route::post('/servicios/categorias', [\App\Http\Controllers\ServicioController::class, 'storeCategoria']);
            Route::put('/servicios/categorias/{id}', [\App\Http\Controllers\ServicioController::class, 'updateCategoria']);
            Route::delete('/servicios/categorias/{id}', [\App\Http\Controllers\ServicioController::class, 'destroyCategoria']);
        });
    });

    // Módulo Analítico y Ecosistema Financiero
    Route::prefix('reportes')->middleware('role:Administrador')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\ReporteController::class, 'index'])->name('reportes.dashboard');
        Route::get('/exportar-excel', [\App\Http\Controllers\ReporteController::class, 'exportarExcel'])->name('reportes.exportar');
        Route::get('/orden/{id}/pdf', [\App\Http\Controllers\ReporteController::class, 'descargarFactura'])->name('reportes.factura');
    });
});
