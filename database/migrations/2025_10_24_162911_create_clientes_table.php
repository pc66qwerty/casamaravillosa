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
    Schema::create('clientes', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->string('apellido');
        $table->string('email')->unique()->nullable();
        $table->string('telefono', 20);
        $table->string('dpi', 20)->nullable();
        $table->text('direccion')->nullable();
        $table->enum('tipo_vehiculo', ['moto', 'carro', 'bicicleta'])->default('moto');
        $table->string('marca_vehiculo')->nullable();
        $table->string('modelo_vehiculo')->nullable();
        $table->string('placa_vehiculo')->nullable();
        $table->enum('estado', ['activo', 'inactivo'])->default('activo');
        $table->text('notas')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
