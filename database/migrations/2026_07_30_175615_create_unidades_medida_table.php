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
        Schema::create('unidades_medida', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->boolean('permite_fracciones')->default(false);
            $table->timestamps();
        });

        // Modificamos repuestos
        Schema::table('repuestos', function (Blueprint $table) {
            $table->foreignId('unidad_medida_id')->nullable()->constrained('unidades_medida')->nullOnDelete();
            $table->dropColumn('unidad_medida');
        });
    }

    public function down(): void
    {
        Schema::table('repuestos', function (Blueprint $table) {
            $table->string('unidad_medida', 50)->nullable();
            $table->dropForeign(['unidad_medida_id']);
            $table->dropColumn('unidad_medida_id');
        });

        Schema::dropIfExists('unidades_medida');
    }
};
