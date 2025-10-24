@extends('layouts.app')

@section('title', 'Inventario - Casa Maravillosa')
@section('page-title', 'Control de Inventario')
@section('page-subtitle', 'Gestiona el stock de tus productos')

@section('content')
<div class="space-y-6">

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

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Total Productos</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_productos'] }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Stock Bajo</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $stats['stock_bajo'] }}</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Sin Stock</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['sin_stock'] }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Valor Total</p>
                    <p class="text-3xl font-bold text-green-600">Q{{ number_format($stats['valor_total'], 2) }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('inventario.index') }}" class="flex gap-4">
            <select name="categoria" class="border border-gray-300 rounded-lg px-4 py-2">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $cat)
                <option value="{{ $cat->id }}" {{ request('categoria') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->nombre }}
                </option>
                @endforeach
            </select>

            <select name="alerta" class="border border-gray-300 rounded-lg px-4 py-2">
                <option value="">Todos los productos</option>
                <option value="bajo" {{ request('alerta') == 'bajo' ? 'selected' : '' }}>Solo stock bajo</option>
            </select>

            <button type="submit
               <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Filtrar
            </button>

            <a href="{{ route('inventario.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                Limpiar
            </a>
        </form>
    </div>

    <!-- Tabla de inventario -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Actual</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Mínimo</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Stock</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($productos as $producto)
                @php
                    $stock = $producto->stock_actual;
                    $stockBajo = $stock <= $producto->stock_minimo;
                    $sinStock = $stock == 0;
                @endphp
                <tr class="hover:bg-gray-50 {{ $stockBajo ? 'bg-orange-50' : '' }} {{ $sinStock ? 'bg-red-50' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $producto->nombre }}</div>
                            <div class="text-sm text-gray-500">{{ $producto->codigo }}</div>
                            @if($producto->marca)
                            <div class="text-xs text-gray-400">{{ $producto->marca }} {{ $producto->modelo }}</div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $producto->categoria->nombre }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="text-2xl font-bold {{ $sinStock ? 'text-red-600' : ($stockBajo ? 'text-orange-600' : 'text-green-600') }}">
                            {{ $stock }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="text-sm text-gray-600">{{ $producto->stock_minimo }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($sinStock)
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                                </svg>
                                Agotado
                            </span>
                        @elseif($stockBajo)
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800 flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                                </svg>
                                Bajo
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                Normal
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="text-sm font-semibold text-gray-900">Q{{ number_format($producto->precio_compra * $stock, 2) }}</div>
                        <div class="text-xs text-gray-500">Q{{ number_format($producto->precio_compra, 2) }} c/u</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('productos.show', $producto) }}" 
                               class="text-blue-600 hover:text-blue-900" title="Ver detalle">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </a>
                            <a href="{{ route('inventario.ajuste', $producto) }}" 
                               class="bg-purple-600 text-white px-3 py-1 rounded hover:bg-purple-700 text-sm font-medium">
                                Ajustar
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        No se encontraron productos
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection