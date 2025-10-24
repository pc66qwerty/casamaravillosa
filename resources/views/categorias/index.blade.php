@extends('layouts.app')

@section('title', 'Categorías - Casa Maravillosa')
@section('page-title', 'Categorías de Productos')
@section('page-subtitle', 'Organiza tus productos por categorías')

@section('content')
<div class="space-y-6">

    <!-- Mensajes -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
        {{ session('error') }}
    </div>
    @endif

    <!-- Barra de acciones -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Gestión de Categorías</h2>
            <p class="text-gray-600">Total: {{ $categorias->count() }} categorías</p>
        </div>
        <a href="{{ route('categorias.create') }}" 
           class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
            </svg>
            Nueva Categoría
        </a>
    </div>

    <!-- Grid de categorías por tipo -->
    @foreach(['moto' => 'Motos', 'carro' => 'Carros', 'bicicleta' => 'Bicicletas'] as $tipo => $nombreTipo)
    <div>
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span class="w-2 h-8 rounded 
                {{ $tipo == 'moto' ? 'bg-purple-500' : '' }}
                {{ $tipo == 'carro' ? 'bg-blue-500' : '' }}
                {{ $tipo == 'bicicleta' ? 'bg-green-500' : '' }}">
            </span>
            {{ $nombreTipo }}
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($categorias->where('tipo', $tipo) as $categoria)
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h4 class="text-lg font-bold text-gray-800">{{ $categoria->nombre }}</h4>
                        @if($categoria->descripcion)
                        <p class="text-sm text-gray-600 mt-1">{{ $categoria->descripcion }}</p>
                        @endif
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                        {{ $categoria->estado == 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($categoria->estado) }}
                    </span>
                </div>

                <div class="flex items-center justify-between pt-4 border-t">
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                        </svg>
                        <span class="text-sm font-medium">{{ $categoria->productos_count }} productos</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('categorias.edit', $categoria) }}" 
                           class="text-yellow-600 hover:text-yellow-800">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                        </a>
                        <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" class="inline" 
                              onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-8 text-gray-500">
                No hay categorías de {{ strtolower($nombreTipo) }}
            </div>
            @endforelse
        </div>
    </div>
    @endforeach

</div>
@endsection