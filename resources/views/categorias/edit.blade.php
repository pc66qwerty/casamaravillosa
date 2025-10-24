@extends('layouts.app')

@section('title', 'Editar Categoría - Casa Maravillosa')
@section('page-title', 'Editar Categoría')
@section('page-subtitle', 'Modifica la información de la categoría')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <div class="bg-white rounded-lg shadow-md p-8">
        
        <form action="{{ route('categorias.update', $categoria) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Categoría *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $categoria->nombre) }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Vehículo *</label>
                    <select name="tipo" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tipo') border-red-500 @enderror">
                        <option value="">Seleccionar...</option>
                        <option value="moto" {{ old('tipo', $categoria->tipo) == 'moto' ? 'selected' : '' }}>Motos</option>
                        <option value="carro" {{ old('tipo', $categoria->tipo) == 'carro' ? 'selected' : '' }}>Carros</option>
                        <option value="bicicleta" {{ old('tipo', $categoria->tipo) == 'bicicleta' ? 'selected' : '' }}>Bicicletas</option>
                    </select>
                    @error('tipo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                    <textarea name="descripcion" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                    <select name="estado" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="activo" {{ old('estado', $categoria->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('estado', $categoria->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

            </div>

            <div class="flex items-center justify-end gap-4 mt-8">
                <a href="{{ route('categorias.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Actualizar Categoría
                </button>
            </div>

        </form>

    </div>

</div>
@endsection