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
        Schema::create('cais', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 9)->unique();
            $table->string('cai', 50)->unique();
            $table->string('prefijo', 50);
            $table->string('numeroinicial', 20);
            $table->string('numerofinal', 20);
            $table->string('facturainicial', 50);
            $table->string('facturafinal', 50);
            $table->date('fecharecibido');
            $table->date('fechalimite');
            $table->string('estado', 20);
            $table->string('posicion', 20);
            $table->string('del', 1)->default('N');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cais');
    }
};
