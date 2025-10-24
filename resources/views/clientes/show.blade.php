@extends('layouts.app')

@section('title', 'Detalle Cliente - Casa Maravillosa')
@section('page-title', 'Detalle del Cliente')
@section('page-subtitle', 'Información completa del cliente')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Acciones rápidas -->
    <div class="flex items-center justify-between">
        <a href="{{ route('clientes.index') }}" 
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"/>
            </svg>
            Volver a la lista
        </a>

        <div class="flex gap-2">
            <a href="{{ route('clientes.edit', $cliente) }}" 
               class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                </svg>
                Editar
            </a>
        </div>
    </div>

    <!-- Información del Cliente -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Columna Izquierda - Info Principal -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Card de perfil -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-center">
                    <div class="mx-auto h-24 w-24 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white text-3xl font-bold mb-4">
                        {{ substr($cliente->nombre, 0, 1) }}{{ substr($cliente->apellido, 0, 1) }}
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $cliente->nombre_completo }}</h2>
                    <p class="text-gray-500 mt-1">Cliente desde {{ $cliente->created_at->format('d/m/Y') }}</p>
                    
                    <div class="mt-4">
                        <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full 
                            {{ $cliente->estado == 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($cliente->estado) }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 space-y-3 border-t pt-4">
                    <div class="flex items-center gap-3 text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                        <span>{{ $cliente->telefono }}</span>
                    </div>

                    @if($cliente->email)
                    <div class="flex items-center gap-3 text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        <span class="text-sm">{{ $cliente->email }}</span>
                    </div>
                    @endif

                    @if($cliente->direccion)
                    <div class="flex items-start gap-3 text-gray-600">
                        <svg class="w-5 h-5 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                        <span class="text-sm">{{ $cliente->direccion }}</span>
                    </div>
                    @endif

                    @if($cliente->dpi)
                    <div class="flex items-center gap-3 text-gray-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z"/>
                        </svg>
                        <span class="text-sm">DPI: {{ $cliente->dpi }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Card de vehículo -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                        <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                    </svg>
                    Vehículo
                </h3>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-500">Tipo:</span>
                        <span class="ml-2 px-3 py-1 inline-flex text-sm font-semibold rounded-full 
                            {{ $cliente->tipo_vehiculo == 'moto' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $cliente->tipo_vehiculo == 'carro' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $cliente->tipo_vehiculo == 'bicicleta' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ ucfirst($cliente->tipo_vehiculo) }}
                        </span>
                    </div>

                    @if($cliente->marca_vehiculo)
                    <div>
                        <span class="text-sm text-gray-500">Marca:</span>
                        <span class="ml-2 text-gray-800 font-medium">{{ $cliente->marca_vehiculo }}</span>
                    </div>
                    @endif

                    @if($cliente->modelo_vehiculo)
                    <div>
                        <span class="text-sm text-gray-500">Modelo:</span>
                        <span class="ml-2 text-gray-800 font-medium">{{ $cliente->modelo_vehiculo }}</span>
                    </div>
                    @endif

                    @if($cliente->placa_vehiculo)
                    <div>
                        <span class="text-sm text-gray-500">Placa:</span>
                        <span class="ml-2 text-gray-800 font-medium">{{ $cliente->placa_vehiculo }}</span>
                    </div>
                    @endif
                </div>
            </div>

            @if($cliente->notas)
            <!-- Notas -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h3 class="text-sm font-bold text-yellow-800 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                    </svg>
                    Notas
                </h3>
                <p class="text-sm text-yellow-900">{{ $cliente->notas }}</p>
            </div>
            @endif

        </div>

        <!-- Columna Derecha - Historial -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-md p-6 text-white">
                    <p class="text-blue-100 text-sm mb-1">Total Compras</p>
                    <p class="text-3xl font-bold">{{ $cliente->ventas->count() }}</p>
                </div>
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-md p-6 text-white">
                    <p class="text-green-100 text-sm mb-1">Monto Total</p>
                    <p class="text-3xl font-bold">Q{{ number_format($cliente->ventas->sum('total'), 2) }}</p>
                </div>
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-md p-6 text-white">
                    <p class="text-purple-100 text-sm mb-1">Interacciones</p>
                    <p class="text-3xl font-bold">{{ $cliente->interacciones->count() }}</p>
                </div>
            </div>

            <!-- Historial de Ventas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Historial de Ventas</h3>
                
                @if($cliente->ventas->count() > 0)
                <div class="space-y-3">
                    @foreach($cliente->ventas->take(5) as $venta)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $venta->numero_venta }}</p>
                                <p class="text-sm text-gray-500">{{ $venta->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-green-600">Q{{ number_format($venta->total, 2) }}</p>
                                <span class="text-xs px-2 py-1 rounded-full 
                                    {{ $venta->estado == 'completada' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $venta->estado == 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $venta->estado == 'cancelada' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($venta->estado) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-center text-gray-500 py-8">No hay ventas registradas</p>
                @endif
            </div>

            <!-- Historial de Interacciones -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Historial de Interacciones</h3>
                
                @if($cliente->interacciones->count() > 0)
                <div class="space-y-4">
                    @foreach($cliente->interacciones->take(5) as $interaccion)
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full 
                                {{ $interaccion->tipo == 'llamada' ? 'bg-blue-100 text-blue-600' : '' }}
                                {{ $interaccion->tipo == 'whatsapp' ? 'bg-green-100 text-green-600' : '' }}
                                {{ $interaccion->tipo == 'email' ? 'bg-purple-100 text-purple-600' : '' }}
                                {{ $interaccion->tipo == 'visita' ? 'bg-yellow-100 text-yellow-600' : '' }}
                                flex items-center justify-center">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-semibold text-gray-800 capitalize">{{ $interaccion->tipo }}</span>
                                <span class="text-xs text-gray-500">{{ $interaccion->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $interaccion->descripcion }}</p>
                            @if($interaccion->fecha_seguimiento)
                            <p class="text-xs text-orange-600 mt-1">Seguimiento: {{ $interaccion->fecha_seguimiento->format('d/m/Y') }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-center text-gray-500 py-8">No hay interacciones registradas</p>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection