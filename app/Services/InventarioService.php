<?php

namespace App\Services;

use App\Models\Repuesto;

class InventarioService
{
    /**
     * Obtiene todos los repuestos procesados para la tabla de alta densidad.
     */
    public function obtenerPreciosAltaDensidad()
    {
        return Repuesto::with(['categoria', 'proveedor', 'inventario'])
            ->orderBy('nombre')
            ->get()
            ->map(function ($repuesto) {
                return [
                    'id' => $repuesto->id,
                    'codigo' => $repuesto->codigoBarras ?? 'S/C',
                    'nombre' => $repuesto->nombre,
                    'categoria' => $repuesto->categoria->nombre ?? 'N/A',
                    'stock' => $repuesto->inventario ? $repuesto->inventario->stockActual : 0,
                    'costo' => (float) $repuesto->costo,
                    'margen' => (float) $repuesto->margen_ganancia,
                    'precio_venta' => $repuesto->precio_venta, // Usando el Accessor
                ];
            });
    }

    /**
     * Actualiza el costo o margen de ganancia de un repuesto.
     */
    public function actualizarPrecio(int $id, array $datos)
    {
        $repuesto = Repuesto::findOrFail($id);
        
        if (isset($datos['costo'])) {
            $repuesto->costo = $datos['costo'];
        }
        if (isset($datos['margen_ganancia'])) {
            $repuesto->margen_ganancia = $datos['margen_ganancia'];
        }
        
        $repuesto->save();
        return $repuesto;
    }



    /**
     * Obtiene los movimientos de inventario (Kardex).
     */
    public function obtenerKardex()
    {
        return \App\Models\MovimientoInventario::with(['inventario.repuesto', 'user'])
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($movimiento) {
                return [
                    'id' => $movimiento->id,
                    'fecha' => $movimiento->fecha,
                    'repuesto' => $movimiento->inventario->repuesto->nombre ?? 'N/A',
                    'codigo' => $movimiento->inventario->repuesto->codigoBarras ?? 'S/C',
                    'tipo' => $movimiento->tipoMovimiento,
                    'cantidad' => $movimiento->cantidad,
                    'motivo' => $movimiento->motivo,
                    'usuario' => $movimiento->user->name ?? 'Sistema'
                ];
            });
    }

    /**
     * Registra un nuevo movimiento usando transacción.
     */
    public function registrarMovimiento(int $repuesto_id, int $user_id, string $tipo, float $cantidad, string $motivo)
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($repuesto_id, $user_id, $tipo, $cantidad, $motivo) {
            $repuesto = Repuesto::with('inventario')->findOrFail($repuesto_id);
            
            // Si no tiene inventario, lo inicializamos
            if (!$repuesto->inventario) {
                $repuesto->inventario()->create(['stockActual' => 0, 'stockMinimo' => 5]);
                $repuesto->load('inventario');
            }

            // Registrar el movimiento en el historial del Kardex
            $movimiento = new \App\Models\MovimientoInventario([
                'inventario_id' => $repuesto->inventario->id,
                'user_id' => $user_id,
                'tipoMovimiento' => $tipo,
                'cantidad' => $cantidad,
                'motivo' => $motivo,
                'fecha' => now()->toDateString(),
            ]);
            $movimiento->save();

            // Actualizar stock físicamente de manera segura
            if ($tipo === 'ENTRADA') {
                $repuesto->inventario->stockActual += $cantidad;
            } else {
                $repuesto->inventario->stockActual -= $cantidad;
            }
            $repuesto->inventario->save();

            return $movimiento;
        });
    }
}
