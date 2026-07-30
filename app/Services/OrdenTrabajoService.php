<?php

namespace App\Services;

use App\Models\OrdenTrabajo;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\DetalleOrdenTrabajo;
use App\Models\Sucursal;
use App\Models\Servicio;
use App\Models\Repuesto;
use App\Models\MovimientoInventario;
use Illuminate\Support\Facades\DB;

class OrdenTrabajoService
{
    /**
     * Obtiene las órdenes con relaciones y las mapea en formato Kanban.
     */
    public function obtenerOrdenesKanban()
    {
        $ordenes = OrdenTrabajo::with(['vehiculo', 'cliente', 'detalles.servicio'])
            ->where(function($query) {
                $query->whereIn('estado', ['PENDIENTE', 'EN REPARACION', 'DIAGNOSTICO'])
                      ->orWhere(function($q) {
                          $q->whereIn('estado', ['FINALIZADO', 'ENTREGADO'])
                            ->whereDate('hora_fin', '>=', now()->subDays(2)->toDateString());
                      });
            })
            ->orderBy('id', 'desc')
            ->get();

        $mapped = $ordenes->map(function ($orden) {
            
            $servicioPrincipal = 'Mantenimiento General';
            if ($orden->detalles && $orden->detalles->count() > 0 && $orden->detalles->first()->servicio) {
                $servicioPrincipal = $orden->detalles->first()->servicio->nombre;
            }

            $estadoVisual = 'PENDIENTE';
            if ($orden->estado === 'EN REPARACION' || $orden->estado === 'DIAGNOSTICO') {
                $estadoVisual = 'EN PROCESO';
            } elseif ($orden->estado === 'FINALIZADO' || $orden->estado === 'ENTREGADO') {
                $estadoVisual = 'FINALIZADO';
            }

            return [
                'id' => $orden->id,
                'vehiculo' => $orden->vehiculo->placa ?? 'S/P',
                'cliente' => $orden->cliente->nombreCompleto ?? 'Sin Asignar',
                'servicio' => $servicioPrincipal,
                'estado' => $estadoVisual,
                'mecanico' => 'Pendiente de Asignación',
                'hora_inicio' => $orden->hora_inicio,
                'hora_fin' => $orden->hora_fin,
            ];
        });

        return [
            'pendientes' => $mapped->where('estado', 'PENDIENTE')->values(),
            'en_proceso' => $mapped->where('estado', 'EN PROCESO')->values(),
            'finalizados' => $mapped->where('estado', 'FINALIZADO')->values(),
        ];
    }

    /**
     * Mueve la orden en el Kanban y guarda el tiempo.
     */
    public function cambiarEstado(int $id, string $nuevoEstado)
    {
        $orden = OrdenTrabajo::findOrFail($id);
        
        if ($nuevoEstado === 'EN PROCESO') {
            $orden->estado = 'EN REPARACION';
            if (!$orden->hora_inicio) {
                $orden->hora_inicio = now();
            }
        } elseif ($nuevoEstado === 'FINALIZADO') {
            $orden->estado = 'FINALIZADO';
            if (!$orden->hora_fin) {
                $orden->hora_fin = now();
            }
        } elseif ($nuevoEstado === 'PENDIENTE') {
            $orden->estado = 'PENDIENTE';
        }

        $orden->save();
        return $orden;
    }

    /**
     * Crea una nueva Orden de Trabajo, Cliente y Vehículo si es necesario.
     */
    public function crearOrden(array $data)
    {
        // 1. Obtener o crear Cliente
        $cliente = Cliente::firstOrCreate(
            ['telefono' => $data['cliente_telefono']],
            [
                'nombreCompleto' => $data['cliente_nombre'],
                'ci' => 'S/CI-' . strtoupper(substr(uniqid(), -6)),
            ]
        );

        // 2. Obtener o crear Marca y Modelo
        $marcaName = strtoupper($data['vehiculo_marca']);
        $marca = \App\Models\MarcaVehiculo::firstOrCreate(['nombre' => $marcaName]);
        
        $modeloName = !empty($data['vehiculo_modelo']) ? strtoupper($data['vehiculo_modelo']) : 'GENERICO';
        $modelo = \App\Models\ModeloVehiculo::firstOrCreate(
            ['marca_vehiculo_id' => $marca->id, 'nombre' => $modeloName]
        );

        // 3. Obtener o crear Vehículo
        $vehiculo = Vehiculo::firstOrCreate(
            ['placa' => strtoupper($data['vehiculo_placa'])],
            [
                'cliente_id' => $cliente->id,
                'modelo_vehiculo_id' => $modelo->id,
                'color' => 'S/C',
                'anio' => date('Y'),
            ]
        );

        // 3. Crear Orden de Trabajo
        // Por defecto usamos la primera sucursal
        $sucursal = Sucursal::first();
        if (!$sucursal) {
            $sucursal = Sucursal::create(['nombre' => 'Matriz Central', 'direccion' => 'Av. Principal', 'telefono' => '123456', 'email' => 'info@taller.com']);
        }

        $orden = OrdenTrabajo::create([
            'vehiculo_id' => $vehiculo->id,
            'cliente_id' => $cliente->id,
            'sucursal_id' => $sucursal->id,
            'fechaIngreso' => now(),
            'estado' => 'PENDIENTE',
            'diagnostico' => $data['diagnostico'] ?? null,
        ]);

        // 4. Crear Detalle de Orden (asignando un servicio por defecto 'Mantenimiento General')
        $categoriaServicio = \App\Models\CategoriaServicio::firstOrCreate(
            ['nombre' => 'General']
        );

        $servicio = Servicio::firstOrCreate(
            ['nombre' => 'Mantenimiento General'],
            ['descripcion' => 'Revisión y mantenimiento general del vehículo', 'precioBase' => 150.00, 'categoria_servicio_id' => $categoriaServicio->id, 'tiempoEstimadoHoras' => 1]
        );

        DetalleOrdenTrabajo::create([
            'orden_trabajo_id' => $orden->id,
            'servicio_id' => $servicio->id,
            'mecanico_id' => null, // Queda pendiente asignar mecánico
            'subtotal' => $servicio->precioBase,
        ]);

        return $orden;
    }

    /**
     * Agrega un repuesto a la orden, descuenta stock y registra movimiento.
     */
    public function agregarRepuesto(int $ordenId, int $repuestoId, float $cantidad, float $precioFinal = null)
    {
        return DB::transaction(function () use ($ordenId, $repuestoId, $cantidad, $precioFinal) {
            $orden = OrdenTrabajo::findOrFail($ordenId);
            $repuesto = Repuesto::with('inventario')->findOrFail($repuestoId);
            $inventario = $repuesto->inventario;

            if (!$inventario || $inventario->stockActual < $cantidad) {
                throw new \Exception("Stock insuficiente para el repuesto: {$repuesto->nombre}");
            }

            // Descontar Stock
            $inventario->stockActual -= $cantidad;
            $inventario->save();

            // Registrar Movimiento de Kardex
            MovimientoInventario::create([
                'inventario_id' => $inventario->id,
                'tipoMovimiento' => 'SALIDA',
                'cantidad' => $cantidad,
                'fecha' => now(),
            ]);

            // Obtener el primer detalle de la orden para asociarlo
            $detalle = $orden->detalles()->first();
            if (!$detalle) {
                // Si no hay detalle, creamos uno genérico
                $servicio = Servicio::firstOrCreate(['nombre' => 'Mantenimiento General'], ['precioBase' => 0, 'categoria_id' => 1]);
                $detalle = DetalleOrdenTrabajo::create([
                    'orden_trabajo_id' => $orden->id,
                    'servicio_id' => $servicio->id,
                    'subtotal' => 0,
                ]);
            }

            // Asociar el repuesto al detalle (pivot)
            // Calculamos precio venta si tiene margen o usamos el precioFinal proveído
            $precioVenta = $precioFinal;
            if ($precioVenta === null) {
                $precioVenta = $repuesto->costo + ($repuesto->costo * ($repuesto->margen_ganancia / 100));
            }
            
            $detalle->repuestos()->attach($repuesto->id, [
                'cantidad' => $cantidad,
                'precioVenta' => $precioVenta
            ]);

            return true;
        });
    }

    /**
     * Agrega un servicio a la orden.
     */
    public function agregarServicio(int $ordenId, int $servicioId, $precioAjustado = null)
    {
        $orden = OrdenTrabajo::findOrFail($ordenId);
        $servicio = Servicio::findOrFail($servicioId);

        $precioFinal = $precioAjustado !== null ? $precioAjustado : $servicio->precioBase;

        DetalleOrdenTrabajo::create([
            'orden_trabajo_id' => $orden->id,
            'servicio_id' => $servicio->id,
            'mecanico_id' => null, // Asignamos al usuario actual o queda null
            'subtotal' => $precioFinal,
        ]);

        return true;
    }

    public function eliminarRepuesto(int $ordenId, int $detalleId, int $repuestoId)
    {
        return DB::transaction(function () use ($detalleId, $repuestoId) {
            $detalle = DetalleOrdenTrabajo::findOrFail($detalleId);
            $repuesto = Repuesto::with('inventario')->findOrFail($repuestoId);
            
            $pivot = $detalle->repuestos()->where('repuesto_id', $repuestoId)->first();
            if ($pivot) {
                $cantidadDevuelta = $pivot->pivot->cantidad;
                
                // Retornar stock
                $inventario = $repuesto->inventario;
                if ($inventario) {
                    $inventario->stockActual += $cantidadDevuelta;
                    $inventario->save();
                    
                    MovimientoInventario::create([
                        'inventario_id' => $inventario->id,
                        'tipoMovimiento' => 'ENTRADA',
                        'cantidad' => $cantidadDevuelta,
                        'fecha' => now(),
                    ]);
                }
                
                // Eliminar pivot
                $detalle->repuestos()->detach($repuestoId);
            }
            return true;
        });
    }

    public function eliminarServicio(int $detalleId)
    {
        $detalle = DetalleOrdenTrabajo::findOrFail($detalleId);
        
        if ($detalle->repuestos()->count() > 0) {
            throw new \Exception("No puede eliminar este servicio porque tiene repuestos asociados.");
        }
        
        $detalle->delete();
        return true;
    }

    /**
     * Cancela una orden y devuelve los repuestos al inventario
     */
    public function cancelarOrden(int $id)
    {
        return DB::transaction(function () use ($id) {
            $orden = OrdenTrabajo::with('detalles.repuestos.inventario')->findOrFail($id);

            if (!in_array($orden->estado, ['PENDIENTE', 'DIAGNOSTICO', 'EN REPARACION'])) {
                throw new \Exception("Solo se pueden cancelar órdenes pendientes o en proceso.");
            }

            // Devolver inventario si se asignaron repuestos
            foreach ($orden->detalles as $detalle) {
                foreach ($detalle->repuestos as $repuesto) {
                    $cantidad = $repuesto->pivot->cantidad;
                    $inventario = $repuesto->inventario;
                    
                    if ($inventario) {
                        $inventario->stockActual += $cantidad;
                        $inventario->save();

                        MovimientoInventario::create([
                            'inventario_id' => $inventario->id,
                            'tipoMovimiento' => 'ENTRADA',
                            'cantidad' => $cantidad,
                            'fecha' => now(),
                        ]);
                    }
                }
            }

            $orden->estado = 'CANCELADO';
            $orden->save();

            return $orden;
        });
    }
}
