@extends('layouts.app')

@section('title', 'Ventas - Casa Maravillosa')
@section('page-title', 'Registro de Ventas')
@section('page-subtitle', 'Historial de todas las ventas realizadas')

@section('content')
<div class="space-y-6">

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
        {{ session('success') }}
    </div>
    @endif

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-md p-6 text-white">
            <p class="text-blue-100 text-sm mb-1">Total Ventas</p>
            <p class="text-3xl font-bold">{{ $stats['total_ventas'] }}</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-md p-6 text-white">
            <p class="text-green-100 text-sm mb-1">Ventas Hoy</p>
            <p class="text-3xl font-bold">{{ $stats['ventas_hoy'] }}</p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-md p-6 text-white">
            <p class="text-purple-100 text-sm mb-1">Total Este Mes</p>
            <p class="text-3xl font-bold">Q{{ number_format($stats['total_mes'], 2) }}</p>
        </div>
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-md p-6 text-white">
            <p class="text-orange-100 text-sm mb-1">Pendientes</p>
            <p class="text-3xl font-bold">{{ $stats['pendientes'] }}</p>
        </div>
    </div>

    <!-- Botón nueva venta -->
    <div class="flex justify-end">
        <a href="{{ route('ventas.create') }}" 
           class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition inline-flex items-center gap-2 text-lg font-semibold">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
            </svg>
            Nueva Venta
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('ventas.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <input type="text" name="buscar" value="{{ request('buscar') }}" 
                   placeholder="Buscar por número o cliente..."
                   class="border border-gray-300 rounded-lg px-4 py-2">
            
            <select name="estado" class="border border-gray-300 rounded-lg px-4 py-2">
                <option value="">Todos los estados</option>
                <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>

            <select name="metodo_pago" class="border border-gray-300 rounded-lg px-4 py-2">
                <option value="">Todos los métodos</option>
                <option value="efectivo" {{ request('metodo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                <option value="tarjeta" {{ request('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                <option value="transferencia" {{ request('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                <option value="credito" {{ request('metodo_pago') == 'credito' ? 'selected' : '' }}>Crédito</option>
            </select>

            <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" 
                   class="border border-gray-300 rounded-lg px-4 py-2">

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Filtrar
            </button>
        </form>
    </div>

    <!-- Tabla de ventas -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Número</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Método Pago</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($ventas as $venta)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-semibold text-blue-600">{{ $venta->numero_venta }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $venta->cliente->nombre_completo }}</div>
                            <div class="text-sm text-gray-500">{{ $venta->cliente->telefono }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $venta->created_at->format('d/m/Y') }}</div>
                        <div class="text-sm text-gray-500">{{ $venta->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $venta->metodo_pago == 'efectivo' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $venta->metodo_pago == 'tarjeta' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $venta->metodo_pago == 'transferencia' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $venta->metodo_pago == 'credito' ? 'bg-orange-100 text-orange-800' : '' }}">
                            {{ ucfirst($venta->metodo_pago) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="text-lg font-bold text-green-600">Q{{ number_format($venta->total, 2) }}</div>
                        @if($venta->descuento > 0)
                        <div class="text-xs text-gray-500">Desc: Q{{ number_format($venta->descuento, 2) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            {{ $venta->estado == 'completada' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $venta->estado == 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $venta->estado == 'cancelada' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($venta->estado) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <a href="{{ route('ventas.show', $venta) }}" 
                           class="inline-flex items-center gap-1 bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            Ver
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        No se encontraron ventas
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-6 py-4 bg-gray-50">
            {{ $ventas->links() }}
        </div>
    </div>

</div>
@endsection