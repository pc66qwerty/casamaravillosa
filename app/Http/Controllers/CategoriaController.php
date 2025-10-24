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
}