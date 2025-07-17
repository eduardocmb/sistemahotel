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
        Schema::create('correlativos', function (Blueprint $table) {
            $table->id();                               // Campo id auto incremental
            $table->string('codigo', 4)->unique();
            $table->text('description')->nullable();     // Descripción opcional del correlativo
            $table->integer('last');
            $table->string('del', 1)->default('N');
            // Campo para el correlativo (como un código o etiqueta)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('correlativos');
    }
};
