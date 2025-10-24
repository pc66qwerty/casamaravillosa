@extends('layouts.app')

@section('title', 'Detalle Producto - Casa Maravillosa')
@section('page-title', 'Detalle del Producto')
@section('page-subtitle', 'Información completa del producto')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Botones de navegación -->
    <div class="flex items-center justify-between">
        <a href="{{ route('productos.index') }}" 
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"/>
            </svg>
            Volver a la lista
        </a>

        <div class="flex gap-2">
            <a href="{{ route('inventario.ajuste', $producto) }}" 
               class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                </svg>
                Ajustar Stock
            </a>
            <a href="{{ route('productos.edit', $producto) }}" 
               class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                </svg>
                Editar
            </a>
        </div>
    </div>

    <!-- Grid principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Columna izquierda - Información del producto -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Card de imagen -->
          <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mb-4">
    @if($producto->imagen)
        @if(\Illuminate\Support\Str::startsWith($producto->imagen, 'http'))
            <img src="{{ $producto->imagen }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover rounded-lg">
        @else
            <img src="{{ asset($producto->imagen) }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover rounded-lg">
        @endif
    @else
        <svg class="w-20 h-20 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"/>
        </svg>
    @endif
</div>

                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-500">Código:</span>
                        <span class="ml-2 font-bold text-gray-800">{{ $producto->codigo }}</span>
                    </div>
                    
                    <div>
                        <span class="text-sm text-gray-500">Categoría:</span>
                        <span class="ml-2 px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $producto->categoria->nombre }}
                        </span>
                    </div>

                    <div>
                        <span class="text-sm text-gray-500">Tipo:</span>
                        <span class="ml-2 px-3 py-1 inline-flex text-sm font-semibold rounded-full 
                            {{ $producto->categoria->tipo == 'moto' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $producto->categoria->tipo == 'carro' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $producto->categoria->tipo == 'bicicleta' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ ucfirst($producto->categoria->tipo) }}
                        </span>
                    </div>

                    <div>
                        <span class="text-sm text-gray-500">Estado:</span>
                        <span class="ml-2 px-3 py-1 inline-flex text-sm font-semibold rounded-full 
                            {{ $producto->estado == 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($producto->estado) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card de precios -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Información de Precios</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-gray-600">Precio de Compra</span>
                        <span class="text-xl font-bold text-gray-800">Q{{ number_format($producto->precio_compra, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-gray-600">Precio de Venta</span>
                        <span class="text-2xl font-bold text-green-600">Q{{ number_format($producto->precio_venta, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Margen de Ganancia</span>
                        <span class="text-xl font-bold text-blue-600">{{ number_format($producto->margen_ganancia, 1) }}%</span>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-blue-700">Ganancia por unidad:</span>
                            <span class="font-bold text-blue-900">Q{{ number_format($producto->precio_venta - $producto->precio_compra, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de ubicación -->
            @if($producto->ubicacion)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h4 class="font-semibold text-yellow-800 mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                    Ubicación en Bodega
                </h4>
                <p class="text-yellow-900">{{ $producto->ubicacion }}</p>
            </div>
            @endif

        </div>

        <!-- Columna derecha - Detalles e historial -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Información general -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $producto->nombre }}</h2>
                
                @if($producto->marca || $producto->modelo)
                <p class="text-lg text-gray-600 mb-4">
                    {{ $producto->marca }} {{ $producto->modelo }}
                </p>
                @endif

                @if($producto->descripcion)
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Descripción</h3>
                    <p class="text-gray-600">{{ $producto->descripcion }}</p>
                </div>
                @endif

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Stock Actual</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $producto->stock_actual }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Stock Mínimo</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $producto->stock_minimo }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Valor en Stock</p>
                        <p class="text-2xl font-bold text-green-600">Q{{ number_format($producto->precio_compra * $producto->stock_actual, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Estado de Stock</p>
                        @if($producto->stock_actual <= 0)
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">Agotado</span>
                        @elseif($producto->stock_actual <= $producto->stock_minimo)
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-orange-100 text-orange-800">Bajo</span>
                        @else
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Normal</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Historial de movimientos de inventario -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Movimientos de Inventario (Últimos 10)</h3>
                
                @if($producto->inventarios->count() > 0)
                <div class="space-y-3">
                    @foreach($producto->inventarios as $movimiento)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-3">
                                <div class="mt-1">
                                    @if($movimiento->tipo_movimiento == 'entrada')
                                    <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z"/>
                                        </svg>
                                    </div>
                                    @elseif($movimiento->tipo_movimiento == 'salida')
                                    <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" transform="rotate(180 10 10)"/>
                                        </svg>
                                    </div>
                                    @else
                                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                    </div>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-semibold text-gray-800 capitalize">{{ $movimiento->tipo_movimiento }}</span>
                                        <span class="text-xs px-2 py-1 rounded-full 
                                            {{ $movimiento->tipo_movimiento == 'entrada' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $movimiento->tipo_movimiento == 'salida' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $movimiento->tipo_movimiento == 'ajuste' ? 'bg-blue-100 text-blue-800' : '' }}">
                                            @if($movimiento->tipo_movimiento == 'entrada')
                                                +{{ $movimiento->cantidad_entrada }}
                                            @elseif($movimiento->tipo_movimiento == 'salida')
                                                -{{ $movimiento->cantidad_salida }}
                                            @else
                                                Ajuste
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 mb-1">{{ $movimiento->motivo }}</p>
                                    
                                    <div class="flex items-center gap-4 text-xs text-gray-500">
                                        <span>{{ $movimiento->created_at->format('d/m/Y H:i') }}</span>
                                        @if($movimiento->usuario)
                                        <span>Por: {{ $movimiento->usuario->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="text-sm text-gray-500">Stock resultante</p>
                                <p class="text-xl font-bold text-gray-800">{{ $movimiento->cantidad_actual }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-center text-gray-500 py-8">No hay movimientos registrados</p>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection