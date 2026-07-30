<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\OrdenTrabajo;
use Illuminate\Support\Carbon;

class ReporteFinancieroExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return OrdenTrabajo::with(['cliente', 'vehiculo'])
            ->whereIn('estado', ['FINALIZADO', 'ENTREGADO'])
            ->whereBetween('hora_fin', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Orden',
            'Fecha Finalización',
            'Cliente',
            'Vehículo',
            'Ingreso Estimado (Bs.)',
            'Costo Operativo (Bs.)',
            'Utilidad Neta (Bs.)'
        ];
    }

    public function map($orden): array
    {
        $ingreso = 1500;
        $costo = 400;
        
        return [
            $orden->id,
            $orden->hora_fin,
            $orden->cliente->nombre ?? 'Sin Asignar',
            $orden->vehiculo->placa ?? 'N/A',
            $ingreso,
            $costo,
            $ingreso - $costo
        ];
    }
}
