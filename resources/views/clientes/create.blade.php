@extends('layouts.app')

@section('title', 'Nuevo Cliente - Casa Maravillosa')
@section('page-title', 'Nuevo Cliente')
@section('page-subtitle', 'Registra un nuevo cliente en el sistema')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="bg-white rounded-lg shadow-md p-8">
        
        <form action="{{ route('clientes.store') }}" method="POST">
            @csrf

            <!-- Información Personal -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Información Personal</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nombre') border-red-500 @enderror">
                        @error('nombre')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Apellido *</label>
                        <input type="text" name="apellido" value="{{ old('apellido') }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('apellido') border-red-500 @enderror">
                        @error('apellido')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono *</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telefono') border-red-500 @enderror">
                        @error('telefono')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">DPI</label>
                        <input type="text" name="dpi" value="{{ old('dpi') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('dpi') border-red-500 @enderror">
                        @error('dpi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                        <textarea name="direccion" rows="2"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('direccion') border-red-500 @enderror">{{ old('direccion') }}</textarea>
                        @error('direccion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- Información del Vehículo -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Información del Vehículo</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Vehículo *</label>
                        <select name="tipo_vehiculo" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tipo_vehiculo') border-red-500 @enderror">
                            <option value="">Seleccionar...</option>
                            <option value="moto" {{ old('tipo_vehiculo') == 'moto' ? 'selected' : '' }}>Moto</option>
                            <option value="carro" {{ old('tipo_vehiculo') == 'carro' ? 'selected' : '' }}>Carro</option>
                            <option value="bicicleta" {{ old('tipo_vehiculo') == 'bicicleta' ? 'selected' : '' }}>Bicicleta</option>
                        </select>
                        @error('tipo_vehiculo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Marca</label>
                        <input type="text" name="marca_vehiculo" value="{{ old('marca_vehiculo') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Modelo</label>
                        <input type="text" name="modelo_vehiculo" value="{{ old('modelo_vehiculo') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Placa</label>
                        <input type="text" name="placa_vehiculo" value="{{ old('placa_vehiculo') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                </div>
            </div>

            <!-- Notas -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notas Adicionales</label>
                <textarea name="notas" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notas') }}</textarea>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('clientes.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Guardar Cliente
                </button>
            </div>

        </form>

    </div>

</div>
@endsection