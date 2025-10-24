<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $nombres = ['Juan', 'María', 'Carlos', 'Ana', 'Luis', 'Sofia', 'Pedro', 'Laura'];
        $apellidos = ['García', 'López', 'Martínez', 'Rodríguez', 'Hernández', 'González'];
        $tipos = ['moto', 'carro', 'bicicleta'];

        for ($i = 1; $i <= 30; $i++) {
            Cliente::create([
                'nombre' => $nombres[array_rand($nombres)],
                'apellido' => $apellidos[array_rand($apellidos)],
                'email' => 'cliente' . $i . '@example.com',
                'telefono' => '5' . rand(1000000, 9999999),
                'dpi' => rand(1000000000000, 9999999999999),
                'direccion' => 'Zona ' . rand(1, 21) . ', Ciudad de Guatemala',
                'tipo_vehiculo' => $tipos[array_rand($tipos)],
                'estado' => 'activo'
            ]);
        }
    }
}