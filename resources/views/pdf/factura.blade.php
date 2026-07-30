<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura OT-{{ $orden->id }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #2c3e50; font-size: 24px; }
        .header p { margin: 5px 0; font-size: 12px; color: #7f8c8d; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { width: 50%; vertical-align: top; }
        .section-title { background-color: #2c3e50; color: #fff; padding: 5px 10px; font-weight: bold; margin-bottom: 10px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table.items th, table.items td { border: 1px solid #bdc3c7; padding: 8px; text-align: left; }
        table.items th { background-color: #ecf0f1; }
        table.items .text-right { text-align: right; }
        table.items .text-center { text-align: center; }
        .totals-table { width: 50%; float: right; border-collapse: collapse; }
        .totals-table td { padding: 5px; border-bottom: 1px solid #ecf0f1; }
        .totals-table .total-row { font-weight: bold; font-size: 16px; border-bottom: none; }
        .footer { position: absolute; bottom: 30px; width: 100%; text-align: center; font-size: 12px; color: #7f8c8d; border-top: 1px solid #bdc3c7; padding-top: 10px; }
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>
    <div class="header">
        <h1>TALLER AUTOMOTRIZ PRO</h1>
        <p>Factura de Servicio y Repuestos</p>
        <p>Fecha de Emisión: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td>
                <div class="section-title">DATOS DEL CLIENTE</div>
                <p><strong>Nombre:</strong> {{ $orden->cliente->nombre ?? 'N/A' }}</p>
                <p><strong>Teléfono:</strong> {{ $orden->cliente->telefono ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $orden->cliente->email ?? 'N/A' }}</p>
            </td>
            <td>
                <div class="section-title">DATOS DEL VEHÍCULO</div>
                <p><strong>Placa:</strong> {{ $orden->vehiculo->placa ?? 'N/A' }}</p>
                <p><strong>Marca:</strong> {{ $orden->vehiculo->marca ?? 'N/A' }}</p>
                <p><strong>Orden N°:</strong> OT-{{ str_pad($orden->id, 5, '0', STR_PAD_LEFT) }}</p>
            </td>
        </tr>
    </table>

    <div class="section-title">DETALLE DE SERVICIO Y REPUESTOS</div>
    <table class="items">
        <thead>
            <tr>
                <th>Descripción</th>
                <th class="text-center">Cant.</th>
                <th class="text-right">Precio Unit. (Bs)</th>
                <th class="text-right">Subtotal (Bs)</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $detalleServicio = $orden->detalles->first();
                $totalServicios = $detalleServicio ? $detalleServicio->subtotal : 0;
                $totalRepuestos = 0;
            @endphp
            
            <!-- Servicio -->
            <tr>
                <td>{{ $detalleServicio->servicio->nombre ?? 'Mantenimiento General' }}<br><small style="color: #7f8c8d;">Mano de obra y diagnóstico</small></td>
                <td class="text-center">1</td>
                <td class="text-right">{{ number_format($totalServicios, 2) }}</td>
                <td class="text-right">{{ number_format($totalServicios, 2) }}</td>
            </tr>

            <!-- Repuestos -->
            @if($detalleServicio && $detalleServicio->repuestos)
                @foreach($detalleServicio->repuestos as $rep)
                    @php 
                        $sub = $rep->pivot->cantidad * $rep->pivot->precioVenta;
                        $totalRepuestos += $sub;
                    @endphp
                    <tr>
                        <td>{{ $rep->nombre }}</td>
                        <td class="text-center">{{ $rep->pivot->cantidad }} <small style="color: #7f8c8d;">{{ $rep->unidadMedida->nombre ?? 'Ud.' }}</small></td>
                        <td class="text-right">{{ number_format($rep->pivot->precioVenta, 2) }}</td>
                        <td class="text-right">{{ number_format($sub, 2) }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <div class="clearfix">
        <table class="totals-table">
            <tr>
                <td>Subtotal Servicios:</td>
                <td class="text-right">Bs. {{ number_format($totalServicios, 2) }}</td>
            </tr>
            <tr>
                <td>Subtotal Repuestos:</td>
                <td class="text-right">Bs. {{ number_format($totalRepuestos, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>TOTAL A PAGAR:</td>
                <td class="text-right">Bs. {{ number_format($totalServicios + $totalRepuestos, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Gracias por confiar en TallerPro. Su vehículo está en las mejores manos.</p>
        <p>Este documento es una representación impresa de la factura electrónica.</p>
    </div>
</body>
</html>
