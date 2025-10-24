<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with(['categoria', 'inventarios' => function($q) {
            $q->latest()->limit(1);
        }]);

        // Filtros
        if ($request->filled('buscar')) {
            $query->where(function($q) use ($request) {
                $q->where('codigo', 'like', "%{$request->buscar}%")
                  ->orWhere('nombre', 'like', "%{$request->buscar}%")
                  ->orWhere('marca', 'like', "%{$request->buscar}%");
            });
        }

        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $productos = $query->orderBy('created_at', 'desc')->paginate(20);
        $categorias = Categoria::activas()->get();

        return view('productos.index', compact('productos', 'categorias'));
    }

    public function create()
    {
        $categorias = Categoria::activas()->get();
        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|unique:productos,codigo',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'marca' => 'nullable|string|max:100',
            'modelo' => 'nullable|string|max:100',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_inicial' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'ubicacion' => 'nullable|string|max:100',
            'imagen_file' => 'nullable|image|max:2048',
            'imagen_url' => 'nullable|url',
        ]);

        // Manejar la imagen
        $imagenPath = null;
        if ($request->hasFile('imagen_file')) {
            $imagenPath = $request->file('imagen_file')->store('productos', 'public');
            $imagenPath = 'storage/' . $imagenPath;
        } elseif ($request->filled('imagen_url')) {
            $imagenPath = $request->imagen_url;
        }

        $producto = Producto::create([
            'codigo' => $validated['codigo'],
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'categoria_id' => $validated['categoria_id'],
            'marca' => $validated['marca'],
            'modelo' => $validated['modelo'],
            'precio_compra' => $validated['precio_compra'],
            'precio_venta' => $validated['precio_venta'],
            'stock_minimo' => $validated['stock_minimo'],
            'ubicacion' => $validated['ubicacion'],
            'imagen' => $imagenPath,
            'estado' => 'activo',
        ]);

        // Crear registro inicial de inventario
        Inventario::create([
            'producto_id' => $producto->id,
            'cantidad_actual' => $validated['stock_inicial'],
            'cantidad_entrada' => $validated['stock_inicial'],
            'cantidad_salida' => 0,
            'tipo_movimiento' => 'entrada',
            'motivo' => 'Stock inicial',
            'usuario_id' => Auth::id() ?? 1,
        ]);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente');
    }

    public function show($id)
    {
        $producto = Producto::with(['categoria', 'inventarios' => function($q) {
            $q->orderBy('created_at', 'desc')->limit(10);
        }])->findOrFail($id);

        return view('productos.show', compact('producto'));
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::activas()->get();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $validated = $request->validate([
            'codigo' => 'required|unique:productos,codigo,' . $id,
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'marca' => 'nullable|string|max:100',
            'modelo' => 'nullable|string|max:100',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'ubicacion' => 'nullable|string|max:100',
            'estado' => 'required|in:activo,inactivo',
            'imagen_file' => 'nullable|image|max:2048',
            'imagen_url' => 'nullable|url',
        ]);

        // Manejar la imagen
        if ($request->hasFile('imagen_file')) {
            $imagenPath = $request->file('imagen_file')->store('productos', 'public');
            $validated['imagen'] = 'storage/' . $imagenPath;
        } elseif ($request->filled('imagen_url')) {
            $validated['imagen'] = $request->imagen_url;
        }

        $producto->update($validated);

        return redirect()->route('productos.show', $producto)
            ->with('success', 'Producto actualizado exitosamente');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado exitosamente');
    }

    // MÉTODO DE EXPORTACIÓN
    public function export(Request $request)
    {
        $query = Producto::with(['categoria', 'inventarios' => function($q) {
            $q->latest()->first();
        }]);

        // Aplicar filtros
        if ($request->filled('buscar')) {
            $query->where(function($q) use ($request) {
                $q->where('codigo', 'like', "%{$request->buscar}%")
                  ->orWhere('nombre', 'like', "%{$request->buscar}%")
                  ->orWhere('marca', 'like', "%{$request->buscar}%");
            });
        }

        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $productos = $query->get();
        
        $filename = "productos_" . date('Y-m-d_His') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function() use ($productos) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM para UTF-8
            
            // Encabezados
            fputcsv($file, [
                'Código', 'Nombre', 'Categoría', 'Marca', 'Modelo',
                'Precio Compra', 'Precio Venta', 'Margen %',
                'Stock Actual', 'Stock Mínimo', 'Estado', 'Fecha Registro'
            ]);
            
            // Datos
            foreach ($productos as $producto) {
                fputcsv($file, [
                    $producto->codigo,
                    $producto->nombre,
                    $producto->categoria->nombre ?? 'N/A',
                    $producto->marca,
                    $producto->modelo,
                    number_format($producto->precio_compra, 2),
                    number_format($producto->precio_venta, 2),
                    number_format($producto->margen_ganancia, 2),
                    $producto->stock_actual,
                    $producto->stock_minimo,
                    ucfirst($producto->estado),
                    $producto->created_at->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}