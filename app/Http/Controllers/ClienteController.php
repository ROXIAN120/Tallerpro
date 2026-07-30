<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Vehiculo;
use App\Models\Cliente;
use App\Models\OrdenTrabajo;

class ClienteController extends Controller
{
    public function index()
    {
        $vehiculos = Vehiculo::with('cliente')->get()->map(function ($vehiculo) {
            return [
                'id' => $vehiculo->id,
                'placa' => $vehiculo->placa,
                'color' => $vehiculo->color ?? 'N/A',
                'anio' => $vehiculo->anio ?? 'N/A',
                'cliente' => $vehiculo->cliente->nombreCompleto ?? 'Sin cliente',
                'telefono' => $vehiculo->cliente->telefono ?? 'N/A',
                'email' => $vehiculo->cliente->email ?? 'N/A',
            ];
        });

        return Inertia::render('Cliente/DirectorioClientes', [
            'vehiculos' => $vehiculos
        ]);
    }

    public function buscarPorPlaca($placa)
    {
        $vehiculo = Vehiculo::with(['cliente', 'modelo.marca'])->where('placa', $placa)->first();
        
        if (!$vehiculo) {
            return response()->json(['encontrado' => false], 404);
        }

        return response()->json([
            'encontrado' => true,
            'cliente_nombre' => $vehiculo->cliente->nombreCompleto ?? '',
            'cliente_telefono' => $vehiculo->cliente->telefono ?? '',
            'cliente_email' => $vehiculo->cliente->email ?? '',
            'vehiculo_marca' => $vehiculo->modelo->marca->nombre ?? '',
            'vehiculo_modelo' => $vehiculo->modelo->nombre ?? ''
        ]);
    }

    public function historial($placa)
    {
        // Buscar el vehículo por placa
        $vehiculo = Vehiculo::where('placa', $placa)->firstOrFail();
        
        // Traer al cliente
        $cliente = Cliente::find($vehiculo->cliente_id);
        
        // Traer todas las órdenes de trabajo de este vehículo
        $ordenes = OrdenTrabajo::where('vehiculo_id', $vehiculo->id)
            ->with(['detalles.servicio', 'detalles.repuestos'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($orden) {
                $total = 0;
                foreach($orden->detalles as $d) {
                    $total += $d->subtotal;
                    foreach($d->repuestos as $r) {
                        $total += $r->pivot->precioVenta * $r->pivot->cantidad;
                    }
                }
                
                return [
                    'id' => $orden->id,
                    'fechaIngreso' => $orden->created_at ? $orden->created_at->format('d/m/Y H:i') : 'N/A',
                    'estado' => $orden->estado,
                    'diagnostico' => $orden->diagnostico,
                    'total' => $total,
                    'detalles' => $orden->detalles->map(function ($detalle) {
                        return [
                            'servicio' => $detalle->servicio->nombre ?? 'Servicio no especificado',
                            'repuestos' => $detalle->repuestos->map(function ($r) {
                                return $r->nombre . ' (x' . $r->pivot->cantidad . ')';
                            })
                        ];
                    })
                ];
            });

        return Inertia::render('Cliente/HistorialVehiculo', [
            'cliente' => [
                'id' => $cliente->id ?? null,
                'nombreCompleto' => $cliente->nombreCompleto ?? 'Sin nombre',
                'telefono' => $cliente->telefono ?? 'N/A',
                'email' => $cliente->email ?? 'N/A'
            ],
            'vehiculo' => [
                'placa' => $vehiculo->placa,
                'color' => $vehiculo->color ?? 'N/A',
                'anio' => $vehiculo->anio ?? 'N/A'
            ],
            'historial' => $ordenes
        ]);
    }

    public function destroy($placa)
    {
        $vehiculo = Vehiculo::where('placa', $placa)->firstOrFail();
        $cliente = $vehiculo->cliente;

        // Check if there are any orders for this client
        $hasOrders = \App\Models\OrdenTrabajo::where('cliente_id', $cliente->id)->exists();

        if ($hasOrders) {
            return back()->withErrors(['error' => 'No se puede eliminar el cliente porque tiene órdenes de trabajo registradas.']);
        }

        // Delete the client (will cascade to vehicle)
        $cliente->delete();

        return redirect()->route('clientes.directorio')->with('success', 'Cliente y vehículo eliminados correctamente.');
    }
}
