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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 9)->unique();
            $table->string('nombre', 255);
            $table->text('descripcion');
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->foreignId('impuesto_id')->constrained('impuestos')->onDelete('cascade');
            $table->decimal('precio_venta', 8, 2);
            $table->string('unidad_entrada_id',1)->nullable();
            $table->string('unidad_salida_id',1)->nullable();
            $table->string('tipo_producto', 50);
            $table->integer('stock_minimo');
            $table->string('del', 1)->default('N');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
