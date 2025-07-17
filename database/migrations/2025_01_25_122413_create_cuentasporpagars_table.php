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
        Schema::create('cuentasporpagars', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 9)->unique();
            $table->date('fecha');
            $table->foreignId('proveedor_id')->constrained('proveedors')->onDelete('cascade');
            $table->string('numfactura', 50);
            $table->decimal('monto_total', 10, 2);
            $table->date('fecha_vencimiento')->nullable();
            $table->string('estado', 15)->default('PENDIENTE');
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
        Schema::dropIfExists('cuentasporpagars');
    }
};
