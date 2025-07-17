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
        Schema::create('detalleplanillas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_planilla', 9);
            $table->string('motivo', 50);
            $table->integer('cantidad');
            $table->decimal('devengado', 10, 2);
            $table->decimal('deducido', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalleplanillas');
    }
};
