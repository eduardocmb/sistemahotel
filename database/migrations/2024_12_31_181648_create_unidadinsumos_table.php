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
        Schema::create('unidadinsumos', function (Blueprint $table) {
            $table->id(); // id (int, clave primaria, autoincremental)
            $table->string('nombre', 100); // nombre (string con límite de 100 caracteres)
            $table->integer('contiene');
            $table->string('del', 1)->default('N');
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidadinsumos');
    }
};
