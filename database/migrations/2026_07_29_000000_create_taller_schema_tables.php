<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Roles y Permisos
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->string('descripcion', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('permisos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->string('modulo', 50);
            $table->timestamps();
        });

        Schema::create('permiso_rol', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rol_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('permiso_id')->constrained('permisos')->cascadeOnDelete();
            $table->timestamps();
        });

        // Modificando tabla users (asumiendo que ya existe por default de Laravel)
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('rol_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->boolean('estado')->default(true);
        });

        // 2. Comerciales (Clientes, Vehículos)
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombreCompleto', 150);
            $table->string('ci', 20)->unique();
            $table->string('telefono', 20)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('marcas_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->timestamps();
        });

        Schema::create('modelos_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marca_vehiculo_id')->constrained('marcas_vehiculos')->cascadeOnDelete();
            $table->string('nombre', 100);
            $table->timestamps();
        });

        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignId('modelo_vehiculo_id')->constrained('modelos_vehiculos')->restrictOnDelete();
            $table->string('placa', 15)->unique();
            $table->string('color', 30);
            $table->integer('anio');
            $table->string('chasisVIN', 50)->unique()->nullable();
            $table->timestamps();
        });

        // 3. Catálogo de Servicios
        Schema::create('categorias_servicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->timestamps();
        });

        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_servicio_id')->constrained('categorias_servicios')->restrictOnDelete();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->decimal('precioBase', 10, 2);
            $table->decimal('tiempoEstimadoHoras', 5, 2);
            $table->timestamps();
        });

        // 4. Personal Mecánico
        Schema::create('mecanicos', function (Blueprint $table) {
            $table->id();
            $table->string('nombreCompleto', 150);
            $table->string('telefono', 20)->nullable();
            $table->boolean('disponible')->default(true);
            $table->timestamps();
        });

        Schema::create('especialidades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->timestamps();
        });

        Schema::create('especialidad_mecanico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mecanico_id')->constrained('mecanicos')->cascadeOnDelete();
            $table->foreignId('especialidad_id')->constrained('especialidades')->cascadeOnDelete();
            $table->timestamps();
        });

        // 5. Sucursales
        Schema::create('sucursales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('direccion', 255);
            $table->string('telefono', 20)->nullable();
            $table->timestamps();
        });

        // 6. Inventario y Repuestos
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('razonSocial', 150);
            $table->string('nit', 30)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('categorias_repuestos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->timestamps();
        });

        Schema::create('repuestos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_repuesto_id')->constrained('categorias_repuestos')->restrictOnDelete();
            $table->foreignId('proveedor_id')->constrained('proveedores')->restrictOnDelete();
            $table->string('nombre', 150);
            $table->string('codigoBarras', 50)->unique()->nullable();
            $table->decimal('precioUnitario', 10, 2);
            $table->timestamps();
        });

        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repuesto_id')->unique()->constrained('repuestos')->cascadeOnDelete();
            $table->integer('stockActual')->default(0);
            $table->integer('stockMinimo')->default(5);
            $table->timestamps();
        });

        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventario_id')->constrained('inventarios')->cascadeOnDelete();
            $table->enum('tipoMovimiento', ['ENTRADA', 'SALIDA']);
            $table->integer('cantidad');
            $table->date('fecha');
            $table->timestamps();
        });

        // 7. Core: Órdenes de Trabajo
        Schema::create('ordenes_trabajo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->restrictOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->foreignId('sucursal_id')->constrained('sucursales')->restrictOnDelete();
            $table->dateTime('fechaIngreso');
            $table->dateTime('fechaEntregaEstimada')->nullable();
            $table->enum('estado', ['PENDIENTE', 'DIAGNOSTICO', 'EN REPARACION', 'FINALIZADO', 'ENTREGADO'])->default('PENDIENTE');
            $table->text('diagnostico')->nullable();
            $table->timestamps();
        });

        Schema::create('detalles_orden_trabajo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_trabajo_id')->constrained('ordenes_trabajo')->cascadeOnDelete();
            $table->foreignId('servicio_id')->constrained('servicios')->restrictOnDelete();
            $table->foreignId('mecanico_id')->nullable()->constrained('mecanicos')->nullOnDelete();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('horasTrabajadas', 5, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('detalle_repuesto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detalle_orden_trabajo_id')->constrained('detalles_orden_trabajo')->cascadeOnDelete();
            $table->foreignId('repuesto_id')->constrained('repuestos')->restrictOnDelete();
            $table->integer('cantidad')->default(1);
            $table->decimal('precioVenta', 10, 2);
            $table->timestamps();
        });

        // 8. Finanzas
        Schema::create('metodos_pago', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->timestamps();
        });

        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_trabajo_id')->constrained('ordenes_trabajo')->restrictOnDelete();
            $table->foreignId('metodo_pago_id')->constrained('metodos_pago')->restrictOnDelete();
            $table->decimal('montoPagado', 10, 2);
            $table->dateTime('fechaPago');
            $table->enum('estado', ['COMPLETADO', 'PENDIENTE', 'ANULADO'])->default('COMPLETADO');
            $table->timestamps();
        });

        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('tipoReporte', 100);
            $table->dateTime('fechaGeneracion');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reportes');
        Schema::dropIfExists('pagos');
        Schema::dropIfExists('metodos_pago');
        Schema::dropIfExists('detalle_repuesto');
        Schema::dropIfExists('detalles_orden_trabajo');
        Schema::dropIfExists('ordenes_trabajo');
        Schema::dropIfExists('movimientos_inventario');
        Schema::dropIfExists('inventarios');
        Schema::dropIfExists('repuestos');
        Schema::dropIfExists('categorias_repuestos');
        Schema::dropIfExists('proveedores');
        Schema::dropIfExists('sucursales');
        Schema::dropIfExists('especialidad_mecanico');
        Schema::dropIfExists('especialidades');
        Schema::dropIfExists('mecanicos');
        Schema::dropIfExists('servicios');
        Schema::dropIfExists('categorias_servicios');
        Schema::dropIfExists('vehiculos');
        Schema::dropIfExists('modelos_vehiculos');
        Schema::dropIfExists('marcas_vehiculos');
        Schema::dropIfExists('clientes');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['rol_id']);
            $table->dropColumn(['rol_id', 'estado']);
        });

        Schema::dropIfExists('permiso_rol');
        Schema::dropIfExists('permisos');
        Schema::dropIfExists('roles');
    }
};
