<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // <-- AÑADIDO
use Illuminate\Support\Facades\File; // <-- AÑADIDO
use Illuminate\Validation\Rule; // <-- AÑADIDO

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with(['categoria', 'inventarios' => function($q) {
            $q->latest();
        }]);

        // Búsqueda
        if ($request->has('buscar') && $request->buscar != '') {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('codigo', 'like', "%{$buscar}%")
                  ->orWhere('marca', 'like', "%{$buscar}%");
            });
        }

        // Filtro por categoría
        if ($request->has('categoria') && $request->categoria != '') {
            $query->where('categoria_id', $request->categoria);
        }

        // Filtro por estado
        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        $productos = $query->orderBy('nombre')->paginate(12);
        $categorias = Categoria::activas()->orderBy('nombre')->get();

        return view('productos.index', compact('productos', 'categorias'));
    }

    public function create()
    {
        $categorias = Categoria::activas()->orderBy('nombre')->get();
        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        // --- VALIDACIÓN MODIFICADA ---
        $validated = $request->validate([
            'codigo' => 'required|string|max:255|unique:productos,codigo',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'marca' => 'nullable|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'ubicacion' => 'nullable|string|max:255',
            'stock_inicial' => 'required|integer|min:0',
            
            // Nuevas reglas de validación de imagen
            'tipo_imagen' => 'required|in:file,url',
            'imagen_file' => [
                Rule::requiredIf($request->tipo_imagen == 'file'),
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048', // 2MB Max
            ],
            'imagen_url' => [
                Rule::requiredIf($request->tipo_imagen == 'url'),
                'nullable',
                'url',
            ],
        ]);

        // Separar stock inicial y preparar datos del producto
        $stockInicial = $validated['stock_inicial'];
        $data = $request->except(['_token', 'stock_inicial', 'tipo_imagen', 'imagen_file', 'imagen_url']);
        
        $rutaImagen = null;

        // --- LÓGICA DE IMAGEN MODIFICADA ---
        if ($request->tipo_imagen == 'file' && $request->hasFile('imagen_file')) {
            $imagen = $request->file('imagen_file');
            // Crear un nombre único
            $nombreImagen = Str::slug($request->codigo, '-') . '-' . time() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('imagenes/productos'), $nombreImagen);
            $rutaImagen = 'imagenes/productos/' . $nombreImagen;

        } elseif ($request->tipo_imagen == 'url' && $request->filled('imagen_url')) {
            // Guardar la URL directamente
            $rutaImagen = $request->imagen_url;
        }

        $data['imagen'] = $rutaImagen;
        // ---------------------------------

        $producto = Producto::create($data);

        // Crear inventario inicial
        Inventario::create([
            'producto_id' => $producto->id,
            'cantidad_actual' => $stockInicial,
            'cantidad_entrada' => $stockInicial,
            'cantidad_salida' => 0,
            'tipo_movimiento' => 'entrada',
            'motivo' => 'Stock inicial',
            'usuario_id' => Auth::id() ?? 1,
        ]);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente');
    }

    public function show(Producto $producto)
    {
        $producto->load(['categoria', 'inventarios' => function($q) {
            $q->latest()->limit(10);
        }]);
        
        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::activas()->orderBy('nombre')->get();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        // --- VALIDACIÓN MODIFICADA ---
        $validated = $request->validate([
            'codigo' => 'required|string|max:255|unique:productos,codigo,' . $producto->id,
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'marca' => 'nullable|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'ubicacion' => 'nullable|string|max:255',
            'estado' => 'required|in:activo,inactivo',

            // Nuevas reglas de validación de imagen
            'tipo_imagen' => 'required|in:file,url',
            'imagen_file' => [ // No es 'required', solo si se sube una nueva
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048', // 2MB Max
            ],
            'imagen_url' => [
                'nullable',
                'url',
            ],
        ]);
        
        $data = $request->except(['_token', '_method', 'tipo_imagen', 'imagen_file', 'imagen_url']);

        $rutaImagen = $producto->imagen; // Mantener la imagen actual por defecto

        // --- LÓGICA DE IMAGEN MODIFICADA ---
        if ($request->tipo_imagen == 'file' && $request->hasFile('imagen_file')) {
            // Eliminar imagen anterior si es local
            if ($producto->imagen && !Str::startsWith($producto->imagen, 'http') && File::exists(public_path($producto->imagen))) {
                File::delete(public_path($producto->imagen));
            }
            
            $imagen = $request->file('imagen_file');
            $nombreImagen = Str::slug($request->codigo, '-') . '-' . time() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('imagenes/productos'), $nombreImagen);
            $rutaImagen = 'imagenes/productos/' . $nombreImagen;

        } elseif ($request->tipo_imagen == 'url' && $request->filled('imagen_url')) {
            // Eliminar imagen anterior si es local
            if ($producto->imagen && !Str::startsWith($producto->imagen, 'http') && File::exists(public_path($producto->imagen))) {
                File::delete(public_path($producto->imagen));
            }
            // Guardar la nueva URL
            $rutaImagen = $request->imagen_url;
        }

        $data['imagen'] = $rutaImagen;
        // ---------------------------------

        $producto->update($data);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente');
    }
    
    public function destroy(Producto $producto)
    {
        // --- LÓGICA DE BORRADO DE IMAGEN AÑADIDA ---
        // Eliminar la imagen si es local
        if ($producto->imagen && !Str::startsWith($producto->imagen, 'http') && File::exists(public_path($producto->imagen))) {
            File::delete(public_path($producto->imagen));
        }

        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado exitosamente');
    }
}
