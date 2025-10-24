@extends('layouts.app')

@section('title', 'Ajustar Inventario - Casa Maravillosa')
@section('page-title', 'Ajustar Inventario')
@section('page-subtitle', 'Registra movimientos de entrada o salida')

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- Botón volver -->
    <div class="mb-6">
        <a href="{{ route('inventario.index') }}" 
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"/>
            </svg>
            Volver al inventario
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Columna izquierda - Información del producto -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-24 h-24 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"/>
                    </svg>
                </div>

                <h3 class="font-bold text-xl text-gray-800 mb-2">{{ $producto->nombre }}</h3>
                <p class="text-sm text-gray-600 mb-4">{{ $producto->codigo }}</p>

                <div class="space-y-3 border-t pt-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Categoría:</span>
                        <span class="text-sm font-semibold">{{ $producto->categoria->nombre }}</span>
                    </div>

                    @if($producto->marca)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Marca:</span>
                        <span class="text-sm font-semibold">{{ $producto->marca }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Stock Actual:</span>
                        <span class="text-2xl font-bold text-blue-600">{{ $producto->stock_actual }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Stock Mínimo:</span>
                        <span class="text-sm font-semibold text-orange-600">{{ $producto->stock_minimo }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Precio:</span>
                        <span class="text-sm font-semibold">Q{{ number_format($producto->precio_venta, 2) }}</span>
                    </div>

                    @if($producto->ubicacion)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Ubicación:</span>
                        <span class="text-sm font-semibold">{{ $producto->ubicacion }}</span>
                    </div>
                    @endif
                </div>

                @if($producto->stock_actual <= $producto->stock_minimo)
                <div class="mt-4 bg-orange-50 border border-orange-200 rounded-lg p-3">
                    <div class="flex items-center gap-2 text-orange-800">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                        </svg>
                        <span class="text-sm font-semibold">Stock bajo</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Columna derecha - Formulario de ajuste -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-8">
                
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Registrar Movimiento</h2>

                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                    {{ session('error') }}
                </div>
                @endif

                <form action="{{ route('inventario.actualizar', $producto) }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        
                        <!-- Tipo de movimiento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Tipo de Movimiento *</label>
                            <div class="grid grid-cols-3 gap-4">
                                
                                <label class="relative flex flex-col items-center p-4 border-2 rounded-lg cursor-pointer hover:border-green-500 transition">
                                    <input type="radio" name="tipo_movimiento" value="entrada" required class="sr-only peer">
                                    <div class="peer-checked:bg-green-600 bg-green-100 text-green-600 peer-checked:text-white p-3 rounded-full mb-2">
                                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z"/>
                                        </svg>
                                    </div>
                                    <span class="font-semibold text-gray-800 peer-checked:text-green-600">Entrada</span>
                                    <span class="text-xs text-gray-500 text-center mt-1">Agregar stock</span>
                                    <div class="absolute inset-0 border-2 border-green-600 rounded-lg opacity-0 peer-checked:opacity-100"></div>
                                </label>

                                <label class="relative flex flex-col items-center p-4 border-2 rounded-lg cursor-pointer hover:border-red-500 transition">
                                    <input type="radio" name="tipo_movimiento" value="salida" required class="sr-only peer">
                                    <div class="peer-checked:bg-red-600 bg-red-100 text-red-600 peer-checked:text-white p-3 rounded-full mb-2">
                                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" transform="rotate(180 10 10)"/>
                                        </svg>
                                    </div>
                                    <span class="font-semibold text-gray-800 peer-checked:text-red-600">Salida</span>
                                    <span class="text-xs text-gray-500 text-center mt-1">Reducir stock</span>
                                    <div class="absolute inset-0 border-2 border-red-600 rounded-lg opacity-0 peer-checked:opacity-100"></div>
                                </label>

                                <label class="relative flex flex-col items-center p-4 border-2 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                    <input type="radio" name="tipo_movimiento" value="ajuste" required class="sr-only peer">
                                    <div class="peer-checked:bg-blue-600 bg-blue-100 text-blue-600 peer-checked:text-white p-3 rounded-full mb-2">
                                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                    </div>
                                    <span class="font-semibold text-gray-800 peer-checked:text-blue-600">Ajuste</span>
                                    <span class="text-xs text-gray-500 text-center mt-1">Establecer cantidad</span>
                                    <div class="absolute inset-0 border-2 border-blue-600 rounded-lg opacity-0 peer-checked:opacity-100"></div>
                                </label>

                            </div>
                            @error('tipo_movimiento')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cantidad -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cantidad *</label>
                            <input type="number" name="cantidad" value="{{ old('cantidad') }}" required min="1"
                                   placeholder="Ingresa la cantidad"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 text-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cantidad') border-red-500 @enderror">
                            @error('cantidad')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">
                                Stock actual: <span class="font-semibold">{{ $producto->stock_actual }}</span> unidades
                            </p>
                        </div>

                        <!-- Motivo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Motivo del Movimiento *</label>
                            <textarea name="motivo" required rows="3"
                                      placeholder="Describe el motivo del ajuste de inventario..."
                                      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('motivo') border-red-500 @enderror">{{ old('motivo') }}</textarea>
                            @error('motivo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ejemplos de motivos -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Ejemplos de motivos:</p>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• <strong>Entrada:</strong> Compra a proveedor, devolución de cliente, traslado desde otra bodega</li>
                                <li>• <strong>Salida:</strong> Venta, producto dañado, traslado a otra bodega, muestra</li>
                                <li>• <strong>Ajuste:</strong> Corrección de inventario físico, diferencia en conteo</li>
                            </ul>
                        </div>

                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t">
                        <a href="{{ route('inventario.index') }}" 
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            Guardar Movimiento
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

</div>
@endsection