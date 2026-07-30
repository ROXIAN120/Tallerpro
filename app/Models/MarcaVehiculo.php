<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MarcaVehiculo extends Model {
    protected $table = 'marcas_vehiculos';
    protected $fillable = ['nombre'];
}
