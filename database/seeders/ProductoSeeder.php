<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Inventario;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        // Arrays de datos para generar productos aleatorios y realistas
        $nombresBase = [
            'Moto' => ['Llanta', 'Pastillas de Freno', 'Filtro de Aire', 'Aceite', 'Bujía', 'Kit de Arrastre'],
            'Carro' => ['Llanta', 'Batería', 'Filtro de Aceite', 'Amortiguador', 'Disco de Freno', 'Faro LED'],
            'Bicicleta' => ['Llanta MTB', 'Cadena', 'Frenos V-Brake', 'Sillín', 'Pedales de Aluminio', 'Manillar']
        ];

        $marcas = [
            'Moto' => ['Michelin', 'Brembo', 'K&N', 'Castrol', 'NGK', 'Motul'],
            'Carro' => ['Bridgestone', 'Bosch', 'Mann', 'Monroe', 'Philips', 'Goodyear'],
            'Bicicleta' => ['Maxxis', 'Shimano', 'Tektro', 'Selle Italia', 'RockBros', 'Race Face']
        ];
        
        // Mapeo de categorías según tu CategoriaSeeder (ajusta si es necesario)
        $categorias = [
            'Moto' => [1, 2, 3, 4],
            'Carro' => [5, 6, 7, 8],
            'Bicicleta' => [9, 10, 11, 12]
        ];

        // Bucle para crear 200 productos
        for ($i = 1; $i <= 200; $i++) {
            // Seleccionar un tipo de vehículo aleatorio
            $tipoVehiculo = array_rand($nombresBase);
            
            // Generar datos del producto
            $nombreProducto = $nombresBase[$tipoVehiculo][array_rand($nombresBase[$tipoVehiculo])];
            $marcaProducto = $marcas[$tipoVehiculo][array_rand($marcas[$tipoVehiculo])];
            $categoriaId = $categorias[$tipoVehiculo][array_rand($categorias[$tipoVehiculo])];
            
            $precioCompra = rand(50, 1500);
            // El precio de venta será entre un 30% y un 80% más caro que el de compra
            $precioVenta = $precioCompra * (1 + rand(30, 80) / 100);

            // Crear el producto en la base de datos
            $producto = Producto::create([
                'codigo' => strtoupper(substr($tipoVehiculo, 0, 2)) . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nombre' => $nombreProducto . ' ' . $marcaProducto . ' Modelo #' . rand(100, 999),
                'categoria_id' => $categoriaId,
                'marca' => $marcaProducto,
                'precio_compra' => $precioCompra,
                'precio_venta' => round($precioVenta, 2), // Redondear a 2 decimales
                'stock_minimo' => rand(5, 20),
            ]);
            
            // Crear el inventario inicial para el producto recién creado
            Inventario::create([
                'producto_id' => $producto->id,
                'cantidad_actual' => rand(20, 100), // Stock inicial aleatorio
                'cantidad_entrada' => rand(20, 100), // La primera entrada es el stock inicial
                'cantidad_salida' => 0,
                'tipo_movimiento' => 'entrada',
                'motivo' => 'Inventario inicial',
                'usuario_id' => 1 // Asumimos que el admin (ID 1) hace el registro inicial
            ]);
        }
    }
}