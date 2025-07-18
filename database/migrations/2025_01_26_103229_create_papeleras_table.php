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
        Schema::create('papeleras', function (Blueprint $table) {
            $table->id();
            $table->string('registro', 250);
            $table->string('modelo', 200);
            $table->string('pk', 100);
            $table->string('token', 100);
            $table->string('usuario', 50);
            $table->dateTime('fecha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('papeleras');
    }
};
