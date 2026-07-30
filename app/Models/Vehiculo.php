<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model {
    protected $table = 'vehiculos';

    protected $fillable = [
        'cliente_id',
        'modelo_vehiculo_id',
        'placa',
        'color',
        'anio',
        'chasisVIN',
    ];

    public function cliente() {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function modelo() {
        return $this->belongsTo(ModeloVehiculo::class, 'modelo_vehiculo_id');
    }
}
