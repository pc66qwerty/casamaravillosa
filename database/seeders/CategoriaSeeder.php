<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            // Motos
            ['nombre' => 'Llantas para Motos', 'descripcion' => 'Llantas y neumáticos para motocicletas', 'tipo' => 'moto', 'estado' => 'activo'],
            ['nombre' => 'Frenos para Motos', 'descripcion' => 'Pastillas, discos y sistemas de freno', 'tipo' => 'moto', 'estado' => 'activo'],
            ['nombre' => 'Filtros para Motos', 'descripcion' => 'Filtros de aire y aceite', 'tipo' => 'moto', 'estado' => 'activo'],
            ['nombre' => 'Aceites para Motos', 'descripcion' => 'Aceites y lubricantes', 'tipo' => 'moto', 'estado' => 'activo'],
            
            // Carros
            ['nombre' => 'Llantas para Carros', 'descripcion' => 'Llantas y neumáticos para automóviles', 'tipo' => 'carro', 'estado' => 'activo'],
            ['nombre' => 'Baterías', 'descripcion' => 'Baterías para automóviles', 'tipo' => 'carro', 'estado' => 'activo'],
            ['nombre' => 'Filtros para Carros', 'descripcion' => 'Filtros de aire, aceite y combustible', 'tipo' => 'carro', 'estado' => 'activo'],
            ['nombre' => 'Sistema Eléctrico', 'descripcion' => 'Componentes eléctricos', 'tipo' => 'carro', 'estado' => 'activo'],
            
            // Bicicletas
            ['nombre' => 'Llantas para Bicicletas', 'descripcion' => 'Llantas y neumáticos para bicicletas', 'tipo' => 'bicicleta', 'estado' => 'activo'],
            ['nombre' => 'Cadenas y Piñones', 'descripcion' => 'Sistema de transmisión', 'tipo' => 'bicicleta', 'estado' => 'activo'],
            ['nombre' => 'Frenos para Bicicletas', 'descripcion' => 'Sistemas de frenado', 'tipo' => 'bicicleta', 'estado' => 'activo'],
            ['nombre' => 'Accesorios', 'descripcion' => 'Accesorios varios para bicicletas', 'tipo' => 'bicicleta', 'estado' => 'activo'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}