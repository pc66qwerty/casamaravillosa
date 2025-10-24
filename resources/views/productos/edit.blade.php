@extends('layouts.app')

@section('title', 'Editar Producto - Casa Maravillosa')
@section('page-title', 'Editar Producto')
@section('page-subtitle', 'Modifica la información del producto')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="bg-white rounded-lg shadow-md p-8">
        
<form action="{{ route('productos.update', $producto) }}" method="POST" enctype="multipart/form-data">            @csrf
            @method('PUT')

            <!-- Información Básica -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Información Básica</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Código del Producto *</label>
                        <input type="text" name="codigo" value="{{ old('codigo', $producto->codigo) }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 @error('codigo') border-red-500 @enderror">
                        @error('codigo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoría *</label>
                        <select name="categoria_id" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 @error('categoria_id') border-red-500 @enderror">
                            <option value="">Seleccionar...</option>
                            @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }} ({{ ucfirst($categoria->tipo) }})
                            </option>
                            @endforeach
                        </select>
                        @error('categoria_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Producto *</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 @error('nombre') border-red-500 @enderror">
                        @error('nombre')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                        <textarea name="descripcion" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">{{ old('descripcion', $producto->descripcion) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Marca</label>
                        <input type="text" name="marca" value="{{ old('marca', $producto->marca) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Modelo</label>
                        <input type="text" name="modelo" value="{{ old('modelo', $producto->modelo) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-2" x-data="{ tipoImagen: 'file' }">
    <label class="block text-sm font-medium text-gray-700 mb-2">Imagen del Producto</label>

    @if($producto->imagen)
    <div class="mb-3">
        <p class="text-xs text-gray-500 mb-1">Imagen actual:</p>
        @if(\Illuminate\Support\Str::startsWith($producto->imagen, 'http'))
            <img src="{{ $producto->imagen }}" alt="{{ $producto->nombre }}" class="h-32 w-32 object-cover rounded-lg border">
        @else
            <img src="{{ asset($producto->imagen) }}" alt="{{ $producto->nombre }}" class="h-32 w-32 object-cover rounded-lg border">
        @endif
    </div>
    @endif
    
    <div class="flex items-center gap-4 mb-3">
        <label class="flex items-center">
            <input type="radio" name="tipo_imagen" value="file" x-model="tipoImagen" class="focus:ring-blue-500">
            <span class="ml-2 text-sm text-gray-600">Subir Archivo Nuevo</span>
        </label>
        <label class="flex items-center">
            <input type="radio" name="tipo_imagen" value="url" x-model="tipoImagen" class="focus:ring-blue-500">
            <span class="ml-2 text-sm text-gray-600">Usar URL</span>
        </label>
    </div>

    <div x-show="tipoImagen === 'file'">
        <input type="file" 
               name="imagen_file" 
               accept="image/*"
               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
        <p class="text-xs text-gray-500 mt-1">Deja vacío para mantener la imagen actual.</p>
    </div>

    <div x-show="tipoImagen === 'url'" style="display: none;">
        <input type="url" 
               name="imagen_url"
               placeholder="https://ejemplo.com/imagen.jpg"
               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
        <p class="text-xs text-gray-500 mt-1">Pega el enlace directo a la imagen.</p>
    </div>
</div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                        <select name="estado" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="activo" {{ old('estado', $producto->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ old('estado', $producto->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                </div>
            </div>

            <!-- Precios e Inventario -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Precios e Inventario</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Precio de Compra *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500">Q</span>
                            <input type="number" name="precio_compra" value="{{ old('precio_compra', $producto->precio_compra) }}" required
                                   step="0.01" min="0"
                                   class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 focus:ring-2 focus:ring-blue-500 @error('precio_compra') border-red-500 @enderror">
                        </div>
                        @error('precio_compra')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Precio de Venta *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500">Q</span>
                            <input type="number" name="precio_venta" value="{{ old('precio_venta', $producto->precio_venta) }}" required
                                   step="0.01" min="0"
                                   class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 focus:ring-2 focus:ring-blue-500 @error('precio_venta') border-red-500 @enderror">
                        </div>
                        @error('precio_venta')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stock Mínimo *</label>
                        <input type="number" name="stock_minimo" value="{{ old('stock_minimo', $producto->stock_minimo) }}" required
                               min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 @error('stock_minimo') border-red-500 @enderror">
                        @error('stock_minimo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ubicación en Bodega</label>
                        <input type="text" name="ubicacion" value="{{ old('ubicacion', $producto->ubicacion) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>

                </div>
            </div>

            <!-- Información actual de stock -->
            <div class="mb-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-semibold text-blue-900 mb-2">Información de Inventario Actual</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="text-blue-700">Stock Actual:</span>
                        <span class="font-bold text-blue-900 ml-2">{{ $producto->stock_actual }}</span>
                    </div>
                    <div>
                        <span class="text-blue-700">Margen:</span>
                        <span class="font-bold text-blue-900 ml-2">{{ number_format($producto->margen_ganancia, 1) }}%</span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-blue-700">Para ajustar el stock, usa el módulo de</span>
                        <a href="{{ route('inventario.index') }}" class="font-bold text-blue-600 hover:underline ml-1">Inventario</a>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-between">
                <a href="{{ route('productos.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Volver
                </a>
                <div class="flex gap-4">
                    <a href="{{ route('productos.show', $producto) }}" 
                       class="px-6 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition">
                        Ver Detalle
                    </a>
                    <button type="submit" 
                            class="px-6 py-2
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Actualizar Producto
                    </button>
                </div>
            </div>

        </form>

    </div>

</div>
@endsection