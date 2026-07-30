<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaServicio extends Model
{
    protected $table = 'categorias_servicios';
    
    protected $fillable = [
        'nombre',
    ];

    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'categoria_servicio_id');
    }
}
