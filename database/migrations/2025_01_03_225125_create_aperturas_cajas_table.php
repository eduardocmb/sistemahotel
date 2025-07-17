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
        Schema::create('aperturas_cajas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_apertura', 9)->unique();
            $table->date('fecha');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('turno_id')->constrained('turnos')->onDelete('cascade');
            $table->foreignId('caja_id')->constrained('cajas')->onDelete('cascade');
            $table->decimal('fondoinicial', 8, 2);
            $table->string('estado', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aperturas_cajas');
    }
};
