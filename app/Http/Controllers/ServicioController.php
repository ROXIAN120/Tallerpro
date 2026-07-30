<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\CategoriaServicio;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServicioController extends Controller
{
    public function index()
    {
        $categorias = CategoriaServicio::all();
        $servicios = Servicio::with('categoria')->get();

        return Inertia::render('Taller/Servicios', [
            'categorias' => $categorias,
            'servicios' => $servicios
        ]);
    }

    public function storeCategoria(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        CategoriaServicio::create($validated);

        return redirect()->back()->with('success', 'Categoría creada con éxito.');
    }

    public function updateCategoria(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        $categoria = CategoriaServicio::findOrFail($id);
        $categoria->update($validated);

        return redirect()->back()->with('success', 'Categoría actualizada con éxito.');
    }

    public function destroyCategoria($id)
    {
        $categoria = CategoriaServicio::findOrFail($id);
        
        if (Servicio::where('categoria_servicio_id', $id)->exists()) {
            return redirect()->back()->with('error', 'No se puede eliminar la categoría porque tiene servicios asignados.');
        }

        $categoria->delete();

        return redirect()->back()->with('success', 'Categoría eliminada con éxito.');
    }

    public function storeServicio(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'categoria_servicio_id' => 'required|exists:categorias_servicios,id',
            'descripcion' => 'nullable|string',
            'precioBase' => 'required|numeric|min:0',
            'tiempoEstimadoHoras' => 'required|numeric|min:0',
        ]);

        Servicio::create($validated);

        return redirect()->back()->with('success', 'Servicio creado con éxito.');
    }

    public function updateServicio(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'categoria_servicio_id' => 'required|exists:categorias_servicios,id',
            'descripcion' => 'nullable|string',
            'precioBase' => 'required|numeric|min:0',
            'tiempoEstimadoHoras' => 'required|numeric|min:0',
        ]);

        $servicio = Servicio::findOrFail($id);
        $servicio->update($validated);

        return redirect()->back()->with('success', 'Servicio actualizado con éxito.');
    }

    public function destroyServicio($id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->delete();

        return redirect()->back()->with('success', 'Servicio eliminado con éxito.');
    }
}
