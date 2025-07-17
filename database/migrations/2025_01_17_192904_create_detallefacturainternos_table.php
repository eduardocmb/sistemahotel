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
        Schema::create('detallefacturainternos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cabfacturainterno_id');
            $table->string('factnum', 35);
            $table->integer('pos');
            $table->string('codproducto', 25);
            $table->string('descripcion', 250);
            $table->float('cant');
            $table->decimal('precio', 10, 2);
            $table->decimal('descto', 10, 2);
            $table->decimal('total', 10, 2);
            $table->decimal('utilidad', 10, 2);
            $table->decimal('comision', 10, 2);
            $table->unsignedBigInteger('idlote')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detallefacturainternos');
    }
};
