<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SystemSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Roles (if needed, but assumed handled or just inserting users)
        $mecanicoId1 = DB::table('users')->insertGetId([
            'name' => 'Roberto Sánchez',
            'email' => 'rsanchez@tallerpro.com',
            'password' => Hash::make('Mecanico2026!'),
            'estado' => 1,
            'rol_id' => 3,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $mecanicoRecordId1 = DB::table('mecanicos')->insertGetId([
            'nombreCompleto' => 'Roberto Sánchez',
            'telefono' => '72011111',
            'disponible' => 1,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $mecanicoId2 = DB::table('users')->insertGetId([
            'name' => 'Miguel Herrera',
            'email' => 'mherrera@tallerpro.com',
            'password' => Hash::make('Mecanico2026!'),
            'estado' => 1,
            'rol_id' => 3,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $mecanicoRecordId2 = DB::table('mecanicos')->insertGetId([
            'nombreCompleto' => 'Miguel Herrera',
            'telefono' => '73022222',
            'disponible' => 1,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $recepcionistaId = DB::table('users')->insertGetId([
            'name' => 'Carla Recepción',
            'email' => 'crecepcion@tallerpro.com',
            'password' => Hash::make('Recepcion2026!'),
            'estado' => 1,
            'rol_id' => 2,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        // 2. Sucursal
        $sucursalId = DB::table('sucursales')->insertGetId([
            'nombre' => 'Taller Central',
            'direccion' => 'Av. Principal 123',
            'telefono' => '1234567',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        // 3. Clientes
        $clienteId1 = DB::table('clientes')->insertGetId([
            'nombreCompleto' => 'Juan Pérez',
            'ci' => '12345678',
            'telefono' => '0987654321',
            'direccion' => 'Calle 1',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $clienteId2 = DB::table('clientes')->insertGetId([
            'nombreCompleto' => 'María Gómez',
            'ci' => '87654321',
            'telefono' => '0999999999',
            'direccion' => 'Calle 2',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        // 4. Marcas y Modelos
        $marcaId = DB::table('marcas_vehiculos')->insertGetId([
            'nombre' => 'Toyota',
            'created_at' => $now,
            'updated_at' => $now
        ]);
        $modeloId = DB::table('modelos_vehiculos')->insertGetId([
            'marca_vehiculo_id' => $marcaId,
            'nombre' => 'Corolla',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $marcaId2 = DB::table('marcas_vehiculos')->insertGetId([
            'nombre' => 'Honda',
            'created_at' => $now,
            'updated_at' => $now
        ]);
        $modeloId2 = DB::table('modelos_vehiculos')->insertGetId([
            'marca_vehiculo_id' => $marcaId2,
            'nombre' => 'Civic',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        // 5. Vehiculos
        $vehiculoId1 = DB::table('vehiculos')->insertGetId([
            'cliente_id' => $clienteId1,
            'modelo_vehiculo_id' => $modeloId,
            'placa' => 'ABC-1234',
            'color' => 'Blanco',
            'anio' => 2020,
            'chasisVIN' => '12345678901234567',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $vehiculoId2 = DB::table('vehiculos')->insertGetId([
            'cliente_id' => $clienteId2,
            'modelo_vehiculo_id' => $modeloId2,
            'placa' => 'XYZ-9876',
            'color' => 'Gris',
            'anio' => 2019,
            'chasisVIN' => '98765432109876543',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        // 6. Proveedores y Categorías de Repuestos
        $proveedorId = DB::table('proveedores')->insertGetId([
            'razonSocial' => 'Autopartes Global',
            'nit' => '123456789',
            'telefono' => '1234567',
            'direccion' => 'Av. Importadores',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $catRepId = DB::table('categorias_repuestos')->insertGetId([
            'nombre' => 'Filtros y Lubricantes',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $unidadId1 = DB::table('unidades_medida')->insertGetId([
            'nombre' => 'Unidad',
            'permite_fracciones' => false,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $unidadId2 = DB::table('unidades_medida')->insertGetId([
            'nombre' => 'Litro',
            'permite_fracciones' => true,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        // 7. Repuestos e Inventario
        $repuestoId1 = DB::table('repuestos')->insertGetId([
            'categoria_repuesto_id' => $catRepId,
            'proveedor_id' => $proveedorId,
            'nombre' => 'Filtro de Aceite Bosh',
            'codigoBarras' => 'FIL001',
            'costo' => 10.50,
            'margen_ganancia' => 30.00,
            'unidad_medida_id' => $unidadId1,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $repuestoId2 = DB::table('repuestos')->insertGetId([
            'categoria_repuesto_id' => $catRepId,
            'proveedor_id' => $proveedorId,
            'nombre' => 'Aceite Sintético 5W-30',
            'codigoBarras' => 'ACE001',
            'costo' => 25.00,
            'margen_ganancia' => 20.00,
            'unidad_medida_id' => $unidadId2,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $inventarioId1 = DB::table('inventarios')->insertGetId(['repuesto_id' => $repuestoId1, 'stockActual' => 0, 'stockMinimo' => 10, 'created_at' => $now, 'updated_at' => $now]);
        $inventarioId2 = DB::table('inventarios')->insertGetId(['repuesto_id' => $repuestoId2, 'stockActual' => 0, 'stockMinimo' => 5, 'created_at' => $now, 'updated_at' => $now]);

        // 8. Servicios
        $catServId = DB::table('categorias_servicios')->insertGetId([
            'nombre' => 'Mantenimiento Preventivo',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $servicioId = DB::table('servicios')->insertGetId([
            'categoria_servicio_id' => $catServId,
            'nombre' => 'Cambio de Aceite Completo',
            'descripcion' => 'Incluye cambio de aceite, filtro y revisión de niveles.',
            'precioBase' => 25.00,
            'tiempoEstimadoHoras' => '1.50',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        // Ya no se generan órdenes de trabajo ni movimientos de inventario por defecto
        // para que el sistema inicie en cero (limpio para pruebas).
    }
}
