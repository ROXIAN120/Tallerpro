<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaRepuesto extends Model
{
    protected $table = 'categorias_repuestos';

    protected $fillable = ['nombre'];

    public function repuestos()
    {
        return $this->hasMany(Repuesto::class, 'categoria_repuesto_id');
    }
}
