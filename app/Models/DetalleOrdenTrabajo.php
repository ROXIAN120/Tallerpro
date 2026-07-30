<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DetalleOrdenTrabajo extends Model {
    protected $table = 'detalles_orden_trabajo';

    protected $fillable = [
        'orden_trabajo_id',
        'servicio_id',
        'mecanico_id',
        'subtotal',
    ];
    
    public function servicio() {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function repuestos() {
        return $this->belongsToMany(Repuesto::class, 'detalle_repuesto', 'detalle_orden_trabajo_id', 'repuesto_id')
                    ->withPivot('cantidad', 'precioVenta')
                    ->withTimestamps();
    }
}
