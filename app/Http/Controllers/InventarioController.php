<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with(['categoria', 'inventarios' => function($q) {
            $q->latest()->first();
        }]);

        // Filtrar por alerta de stock bajo
        if ($request->has('alerta') && $request->alerta == 'bajo') {
            $query->whereHas('inventarios', function($q) {
                $q->whereRaw('cantidad_actual <= productos.stock_minimo');
            });
        }

        // Filtrar por categoría
        if ($request->has('categoria') && $request->categoria != '') {
            $query->where('categoria_id', $request->categoria);
        }

        $productos = $query->orderBy('nombre')->get();
        $categorias = \App\Models\Categoria::activas()->get();

        // Estadísticas
        $stats = [
            'total_productos' => Producto::count(),
            'stock_bajo' => Producto::whereHas('inventarios', function($q) {
                $q->whereRaw('cantidad_actual <= productos.stock_minimo');
            })->count(),
            'sin_stock' => Producto::whereHas('inventarios', function($q) {
                $q->where('cantidad_actual', 0);
            })->count(),
            'valor_total' => $productos->sum(function($p) {
                return $p->precio_compra * $p->stock_actual;
            }),
        ];

        return view('inventario.index', compact('productos', 'categorias', 'stats'));
    }

    public function ajuste($id)
    {
        $producto = Producto::with(['categoria', 'inventarios' => function($q) {
            $q->latest()->first();
        }])->findOrFail($id);

        return view('inventario.ajuste', compact('producto'));
    }

    public function actualizar(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $validated = $request->validate([
            'tipo_movimiento' => 'required|in:entrada,salida,ajuste',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'required|string',
        ]);

        $stockActual = $producto->stock_actual;

        // Calcular nuevo stock según el tipo de movimiento
        if ($validated['tipo_movimiento'] == 'entrada') {
            $nuevoStock = $stockActual + $validated['cantidad'];
            $cantidadEntrada = $validated['cantidad'];
            $cantidadSalida = 0;
        } elseif ($validated['tipo_movimiento'] == 'salida') {
            if ($stockActual < $validated['cantidad']) {
                return redirect()->back()
                    ->with('error', 'No hay suficiente stock disponible')
                    ->withInput();
            }
            $nuevoStock = $stockActual - $validated['cantidad'];
            $cantidadEntrada = 0;
            $cantidadSalida = $validated['cantidad'];
        } else { // ajuste
            $nuevoStock = $validated['cantidad'];
            $cantidadEntrada = $nuevoStock > $stockActual ? ($nuevoStock - $stockActual) : 0;
            $cantidadSalida = $nuevoStock < $stockActual ? ($stockActual - $nuevoStock) : 0;
        }

        // Crear registro de inventario
        Inventario::create([
            'producto_id' => $producto->id,
            'cantidad_actual' => $nuevoStock,
            'cantidad_entrada' => $cantidadEntrada,
            'cantidad_salida' => $cantidadSalida,
            'tipo_movimiento' => $validated['tipo_movimiento'],
            'motivo' => $validated['motivo'],
            'usuario_id' => Auth::id() ?? 1,
        ]);

        return redirect()->route('inventario.index')
            ->with('success', 'Inventario actualizado exitosamente');
    }
}