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
        $table->string('codigo')->unique();
        $table->string('nombre');
        $table->text('descripcion')->nullable();
        $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
        $table->string('marca')->nullable();
        $table->string('modelo')->nullable();
        $table->decimal('precio_compra', 10, 2);
        $table->decimal('precio_venta', 10, 2);
        $table->integer('stock_minimo')->default(5);
        $table->string('ubicacion')->nullable();
        $table->string('imagen')->nullable();
        $table->enum('estado', ['activo', 'inactivo'])->default('activo');
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
