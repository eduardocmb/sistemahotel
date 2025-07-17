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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('dni', 20)->unique();
            $table->string('nombrecompleto', 100);
            $table->text('direccion');
            $table->string('telefono', 20);
            $table->date('fechanac');
            $table->string('genero', 20);
            $table->foreignId('departamento_id')->constrained('departamentos')->onDelete('cascade');
            $table->date('fechaingreso');
            $table->string('trabajotipo', 20);
            $table->decimal('salario', 10,2);
            $table->string('estado', 20);
            $table->string('del', 1)->default('N');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
