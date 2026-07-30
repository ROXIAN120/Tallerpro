<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\OrdenTrabajo;
use App\Models\Inventario;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();

        // 1. Órdenes Activas (Pendientes o En Proceso)
        $ordenesActivas = OrdenTrabajo::whereIn('estado', ['PENDIENTE', 'DIAGNOSTICO', 'EN REPARACION'])->count();

        // 2. Vehículos Entregados en el mes
        $vehiculosEntregados = OrdenTrabajo::whereIn('estado', ['FINALIZADO', 'ENTREGADO'])
            ->whereBetween('hora_fin', [$inicioMes, $finMes])
            ->count();

        // 3. Repuestos en estado crítico (Stock < 5)
        $alertasStock = Inventario::where('stockActual', '<', 5)->count();

        // 4. Últimas 5 Órdenes de Trabajo (Cargando relaciones eficientemente)
        $ultimasOrdenes = OrdenTrabajo::with(['cliente', 'vehiculo'])
            ->orderBy('id', 'desc')
            ->take(5)
            ->get()
            ->map(function ($orden) {
                return [
                    'id' => $orden->id,
                    'cliente' => $orden->cliente->nombreCompleto ?? 'Sin cliente',
                    'telefono' => $orden->cliente->telefono ?? 'Sin teléfono',
                    'vehiculo' => $orden->vehiculo->placa ?? 'N/A',
                    'estado' => $orden->estado,
                    'fecha' => $orden->created_at ? $orden->created_at->format('d/m/Y H:i') : date('d/m/Y H:i'),
                ];
            });

        return Inertia::render('Dashboard', [
            'metricas' => [
                'ordenesActivas' => $ordenesActivas,
                'vehiculosEntregados' => $vehiculosEntregados,
                'alertasStock' => $alertasStock,
            ],
            'ultimasOrdenes' => $ultimasOrdenes
        ]);
    }
}
