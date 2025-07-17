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
        Schema::create('cabeceracomprainsumos', function (Blueprint $table) {
            $table->id();
            $table->string('codigocompra', 9)->unique();
            $table->foreignid('proveedor_id')->constrained('proveedors')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->date('fecha_compra');
            $table->foreignId('turno_id')->constrained('turnos')->onDelete('cascade');
            $table->string('tipo_pago', 50);
            $table->string('numfactura', 30);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('impuesto', 10, 2);
            $table->decimal('total_compra', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabeceracomprainsumos');
    }
};
