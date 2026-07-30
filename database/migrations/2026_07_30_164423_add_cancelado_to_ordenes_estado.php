<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE ordenes_trabajo MODIFY COLUMN estado ENUM('PENDIENTE', 'DIAGNOSTICO', 'EN REPARACION', 'FINALIZADO', 'ENTREGADO', 'CANCELADO') DEFAULT 'PENDIENTE'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE ordenes_trabajo MODIFY COLUMN estado ENUM('PENDIENTE', 'DIAGNOSTICO', 'EN REPARACION', 'FINALIZADO', 'ENTREGADO') DEFAULT 'PENDIENTE'");
    }
};
