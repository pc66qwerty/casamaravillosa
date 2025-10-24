@extends('layouts.app')

@section('title', 'Clientes - Casa Maravillosa')
@section('page-title', 'Gestión de Clientes')
@section('page-subtitle', 'Administra tu base de clientes')

@section('content')
<div class="space-y-6">

    <!-- Mensajes de éxito -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Barra de acciones -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            
            <!-- Buscador -->
            <form method="GET" action="{{ route('clientes.index') }}" class="flex-1">
                <div class="flex gap-2">
                    <input type="text" 
                           name="buscar" 
                           value="{{ request('buscar') }}"
                           placeholder="Buscar por nombre, teléfono o email..." 
                           class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    
                    <select name="tipo" class="border border-gray-300 rounded-lg px-4 py-2">
                        <option value="">Todos los vehículos</option>
                        <option value="moto" {{ request('tipo') == 'moto' ? 'selected' : '' }}>Motos</option>
                        <option value="carro" {{ request('tipo') == 'carro' ? 'selected' : '' }}>Carros</option>
                        <option value="bicicleta" {{ request('tipo') == 'bicicleta' ? 'selected' : '' }}>Bicicletas</option>
                    </select>
                    
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        Buscar
                    </button>
                </div>
            </form>

            <!-- Botón nuevo cliente -->
            <a href="{{ route('clientes.create') }}" 
               class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                </svg>
                Nuevo Cliente
            </a>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Total Clientes</p>
            <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Cliente::count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Clientes Motos</p>
            <p class="text-2xl font-bold text-purple-600">{{ \App\Models\Cliente::where('tipo_vehiculo', 'moto')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Clientes Carros</p>
            <p class="text-2xl font-bold text-blue-600">{{ \App\Models\Cliente::where('tipo_vehiculo', 'carro')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Clientes Bicicletas</p>
            <p class="text-2xl font-bold text-green-600">{{ \App\Models\Cliente::where('tipo_vehiculo', 'bicicleta')->count() }}</p>
        </div>
    </div>

    <!-- Tabla de clientes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehículo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($clientes as $cliente)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold">
                                    {{ substr($cliente->nombre, 0, 1) }}{{ substr($cliente->apellido, 0, 1) }}
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $cliente->nombre_completo }}</div>
                                <div class="text-sm text-gray-500">{{ $cliente->dpi }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $cliente->telefono }}</div>
                        <div class="text-sm text-gray-500">{{ $cliente->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $cliente->tipo_vehiculo == 'moto' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $cliente->tipo_vehiculo == 'carro' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $cliente->tipo_vehiculo == 'bicicleta' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ ucfirst($cliente->tipo_vehiculo) }}
                        </span>
                        @if($cliente->marca_vehiculo)
                        <div class="text-xs text-gray-500 mt-1">{{ $cliente->marca_vehiculo }} {{ $cliente->modelo_vehiculo }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $cliente->estado == 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($cliente->estado) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('clientes.show', $cliente) }}" class="text-blue-600 hover:text-blue-900">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </a>
                            <a href="{{ route('clientes.edit', $cliente) }}" class="text-yellow-600 hover:text-yellow-900">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                </svg>
                            </a>
                            <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este cliente?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        No se encontraron clientes
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="px-6 py-4 bg-gray-50">
            {{ $clientes->links() }}
        </div>
    </div>

</div>
@endsection