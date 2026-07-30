<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    protected $table = 'movimientos_inventario';
    
    protected $fillable = [
        'inventario_id', 'user_id', 'tipoMovimiento', 'cantidad', 'motivo', 'fecha'
    ];

    public function inventario()
    {
        return $this->belongsTo(Inventario::class, 'inventario_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
