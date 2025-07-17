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
        Schema::create('cierres_cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aperturas_caja_id')->constrained('aperturas_cajas')->onDelete('cascade');
            $table->decimal('fondo', 15, 2);
            $table->decimal('ventasefe', 15, 2);
            $table->decimal('ventaspos', 15, 2);
            $table->decimal('transferencias', 15, 2);
            $table->decimal('totventas', 15, 2);
            $table->decimal('rec_ctas', 15, 2);
            $table->decimal('caja', 15, 2);
            $table->decimal('egresos', 15, 2);
            $table->decimal('diferencia', 15, 2);
            $table->text('observ');
            $table->decimal('retirar', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cierres_cajas');
    }
};
