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
        Schema::create('deduccions', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->string('tipo', 20);
            $table->decimal('monto', 10, 2);
            $table->string('activo', 1)->default('S');
            $table->string('descripcion', 250);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deduccions');
    }
};
