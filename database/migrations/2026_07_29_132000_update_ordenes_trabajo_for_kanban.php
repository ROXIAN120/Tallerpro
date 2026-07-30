<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('ordenes_trabajo', function (Blueprint $table) {
            $table->foreignId('mecanico_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('hora_inicio')->nullable();
            $table->timestamp('hora_fin')->nullable();
        });
    }

    public function down(): void {
        Schema::table('ordenes_trabajo', function (Blueprint $table) {
            $table->dropForeign(['mecanico_id']);
            $table->dropColumn(['mecanico_id', 'hora_inicio', 'hora_fin']);
        });
    }
};
