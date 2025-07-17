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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_cliente', 9)->unique();
            $table->string('nombre_completo', 250);
            $table->string('tipo_id', 50);
            $table->string('identificacion', 20);
            $table->string('rtn', 14);
            $table->string('telefono', 20);
            $table->text('direccion');
            $table->string('email', 250);
            $table->string('del', 1)->default('N');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
