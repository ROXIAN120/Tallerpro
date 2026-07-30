<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\InventarioService;

class InventarioController extends Controller
{
    protected InventarioService $inventarioService;

    public function __construct(InventarioService $inventarioService)
    {
        $this->inventarioService = $inventarioService;
    }

    public function preciosIndex()
    {
        $repuestos = $this->inventarioService->obtenerPreciosAltaDensidad();
        
        return Inertia::render('Inventario/Precios', [
            'repuestos' => $repuestos
        ]);
    }

    public function actualizarPrecio(Request $request)
    {
        $datos = $request->validate([
            'id' => 'required|exists:repuestos,id',
            'costo' => 'nullable|numeric|min:0',
            'margen_ganancia' => 'nullable|numeric|min:0',
        ]);

        $this->inventarioService->actualizarPrecio($datos['id'], $datos);

        return back()->with('success', 'Precio actualizado correctamente.');
    }



    public function kardexIndex()
    {
        $movimientos = $this->inventarioService->obtenerKardex();
        $repuestos = \App\Models\Repuesto::with('unidadMedida')->orderBy('nombre')->get(['id', 'nombre', 'codigoBarras', 'unidad_medida_id']);
        
        return Inertia::render('Inventario/Kardex', [
            'movimientos' => $movimientos,
            'repuestos' => $repuestos
        ]);
    }

    public function registrarMovimiento(Request $request)
    {
        $datos = $request->validate([
            'repuesto_id' => 'required|exists:repuestos,id',
            'tipo' => 'required|in:ENTRADA,SALIDA',
            'cantidad' => 'required|numeric|min:0.001',
            'motivo' => 'required|string|max:255',
        ]);

        $this->inventarioService->registrarMovimiento(
            $datos['repuesto_id'],
            $request->user()->id ?? 1,
            $datos['tipo'],
            $datos['cantidad'],
            $datos['motivo']
        );

        return back()->with('success', 'Movimiento registrado correctamente en el Kardex.');
    }
}
