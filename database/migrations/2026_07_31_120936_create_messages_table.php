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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->string('message_id')->nullable()->unique(); // ID de WhatsApp
            $table->text('body')->nullable();
            $table->string('type')->default('text'); // text, image, document
            $table->string('direction'); // inbound, outbound
            $table->string('status')->default('sent'); // sent, delivered, read
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
