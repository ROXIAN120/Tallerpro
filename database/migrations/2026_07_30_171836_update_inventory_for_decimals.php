<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inventarios', function (Blueprint $table) {
            $table->decimal('stockActual', 10, 3)->default(0)->change();
            $table->decimal('stockMinimo', 10, 3)->default(5)->change();
        });

        Schema::table('movimientos_inventario', function (Blueprint $table) {
            $table->decimal('cantidad', 10, 3)->change();
        });

        Schema::table('detalle_repuesto', function (Blueprint $table) {
            $table->decimal('cantidad', 10, 3)->default(1)->change();
        });

        Schema::table('repuestos', function (Blueprint $table) {
            $table->string('unidad_medida')->default('Unidad')->after('nombre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repuestos', function (Blueprint $table) {
            $table->dropColumn('unidad_medida');
        });

        Schema::table('detalle_repuesto', function (Blueprint $table) {
            $table->integer('cantidad')->default(1)->change();
        });

        Schema::table('movimientos_inventario', function (Blueprint $table) {
            $table->integer('cantidad')->change();
        });

        Schema::table('inventarios', function (Blueprint $table) {
            $table->integer('stockActual')->default(0)->change();
            $table->integer('stockMinimo')->default(5)->change();
        });
    }
};
