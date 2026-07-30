<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\OrdenTrabajoService;
use App\Models\OrdenTrabajo;
use App\Models\Repuesto;

class OrdenTrabajoController extends Controller
{
    protected OrdenTrabajoService $ordenService;

    public function __construct(OrdenTrabajoService $ordenService)
    {
        $this->ordenService = $ordenService;
    }

    public function kanbanIndex()
    {
        $kanban = $this->ordenService->obtenerOrdenesKanban();
        
        return Inertia::render('Taller/Kanban', [
            'pendientes' => $kanban['pendientes'],
            'enProceso' => $kanban['en_proceso'],
            'finalizados' => $kanban['finalizados'],
        ]);
    }

    public function cambiarEstado(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:ordenes_trabajo,id',
            'estado' => 'required|in:PENDIENTE,EN PROCESO,FINALIZADO'
        ]);

        $this->ordenService->cambiarEstado($request->id, $request->estado);

        return back()->with('success', 'Estado de la orden actualizado.');
    }

    public function cancelar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:ordenes_trabajo,id'
        ]);

        try {
            $this->ordenService->cancelarOrden($request->id);
            return back()->with('success', 'Orden cancelada y repuestos devueltos al inventario.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function crearIndex()
    {
        return Inertia::render('Taller/NuevaOrden');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_nombre' => 'required|string|max:100',
            'cliente_telefono' => 'required|string|max:20',
            'cliente_email' => 'nullable|email|max:100',
            'vehiculo_placa' => 'required|string|max:20',
            'vehiculo_marca' => 'required|string|max:50',
            'vehiculo_modelo' => 'nullable|string|max:50',
            'diagnostico' => 'nullable|string',
        ]);

        $this->ordenService->crearOrden($data);

        return redirect()->route('taller.kanban')->with('success', 'Orden de Trabajo creada con éxito.');
    }

    public function show($id)
    {
        $orden = OrdenTrabajo::with(['vehiculo', 'cliente', 'sucursal', 'detalles.servicio', 'detalles.repuestos'])->findOrFail($id);
        $repuestos = Repuesto::with(['inventario', 'unidadMedida'])->get()->map(function ($repuesto) {
            $repuesto->stockActual = $repuesto->inventario->stockActual ?? 0;
            return $repuesto;
        });
        $servicios = \App\Models\Servicio::with('categoria')->get();

        return Inertia::render('Taller/OrdenDetalle', [
            'orden' => $orden,
            'repuestosStock' => $repuestos,
            'serviciosCatalogo' => $servicios
        ]);
    }

    public function agregarRepuesto(Request $request, $id)
    {
        $request->validate([
            'repuesto_id' => 'required|exists:repuestos,id',
            'cantidad' => 'required|numeric|min:0.001',
            'precio_final' => 'nullable|numeric|min:0'
        ]);

        $this->ordenService->agregarRepuesto($id, $request->repuesto_id, $request->cantidad, $request->precio_final);

        return back()->with('success', 'Repuesto agregado y stock descontado.');
    }

    public function agregarServicio(Request $request, $id)
    {
        $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'precio_ajustado' => 'nullable|numeric|min:0'
        ]);

        $this->ordenService->agregarServicio($id, $request->servicio_id, $request->precio_ajustado);

        return back()->with('success', 'Servicio agregado a la orden.');
    }

    public function eliminarRepuesto($id, $detalle_id, $repuesto_id)
    {
        try {
            $this->ordenService->eliminarRepuesto($id, $detalle_id, $repuesto_id);
            return back()->with('success', 'Repuesto eliminado y stock devuelto.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function eliminarServicio($id, $detalle_id)
    {
        try {
            $this->ordenService->eliminarServicio($detalle_id);
            return back()->with('success', 'Servicio eliminado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
