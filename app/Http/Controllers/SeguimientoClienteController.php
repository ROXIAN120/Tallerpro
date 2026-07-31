<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\OrdenTrabajo;
use App\Models\Vehiculo;

class SeguimientoClienteController extends Controller
{
    public function index()
    {
        return Inertia::render('Cliente/Seguimiento', [
            'ordenData' => null,
            'error' => null
        ]);
    }

    public function buscarPlaca(Request $request)
    {
        $request->validate([
            'placa' => 'required|string|max:20'
        ]);

        $placa = strtoupper(trim($request->placa));

        // 1. Buscar vehículo por placa
        $vehiculo = Vehiculo::where('placa', $placa)->first();

        if (!$vehiculo) {
            return Inertia::render('Cliente/Seguimiento', [
                'ordenData' => null,
                'error' => 'No se encontró ningún vehículo con esa placa registrada.'
            ]);
        }

        // 2. Buscar la orden de trabajo activa más reciente
        $orden = OrdenTrabajo::with(['cliente', 'detalles.servicio'])
            ->where('vehiculo_id', $vehiculo->id)
            ->orderBy('id', 'desc')
            ->first();

        if (!$orden) {
            return Inertia::render('Cliente/Seguimiento', [
                'ordenData' => null,
                'error' => 'El vehículo no tiene ninguna orden de trabajo en el historial.'
            ]);
        }

        $servicioPrincipal = 'Mantenimiento General';
        $tiempoTotal = null;
        if ($orden->detalles && $orden->detalles->count() > 0) {
            $primerServicio = $orden->detalles->first()->servicio;
            if ($primerServicio) {
                $servicioPrincipal = $primerServicio->nombre;
                $tiempoTotal = $primerServicio->tiempoEstimadoHoras;
            }
        }

        // 3. Mapear estado al Timeline del frontend
        $estadoVisual = 'PENDIENTE';
        $progreso = 0;
        
        if ($orden->estado === 'EN REPARACION' || $orden->estado === 'DIAGNOSTICO') {
            $estadoVisual = 'EN PROCESO';
            $progreso = 50;
        } elseif ($orden->estado === 'FINALIZADO' || $orden->estado === 'ENTREGADO') {
            $estadoVisual = 'FINALIZADO';
            $progreso = 100;
        }

        $datosFormateados = [
            'id' => $orden->id,
            'placa' => $vehiculo->placa,
            'cliente' => $orden->cliente->nombre ?? 'Estimado Cliente',
            'servicio' => $servicioPrincipal,
            'tiempo_estimado' => $tiempoTotal,
            'estado' => $estadoVisual,
            'progreso' => $progreso,
            'fecha' => $orden->created_at ? $orden->created_at->format('d/m/Y') : date('d/m/Y')
        ];

        // Retornamos usando Inertia (Reactiva)
        return Inertia::render('Cliente/Seguimiento', [
            'ordenData' => $datosFormateados,
            'error' => null
        ]);
    }
}
