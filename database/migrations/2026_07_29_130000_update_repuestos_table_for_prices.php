<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repuestos', function (Blueprint $table) {
            $table->renameColumn('precioUnitario', 'costo');
        });
        Schema::table('repuestos', function (Blueprint $table) {
            $table->decimal('margen_ganancia', 5, 2)->default(30.00)->after('costo');
        });
    }

    public function down(): void
    {
        Schema::table('repuestos', function (Blueprint $table) {
            $table->dropColumn('margen_ganancia');
            $table->renameColumn('costo', 'precioUnitario');
        });
    }
};
