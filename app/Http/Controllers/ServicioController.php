<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::with('user')->latest()->get();
        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('servicios.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'duracion_estimada' => 'required|integer|min:1',
            'estado' => 'required|string|max:30',
        ]);

        $datos['user_id'] = auth()->id();
        Servicio::create($datos);

        return redirect()->route('servicios.index')->with('success', 'Servicio registrado exitosamente.');
    }
}
