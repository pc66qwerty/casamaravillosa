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
    Schema::create('ventas', function (Blueprint $table) {
        $table->id();
        $table->string('numero_venta')->unique();
        $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
        $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
        $table->decimal('subtotal', 10, 2);
        $table->decimal('descuento', 10, 2)->default(0);
        $table->decimal('total', 10, 2);
        $table->enum('estado', ['completada', 'pendiente', 'cancelada'])->default('completada');
        $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia', 'credito'])->default('efectivo');
        $table->text('notas')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
