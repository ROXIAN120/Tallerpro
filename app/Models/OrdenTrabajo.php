<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenTrabajo extends Model
{
    protected $table = 'ordenes_trabajo';

    protected $fillable = [
        'vehiculo_id', 'cliente_id', 'sucursal_id',
        'fechaIngreso', 'fechaEntregaEstimada', 'estado', 'diagnostico',
        'hora_inicio', 'hora_fin'
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }
    
    public function detalles()
    {
        return $this->hasMany(DetalleOrdenTrabajo::class, 'orden_trabajo_id');
    }
}
