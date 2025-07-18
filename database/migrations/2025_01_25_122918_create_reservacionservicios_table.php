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
        Schema::create('reservacionservicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservacion_id')->constrained('reservacions')->onDelete('cascade');
            $table->foreignId('producto_id')
            ->nullable()
            ->constrained('productos')
            ->onDelete('cascade');
            $table->integer('cantidad')->default(1);
            $table->decimal('subtotal', 10, 2);
            $table->string('del', 1)->default('N');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservacionservicios');
    }
};
