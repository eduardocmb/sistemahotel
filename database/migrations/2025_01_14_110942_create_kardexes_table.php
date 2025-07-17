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
        Schema::create('kardexes', function (Blueprint $table) {
            $table->id();
            $table->float('ahora');
            $table->date('fecha');
            $table->string('numdocto', 50);
            $table->string('movimiento', 20);
            $table->string('detalle', 200);
            $table->string('codproducto', 30);
            $table->float('habian');
            $table->float('entrada');
            $table->float('salida');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kardexes');
    }
};
