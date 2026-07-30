<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $fillable = [
        'categoria_servicio_id',
        'nombre',
        'descripcion',
        'precioBase',
        'tiempoEstimadoHoras'
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaServicio::class, 'categoria_servicio_id');
    }
}
