<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::withCount('productos')->orderBy('tipo')->get();
        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:moto,carro,bicicleta',
        ]);

        Categoria::create($validated);

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría creada exitosamente');
    }

    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:moto,carro,bicicleta',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $categoria->update($validated);

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría actualizada exitosamente');
    }

    public function destroy(Categoria $categoria)
    {
        if ($categoria->productos()->count() > 0) {
            return redirect()->route('categorias.index')
                ->with('error', 'No se puede eliminar una categoría con productos asociados');
        }

        $categoria->delete();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría eliminada exitosamente');
    }
    public function export(Request $request)
{
    $query = Categoria::withCount('productos');

    // Aplicar filtros
    if ($request->filled('buscar')) {
        $query->where('nombre', 'like', "%{$request->buscar}%");
    }

    if ($request->filled('tipo')) {
        $query->where('tipo', $request->tipo);
    }

    if ($request->filled('estado')) {
        $query->where('estado', $request->estado);
    }

    $categorias = $query->get();
    
    $filename = "categorias_" . date('Y-m-d_His') . ".csv";
    $headers = [
        'Content-Type' => 'text/csv; charset=utf-8',
        'Content-Disposition' => "attachment; filename=$filename",
    ];

    $callback = function() use ($categorias) {
        $file = fopen('php://output', 'w');
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8
        
        fputcsv($file, ['ID', 'Nombre', 'Tipo', 'Descripción', 'Total Productos', 'Estado', 'Fecha Creación']);
        
        foreach ($categorias as $categoria) {
            fputcsv($file, [
                $categoria->id,
                $categoria->nombre,
                ucfirst($categoria->tipo),
                $categoria->descripcion,
                $categoria->productos_count,
                ucfirst($categoria->estado),
                $categoria->created_at->format('d/m/Y H:i')
            ]);
        }
        
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
}