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
    Schema::create('inventarios', function (Blueprint $table) {
        $table->id();
        $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
        $table->integer('cantidad_actual')->default(0);
        $table->integer('cantidad_entrada')->default(0);
        $table->integer('cantidad_salida')->default(0);
        $table->enum('tipo_movimiento', ['entrada', 'salida', 'ajuste'])->nullable();
        $table->text('motivo')->nullable();
        $table->foreignId('usuario_id')->nullable()->constrained('users')->onDelete('set null');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
