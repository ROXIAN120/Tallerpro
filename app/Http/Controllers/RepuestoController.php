<?php

namespace App\Http\Controllers;

use App\Models\Repuesto;
use App\Models\CategoriaRepuesto;
use App\Models\UnidadMedida;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class RepuestoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $repuestos = Repuesto::with(['categoria', 'proveedor', 'inventario', 'unidadMedida'])
            ->orderBy('nombre')
            ->get()
            ->map(function ($repuesto) {
                return [
                    'id' => $repuesto->id,
                    'codigoBarras' => $repuesto->codigoBarras,
                    'nombre' => $repuesto->nombre,
                    'categoria_id' => $repuesto->categoria_repuesto_id,
                    'categoria' => $repuesto->categoria->nombre ?? 'N/A',
                    'proveedor_id' => $repuesto->proveedor_id,
                    'proveedor' => $repuesto->proveedor->nombre ?? 'N/A',
                    'costo' => (float) $repuesto->costo,
                    'margen_ganancia' => (float) $repuesto->margen_ganancia,
                    'precio_venta' => $repuesto->precio_venta,
                    'stockActual' => $repuesto->inventario->stockActual ?? 0,
                    'stockMinimo' => $repuesto->inventario->stockMinimo ?? 5,
                    'inventario_id' => $repuesto->inventario->id ?? null,
                    'unidad_medida_id' => $repuesto->unidad_medida_id,
                    'unidad_medida' => $repuesto->unidadMedida->nombre ?? 'Unidad',
                ];
            });

        $categorias = CategoriaRepuesto::all();
        $proveedores = Proveedor::all();
        $unidades = UnidadMedida::all();

        return Inertia::render('Inventario/Index', [
            'repuestos' => $repuestos,
            'categorias' => $categorias,
            'proveedores' => $proveedores,
            'unidades' => $unidades
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigoBarras' => 'required|string|max:100|unique:repuestos,codigoBarras',
            'categoria_id' => 'required|exists:categorias_repuestos,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'costo' => 'required|numeric|min:0',
            'margen_ganancia' => 'required|numeric|min:0',
            'stock_inicial' => 'required|numeric|min:0',
            'unidad_medida_id' => 'required|exists:unidades_medida,id',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $repuesto = Repuesto::create([
                'nombre' => $validated['nombre'],
                'codigoBarras' => $validated['codigoBarras'],
                'categoria_repuesto_id' => $validated['categoria_id'],
                'proveedor_id' => $validated['proveedor_id'],
                'costo' => $validated['costo'],
                'margen_ganancia' => $validated['margen_ganancia'],
                'unidad_medida_id' => $validated['unidad_medida_id'],
            ]);

            $inventario = $repuesto->inventario()->create([
                'stockActual' => $validated['stock_inicial'],
                'stockMinimo' => 5
            ]);

            // Si hay stock inicial > 0, registrarlo en el Kardex
            if ($validated['stock_inicial'] > 0) {
                \App\Models\MovimientoInventario::create([
                    'inventario_id' => $inventario->id,
                    'user_id' => $request->user()->id ?? 1,
                    'tipoMovimiento' => 'ENTRADA',
                    'cantidad' => $validated['stock_inicial'],
                    'motivo' => 'Inventario Inicial',
                    'fecha' => now()->toDateString(),
                ]);
            }
        });

        return back()->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $repuesto = Repuesto::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigoBarras' => 'required|string|max:100|unique:repuestos,codigoBarras,' . $id,
            'categoria_id' => 'required|exists:categorias_repuestos,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'costo' => 'required|numeric|min:0',
            'margen_ganancia' => 'required|numeric|min:0',
            'unidad_medida_id' => 'required|exists:unidades_medida,id',
        ]);

        $repuesto->update([
            'nombre' => $validated['nombre'],
            'codigoBarras' => $validated['codigoBarras'],
            'categoria_repuesto_id' => $validated['categoria_id'],
            'proveedor_id' => $validated['proveedor_id'],
            'costo' => $validated['costo'],
            'margen_ganancia' => $validated['margen_ganancia'],
            'unidad_medida_id' => $validated['unidad_medida_id'],
        ]);

        return back()->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $repuesto = Repuesto::findOrFail($id);
        
        // Check if there are movements other than initialization
        $inventario = $repuesto->inventario;
        if ($inventario) {
            $movimientos = \App\Models\MovimientoInventario::where('inventario_id', $inventario->id)->count();
            if ($movimientos > 1) { // Assuming 1 movement could be initialization, wait, maybe any movement?
                // Let's be strict: if it has any usage in work orders or manual adjustments, block it.
                // Or just block if any movement exists.
                if ($movimientos > 0) {
                    return back()->with('error', 'No se puede eliminar un producto que ya tiene historial de movimientos en el Kardex.');
                }
            }
        }
        
        // Also check detalles_orden_trabajo usage via detalle_repuesto. But wait, repuesto doesn't have a direct relationship to detalle_repuesto in the standard models, it usually goes through Inventario or DetalleRepuesto.
        $hasUsage = DB::table('detalle_repuesto')->where('repuesto_id', $id)->exists();
        if ($hasUsage) {
            return back()->with('error', 'No se puede eliminar porque este repuesto ya ha sido utilizado en Órdenes de Trabajo.');
        }

        DB::transaction(function () use ($repuesto) {
            if ($repuesto->inventario) {
                \App\Models\MovimientoInventario::where('inventario_id', $repuesto->inventario->id)->delete();
                $repuesto->inventario->delete();
            }
            $repuesto->delete();
        });

        return back()->with('success', 'Producto eliminado correctamente.');
    }

    // --- Categorías CRUD ---
    public function storeCategoria(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias_repuestos,nombre',
        ]);
        CategoriaRepuesto::create($validated);
        return back()->with('success', 'Categoría creada.');
    }

    public function updateCategoria(Request $request, $id)
    {
        $categoria = CategoriaRepuesto::findOrFail($id);
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias_repuestos,nombre,' . $id,
        ]);
        $categoria->update($validated);
        return back()->with('success', 'Categoría actualizada.');
    }

    public function destroyCategoria($id)
    {
        $categoria = CategoriaRepuesto::findOrFail($id);
        if ($categoria->repuestos()->count() > 0) {
            return back()->with('error', 'No se puede eliminar la categoría porque tiene productos asignados.');
        }
        $categoria->delete();
        return back()->with('success', 'Categoría eliminada.');
    }

    // --- Unidades CRUD ---
    public function storeUnidad(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50|unique:unidades_medida,nombre',
            'permite_fracciones' => 'boolean'
        ]);
        UnidadMedida::create($validated);
        return back()->with('success', 'Unidad de medida creada.');
    }

    public function updateUnidad(Request $request, $id)
    {
        $unidad = UnidadMedida::findOrFail($id);
        $validated = $request->validate([
            'nombre' => 'required|string|max:50|unique:unidades_medida,nombre,' . $id,
            'permite_fracciones' => 'boolean'
        ]);
        $unidad->update($validated);
        return back()->with('success', 'Unidad de medida actualizada.');
    }

    public function destroyUnidad($id)
    {
        $unidad = UnidadMedida::findOrFail($id);
        if ($unidad->repuestos()->count() > 0) {
            return back()->with('error', 'No se puede eliminar la unidad de medida porque hay productos que la usan.');
        }
        $unidad->delete();
        return back()->with('success', 'Unidad de medida eliminada.');
    }
}
