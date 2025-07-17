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
        Schema::create('detafacturareservacioninternos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cabfacturainterno_id');
            $table->string('factnum', 35);
            $table->integer('pos');
            $table->foreignId('habitacion_id')->constrained('habitaciones')->onDelete('cascade');
            $table->foreignId('producto_id')
            ->nullable()
            ->constrained('productos')
            ->onDelete('cascade');
            $table->string('descripcion', 250);
            $table->string('cant');
            $table->string('dias');
            $table->decimal('precio', 10, 2);
            $table->decimal('descto', 10, 2);
            $table->decimal('total', 10, 2);
            $table->decimal('comision', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detafacturareservacioninternos');
    }
};
