@extends('layouts.app')

@section('title', 'Productos - Casa Maravillosa')
@section('page-title', 'Catálogo de Productos')
@section('page-subtitle', 'Gestiona tu inventario de repuestos')

@section('content')
<div class="space-y-6">

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
        {{ session('success') }}
    </div>
    @endif

    <!-- Barra de búsqueda y filtros -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('productos.index') }}" class="space-y-4">
            <div class="flex flex-col md:flex-row gap-4">
                <input type="text" name="buscar" value="{{ request('buscar') }}" 
                       placeholder="Buscar por código, nombre o marca..."
                       class="flex-1 border border-gray-300 rounded-lg px-4 py-2">
                
                <select name="categoria" class="border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $cat)
                    <option value="{{ $cat->id }}" {{ request('categoria') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->nombre }}
                    </option>
                    @endforeach
                </select>

                <select name="estado" class="border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Todos los estados</option>
                    <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>

                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Buscar
                </button>

                <a href="{{ route('productos.create') }}" 
                   class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 inline-flex items-center gap-2 justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                    </svg>
                    Nuevo
                </a>
            </div>
        </form>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Total Productos</p>
            <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Producto::count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Productos Activos</p>
            <p class="text-2xl font-bold text-green-600">{{ \App\Models\Producto::where('estado', 'activo')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Stock Bajo</p>
            <p class="text-2xl font-bold text-orange-600">
                {{ \App\Models\Producto::whereHas('inventarios', function($q) {
                    $q->whereRaw('cantidad_actual <= stock_minimo');
                })->count() }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-500 text-sm">Valor Inventario</p>
            <p class="text-2xl font-bold text-blue-600">
                Q{{ number_format(\App\Models\Producto::with('inventarios')->get()->sum(function($p) {
                    return $p->precio_compra * ($p->inventarios->last()->cantidad_actual ?? 0);
                }), 2) }}
            </p>
        </div>
    </div>

    <!-- Grid de productos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($productos as $producto)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
           <div class="h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                @if($producto->imagen)
@if(\Illuminate\Support\Str::startsWith($producto->imagen, 'http'))
    <img src="{{ $producto->imagen }}" alt="{{ $producto->nombre }}" class="h-full w-full object-cover">
@else
    <img src="{{ asset($producto->imagen) }}" alt="{{ $producto->nombre }}" class="h-full w-full object-cover">
@endif
                @else
                <svg class="w-20 h-20 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"/>
                </svg>
                @endif
            </div>
            
            <div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <span class="text-xs font-semibold text-gray-500">{{ $producto->codigo }}</span>
                    @php
                        $stock = $producto->inventarios->first()->cantidad_actual ?? 0;
                        $stockBajo = $stock <= $producto->stock_minimo;
                    @endphp
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $stockBajo ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        Stock: {{ $stock }}
                    </span>
                </div>

                <h3 class="font-bold text-gray-800 mb-1 line-clamp-2">{{ $producto->nombre }}</h3>
                
                @if($producto->marca)
                <p class="text-sm text-gray-600 mb-2">{{ $producto->marca }} {{ $producto->modelo }}</p>
                @endif

                <div class="flex items-center gap-2 mb-3">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $producto->categoria->nombre }}
                    </span>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $producto->estado == 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($producto->estado) }}
                    </span>
                </div>

                <div class="border-t pt-3 mb-3">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Compra:</span>
                        <span class="font-semibold">Q{{ number_format($producto->precio_compra, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Venta:</span>
                        <span class="font-bold text-green-600">Q{{ number_format($producto->precio_venta, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                        <span>Margen:</span>
                        <span class="font-semibold">{{ number_format($producto->margen_ganancia, 1) }}%</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('productos.show', $producto) }}" 
                       class="flex-1 bg-blue-600 text-white text-center py-2 rounded hover:bg-blue-700 transition text-sm">
                        Ver
                    </a>
                    <a href="{{ route('productos.edit', $producto) }}" 
                       class="bg-yellow-500 text-white p-2 rounded hover:bg-yellow-600 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-gray-500">
            No se encontraron productos
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    <div class="bg-white rounded-lg shadow-md p-4">
        {{ $productos->links() }}
    </div>

</div>
@endsection