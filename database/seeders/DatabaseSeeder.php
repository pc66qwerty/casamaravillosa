<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
   public function run(): void
{
    // Crear usuario administrador
    \App\Models\User::factory()->create([
        'name' => 'Administrador',
        'email' => 'admin@casamaravillosa.com',
        'password' => bcrypt('admin123')
    ]);

    // Crear usuario vendedor
    \App\Models\User::factory()->create([
        'name' => 'Vendedor',
        'email' => 'vendedor@casamaravillosa.com',
        'password' => bcrypt('vendedor123')
    ]);

    // Ejecutar otros seeders
    $this->call([
        CategoriaSeeder::class,
        ProductoSeeder::class,
        ClienteSeeder::class,
    ]);
}
}
