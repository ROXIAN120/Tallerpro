<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\ReporteFinancieroService;
use App\Models\OrdenTrabajo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class ReporteController extends Controller
{
    protected ReporteFinancieroService $reporteService;

    public function __construct(ReporteFinancieroService $reporteService)
    {
        $this->reporteService = $reporteService;
    }

    public function index(Request $request)
    {
        $hoy = \Carbon\Carbon::now()->toDateString();
        $fechaInicio = $request->input('fecha_inicio', $hoy);
        $fechaFin = $request->input('fecha_fin', $hoy);
        
        $metricas = $this->reporteService->obtenerMetricas($fechaInicio, $fechaFin);
        
        return Inertia::render('Reportes/DashboardFinanciero', [
            'metricas' => $metricas,
            'filtros' => [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin
            ]
        ]);
    }

    public function exportarExcel(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        
        $metricas = $this->reporteService->obtenerMetricas($fechaInicio, $fechaFin);
        
        $fileName = 'reporte_financiero_' . date('Y_m_d') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Total Ingresos (Bs)', 'Costos Operativos (Bs)', 'Utilidad Neta (Bs)', 'Margen Global (%)', 'Ordenes Finalizadas'];
        
        $callback = function() use($metricas, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add BOM to fix UTF-8 in Excel
            fputs($file, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));
            
            // Sección 1: Resumen General
            fputcsv($file, ['=== RESUMEN GENERAL ==='], ';');
            fputcsv($file, $columns, ';');
            fputcsv($file, [
                $metricas['ingresos'],
                $metricas['costos'],
                $metricas['utilidad'],
                $metricas['margen'],
                $metricas['total_ordenes']
            ], ';');
            
            fputcsv($file, [], ';'); // Fila en blanco
            fputcsv($file, ['=== DETALLE DE ÓRDENES ==='], ';');
            
            // Sección 2: Detalle de cada orden
            $detalleColumnas = ['OT', 'Cliente', 'Vehículo', 'Ingreso (Bs)', 'Costo (Bs)', 'Utilidad (Bs)', 'Fecha Finalización'];
            fputcsv($file, $detalleColumnas, ';');

            foreach($metricas['ordenes'] as $orden) {
                $ingresoOrden = 0;
                $costoOrden = 0;
                
                foreach ($orden->detalles as $detalle) {
                    if ($detalle->servicio) {
                        $ingresoOrden += $detalle->subtotal;
                    }
                    foreach ($detalle->repuestos as $repuesto) {
                        $ingresoOrden += ($repuesto->pivot->cantidad * $repuesto->pivot->precioVenta);
                        $costoOrden += ($repuesto->pivot->cantidad * $repuesto->costo);
                    }
                }

                $utilidadOrden = $ingresoOrden - $costoOrden;

                fputcsv($file, [
                    'OT-' . $orden->id,
                    $orden->cliente->nombreCompleto ?? 'S/N',
                    $orden->vehiculo->placa ?? 'S/P',
                    $ingresoOrden,
                    $costoOrden,
                    $utilidadOrden,
                    \Carbon\Carbon::parse($orden->hora_fin)->format('d/m/Y H:i')
                ], ';');
            }
            
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function descargarFactura($id)
    {
        $orden = OrdenTrabajo::with(['vehiculo', 'cliente', 'sucursal', 'detalles.servicio', 'detalles.repuestos.unidadMedida'])->findOrFail($id);
        
        $pdf = Pdf::loadView('pdf.factura', compact('orden'));
        return $pdf->stream('factura_orden_' . $orden->id . '.pdf');
    }
}
