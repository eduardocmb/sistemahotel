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
        Schema::create('pagoscuentas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuentasporpagar_id')->constrained('cuentasporpagars')->onDelete('cascade');
            $table->date('fecha');
            $table->decimal('monto_pagado', 10, 2);
            $table->string('tipo_pago', 20);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagoscuentas');
    }
};
