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
        Schema::create('proveedors', function (Blueprint $table) {
            $table->id(); // id (int, clave primaria, autoincremental)
            $table->string('nombre', 255); // nombre (string con límite de 255 caracteres)
            $table->string('codigo', 9)->unique();
            $table->string('telefono', 15)->nullable(); // telefono (string opcional, límite de 15 caracteres)
            $table->string('email', 100)->nullable(); // email (string opcional, límite de 100 caracteres)
            $table->text('direccion')->nullable(); // direccion (text opcional)
            $table->string('del', 1)->default('N');
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedors');
    }
};
