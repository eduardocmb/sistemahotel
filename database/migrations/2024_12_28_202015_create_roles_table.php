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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 9)->unique();
            $table->string('rol', 50);
            $table->string('ver_informacion', 1);
            $table->string('guardar', 1);
            $table->string('actualizar', 1);
            $table->string('eliminar', 1);
            $table->string('imprimir', 1);
            $table->string('reimprimir', 1);
            $table->string('finanzas', 1);
            $table->string('del', 1)->default("N");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
