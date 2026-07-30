<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = ['razonSocial', 'nit', 'telefono', 'direccion'];

    public function repuestos()
    {
        return $this->hasMany(Repuesto::class, 'proveedor_id');
    }
}
