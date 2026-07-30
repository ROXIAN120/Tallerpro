<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    use HasFactory;

    protected $table = 'unidades_medida';

    protected $fillable = [
        'nombre',
        'permite_fracciones'
    ];

    protected $casts = [
        'permite_fracciones' => 'boolean'
    ];

    public function repuestos()
    {
        return $this->hasMany(Repuesto::class);
    }
}
