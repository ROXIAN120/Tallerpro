<?php

namespace App\Services;

use App\Models\OrdenTrabajo;
use Illuminate\Support\Carbon;

class ReporteFinancieroService
{
    /**
     * Calcula las métricas consolidadas del mes actual.
     */
    public function obtenerMetricas($fechaInicio = null, $fechaFin = null)
    {
        $inicio = $fechaInicio ? Carbon::parse($fechaInicio)->startOfDay() : Carbon::now()->startOfMonth();
        $fin = $fechaFin ? Carbon::parse($fechaFin)->endOfDay() : Carbon::now()->endOfMonth();
        
        $ordenesFinalizadas = OrdenTrabajo::with(['detalles.servicio', 'detalles.repuestos'])
            ->whereIn('estado', ['FINALIZADO', 'ENTREGADO'])
            ->whereBetween('hora_fin', [$inicio, $fin])
            ->get();

        $ingresos = 0;
        $costos = 0;

        foreach ($ordenesFinalizadas as $orden) {
            foreach ($orden->detalles as $detalle) {
                if ($detalle->servicio) {
                    $ingresos += $detalle->subtotal;
                }
                
                foreach ($detalle->repuestos as $repuesto) {
                    $ingresos += ($repuesto->pivot->cantidad * $repuesto->pivot->precioVenta);
                    $costos += ($repuesto->pivot->cantidad * $repuesto->costo);
                }
            }
        }

        $utilidad = $ingresos - $costos;

        return [
            'ingresos' => $ingresos,
            'costos' => $costos,
            'utilidad' => $utilidad,
            'margen' => $ingresos > 0 ? round(($utilidad / $ingresos) * 100, 2) : 0,
            'total_ordenes' => $ordenesFinalizadas->count(),
            'ordenes' => $ordenesFinalizadas
        ];
    }
}
