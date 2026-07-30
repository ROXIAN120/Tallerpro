<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ModeloVehiculo extends Model {
    protected $table = 'modelos_vehiculos';
    protected $fillable = ['marca_vehiculo_id', 'nombre'];

    public function marca() {
        return $this->belongsTo(MarcaVehiculo::class, 'marca_vehiculo_id');
    }
}
