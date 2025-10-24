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
    Schema::create('interacciones', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
        $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
        $table->enum('tipo', ['llamada', 'whatsapp', 'email', 'visita', 'otro']);
        $table->text('descripcion');
        $table->date('fecha_seguimiento')->nullable();
        $table->enum('estado', ['pendiente', 'completado'])->default('completado');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interacciones');
    }
};
