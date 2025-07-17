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
        Schema::create('infohotels', function (Blueprint $table) {
            $table->id();
            $table->string('rtn', 18);
            $table->string('nombre', 150);
            $table->string('eslogan', 150);
            $table->string('direccion', 150);
            $table->string('telefono', 10);
            $table->string('correo', 150);
            $table->string('propietario', 80);
            $table->string('logo', 200);
            $table->string('logo2', 200)->nullable();
            $table->string('del', 1)->default('N');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infohotels');
    }
};
