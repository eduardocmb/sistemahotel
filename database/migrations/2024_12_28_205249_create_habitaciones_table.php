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
        Schema::create('habitaciones', function (Blueprint $table) {
            $table->id();
            $table->string('numero_habitacion')->unique();
            $table->string('tipo_habitacion', 20);
            $table->text('descripcion')->nullable();
            $table->decimal('precio_diario', 10, 2);
            $table->string('estado',50)->default('DISPONIBLE');
            $table->text('observaciones')->nullable();
            $table->string('del', 1)->default('N');
            $table->integer('capacidad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habitaciones');
    }
};
