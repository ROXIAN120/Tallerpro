<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoriaServicio;
use App\Models\Servicio;
use App\Models\CategoriaRepuesto;
use App\Models\Proveedor;
use App\Models\Repuesto;
use App\Models\Inventario;
use App\Models\MovimientoInventario;
use Illuminate\Support\Facades\DB;

class CatalogDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // ==========================================
            // 1. CATÁLOGO DE SERVICIOS (MANO DE OBRA)
            // ==========================================

            $catMantenimiento = CategoriaServicio::firstOrCreate(['nombre' => 'Mantenimiento Preventivo']);
            $catMecanica = CategoriaServicio::firstOrCreate(['nombre' => 'Mecánica General']);
            $catElectrico = CategoriaServicio::firstOrCreate(['nombre' => 'Sistema Eléctrico']);
            $catFrenos = CategoriaServicio::firstOrCreate(['nombre' => 'Frenos y Suspensión']);

            // Mantenimiento
            Servicio::firstOrCreate(['nombre' => 'Cambio de Aceite Básico (Mano de Obra)'], [
                'categoria_servicio_id' => $catMantenimiento->id,
                'descripcion' => 'Drenaje de aceite, cambio de filtro y rellenado.',
                'precioBase' => 15.00,
                'tiempoEstimadoHoras' => 0.5
            ]);
            Servicio::firstOrCreate(['nombre' => 'Mantenimiento 10,000 km'], [
                'categoria_servicio_id' => $catMantenimiento->id,
                'descripcion' => 'Cambio de aceite, filtro, revisión de niveles, presión de llantas, limpieza de cuerpo de aceleración.',
                'precioBase' => 45.00,
                'tiempoEstimadoHoras' => 2.0
            ]);

            // Mecánica
            Servicio::firstOrCreate(['nombre' => 'Cambio de Bujías'], [
                'categoria_servicio_id' => $catMecanica->id,
                'descripcion' => 'Reemplazo de bujías (precio varía según la dificultad de acceso al motor).',
                'precioBase' => 25.00,
                'tiempoEstimadoHoras' => 1.0
            ]);
            Servicio::firstOrCreate(['nombre' => 'Cambio de Correa de Distribución'], [
                'categoria_servicio_id' => $catMecanica->id,
                'descripcion' => 'Reemplazo de correa y tensores (Trabajo complejo).',
                'precioBase' => 120.00,
                'tiempoEstimadoHoras' => 5.0
            ]);

            // Frenos y Suspensión
            Servicio::firstOrCreate(['nombre' => 'Cambio de Pastillas de Freno (Delanteras)'], [
                'categoria_servicio_id' => $catFrenos->id,
                'descripcion' => 'Desmontaje de llantas, purgado y reemplazo de pastillas.',
                'precioBase' => 30.00,
                'tiempoEstimadoHoras' => 1.5
            ]);
            Servicio::firstOrCreate(['nombre' => 'Alineación y Balanceo'], [
                'categoria_servicio_id' => $catFrenos->id,
                'descripcion' => 'Alineación por computadora y balanceo dinámico de 4 llantas.',
                'precioBase' => 40.00,
                'tiempoEstimadoHoras' => 1.0
            ]);

            // ==========================================
            // 2. CATÁLOGO DE REPUESTOS E INVENTARIO
            // ==========================================

            $catLubricantes = CategoriaRepuesto::firstOrCreate(['nombre' => 'Lubricantes y Líquidos']);
            $catFiltros = CategoriaRepuesto::firstOrCreate(['nombre' => 'Filtros']);
            $catLlantas = CategoriaRepuesto::firstOrCreate(['nombre' => 'Llantas']);
            $catFrenosRep = CategoriaRepuesto::firstOrCreate(['nombre' => 'Frenos']);
            $catIgnicion = CategoriaRepuesto::firstOrCreate(['nombre' => 'Ignición']);

            $proveedorGen = Proveedor::firstOrCreate(['razonSocial' => 'Distribuidora Automotriz Central'], [
                'nit' => '123456789',
                'telefono' => '555-0101',
                'direccion' => 'Av. Industrial 123'
            ]);
            $proveedorLlantas = Proveedor::firstOrCreate(['razonSocial' => 'Llantas Importadas S.A.'], [
                'nit' => '987654321',
                'telefono' => '555-0202',
                'direccion' => 'Calle Comercio 45'
            ]);

            $repuestos = [
                // Lubricantes
                ['cat' => $catLubricantes->id, 'prov' => $proveedorGen->id, 'nombre' => 'Aceite Sintético Castrol 5W-30', 'precio' => 12.50, 'costo' => 9.00, 'stock' => 50, 'unidad' => 'Litro'],
                ['cat' => $catLubricantes->id, 'prov' => $proveedorGen->id, 'nombre' => 'Aceite Mineral 20W-50', 'precio' => 8.00, 'costo' => 5.50, 'stock' => 100, 'unidad' => 'Litro'],
                ['cat' => $catLubricantes->id, 'prov' => $proveedorGen->id, 'nombre' => 'Líquido de Frenos DOT 3', 'precio' => 5.50, 'costo' => 3.00, 'stock' => 30, 'unidad' => 'Litro'],
                
                // Filtros
                ['cat' => $catFiltros->id, 'prov' => $proveedorGen->id, 'nombre' => 'Filtro de Aceite Toyota Corolla 2015-2020', 'precio' => 15.00, 'costo' => 8.00, 'stock' => 20, 'unidad' => 'Unidad'],
                ['cat' => $catFiltros->id, 'prov' => $proveedorGen->id, 'nombre' => 'Filtro de Aire Universal Mediano', 'precio' => 10.00, 'costo' => 5.00, 'stock' => 25, 'unidad' => 'Unidad'],
                
                // Llantas
                ['cat' => $catLlantas->id, 'prov' => $proveedorLlantas->id, 'nombre' => 'Llanta Michelin 205/55R16', 'precio' => 120.00, 'costo' => 90.00, 'stock' => 16, 'unidad' => 'Unidad'],
                ['cat' => $catLlantas->id, 'prov' => $proveedorLlantas->id, 'nombre' => 'Llanta Goodyear 185/65R15', 'precio' => 85.00, 'costo' => 60.00, 'stock' => 12, 'unidad' => 'Unidad'],
                
                // Frenos
                ['cat' => $catFrenosRep->id, 'prov' => $proveedorGen->id, 'nombre' => 'Pastillas de Freno Cerámicas (Juego Delantero)', 'precio' => 45.00, 'costo' => 28.00, 'stock' => 10, 'unidad' => 'Par'],
                ['cat' => $catFrenosRep->id, 'prov' => $proveedorGen->id, 'nombre' => 'Pastillas de Freno Metálicas Genericas', 'precio' => 25.00, 'costo' => 15.00, 'stock' => 15, 'unidad' => 'Par'],
                
                // Ignición
                ['cat' => $catIgnicion->id, 'prov' => $proveedorGen->id, 'nombre' => 'Bujía Iridium NGK', 'precio' => 18.00, 'costo' => 12.00, 'stock' => 40, 'unidad' => 'Unidad'],
                ['cat' => $catIgnicion->id, 'prov' => $proveedorGen->id, 'nombre' => 'Bujía Cobre Bosch', 'precio' => 5.00, 'costo' => 2.50, 'stock' => 60, 'unidad' => 'Unidad'],
            ];

            foreach ($repuestos as $r) {
                // Find or create unidad
                $unidad = \App\Models\UnidadMedida::firstOrCreate(
                    ['nombre' => $r['unidad']],
                    ['permite_fracciones' => in_array($r['unidad'], ['Litro', 'Galón', 'Kilogramo', 'Metro'])]
                );

                // Check if repuesto exists
                $rep = Repuesto::firstOrCreate([
                    'nombre' => $r['nombre']
                ], [
                    'categoria_repuesto_id' => $r['cat'],
                    'proveedor_id' => $r['prov'],
                    'codigoBarras' => strtoupper(substr(md5($r['nombre']), 0, 10)),
                    'costo' => $r['costo'],
                    'margen_ganancia' => (($r['precio'] - $r['costo']) / $r['costo']) * 100,
                    'unidad_medida_id' => $unidad->id
                ]);

                // Crear inventario y movimiento inicial si se acaba de crear
                if ($rep->wasRecentlyCreated) {
                    $inventario = Inventario::create([
                        'repuesto_id' => $rep->id,
                        'stockActual' => $r['stock'],
                        'stockMinimo' => 5
                    ]);

                    MovimientoInventario::create([
                        'inventario_id' => $inventario->id,
                        'tipoMovimiento' => 'ENTRADA',
                        'cantidad' => $r['stock'],
                        'fecha' => now(),
                        'motivo' => 'Inventario Inicial'
                    ]);
                }
            }
        });
    }
}
