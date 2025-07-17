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
        Schema::create('cabecerafacturas', function (Blueprint $table) {
            $table->id();
            $table->string('factnum', 35)->unique();
            $table->string('apnum', 9);
            $table->date('fecha');
            $table->string('idcai', 9);
            $table->string('pago', 30);
            $table->foreignId('turno_id')->constrained('turnos')->onDelete('cascade');
            $table->string('caja_id')->constrained('cajas')->onDelete('cascade');
            $table->string('codigo_cliente', 9);
            $table->string('cliente', 200);
            $table->string('rtn', 20);
            $table->decimal('impoexon', 10, 2);
            $table->decimal('impoexen', 10, 2);
            $table->decimal('impograv15', 10, 2);
            $table->decimal('impograv18', 10, 2);
            $table->decimal('isv15', 10, 2);
            $table->decimal('isv18', 10, 2);
            $table->decimal('descto', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('enletras', 255);
            $table->string('usuario', 15);
            $table->decimal('efectivo', 10, 2);
            $table->decimal('cambio', 10, 2);
            $table->decimal('tarjeta', 10, 2);
            $table->decimal('transferencia', 10, 2);
            $table->string('idvendedor', 9);
            $table->decimal('comision', 10, 2);
            $table->string('liq', 1);
            $table->string('anular', 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabecerafacturas');
    }
};
