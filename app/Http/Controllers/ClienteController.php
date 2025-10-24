<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Mostrar lista de clientes
     */
    public function index(Request $request)
    {
        $query = Cliente::query();

        // Búsqueda
        if ($request->has('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('apellido', 'like', "%{$buscar}%")
                  ->orWhere('telefono', 'like', "%{$buscar}%")
                  ->orWhere('email', 'like', "%{$buscar}%");
            });
        }

        // Filtro por tipo de vehículo
        if ($request->has('tipo') && $request->tipo != '') {
            $query->where('tipo_vehiculo', $request->tipo);
        }

        // Filtro por estado
        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        $clientes = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('clientes.index', compact('clientes'));
    }

    /**
     * Mostrar formulario de crear cliente
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Guardar nuevo cliente
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clientes,email',
            'telefono' => 'required|string|max:20',
            'dpi' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'tipo_vehiculo' => 'required|in:moto,carro,bicicleta',
            'marca_vehiculo' => 'nullable|string|max:255',
            'modelo_vehiculo' => 'nullable|string|max:255',
            'placa_vehiculo' => 'nullable|string|max:255',
            'notas' => 'nullable|string',
        ]);

        Cliente::create($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado exitosamente');
    }

    /**
     * Mostrar detalle de un cliente
     */
    public function show(Cliente $cliente)
    {
        $cliente->load(['ventas', 'interacciones']);
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Mostrar formulario de editar cliente
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Actualizar cliente
     */
    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clientes,email,' . $cliente->id,
            'telefono' => 'required|string|max:20',
            'dpi' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'tipo_vehiculo' => 'required|in:moto,carro,bicicleta',
            'marca_vehiculo' => 'nullable|string|max:255',
            'modelo_vehiculo' => 'nullable|string|max:255',
            'placa_vehiculo' => 'nullable|string|max:255',
            'estado' => 'required|in:activo,inactivo',
            'notas' => 'nullable|string',
        ]);

        $cliente->update($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado exitosamente');
    }

    /**
     * Eliminar cliente
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado exitosamente');
    }
}