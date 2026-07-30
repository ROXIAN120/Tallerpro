<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Repuesto extends Model
{
    protected $table = 'repuestos';
    
    protected $fillable = [
        'categoria_repuesto_id', 
        'proveedor_id', 
        'nombre', 
        'codigoBarras', 
        'costo', 
        'margen_ganancia',
        'unidad_medida_id'
    ];

    // Para exponer el atributo calculado (accessor) al serializar a JSON (en Inertia)
    protected $appends = ['precio_venta'];

    // Relaciones
    public function categoria()
    {
        return $this->belongsTo(CategoriaRepuesto::class, 'categoria_repuesto_id');
    }

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_medida_id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function inventario()
    {
        return $this->hasOne(Inventario::class, 'repuesto_id');
    }

    /**
     * Accessor Moderno (Laravel 11) para calcular el precio de venta final.
     */
    protected function precioVenta(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => 
                (isset($attributes['costo']) && isset($attributes['margen_ganancia']))
                    ? round($attributes['costo'] * (1 + ($attributes['margen_ganancia'] / 100)), 2)
                    : 0,
        );
    }
}
