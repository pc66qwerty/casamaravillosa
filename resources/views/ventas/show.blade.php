@extends('layouts.app')

@section('title', 'Detalle Venta - Casa Maravillosa')
@section('page-title', 'Detalle de la Venta')
@section('page-subtitle', 'Información completa de la venta')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Botones -->
    <div class="flex items-center justify-between">
        <a href="{{ route('ventas.index') }}" 
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"/>
            </svg>
            Volver al listado
        </a>

        <div class="flex gap-2">
            <button onclick="window.print()" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"/>
                </svg>
                Imprimir
            </button>
        </div>
    </div>

    <!-- Información de la venta -->
    <div class="bg-white rounded-lg shadow-md p-8">
        
        <!-- Encabezado -->
        <div class="flex justify-between items-start mb-8 pb-6 border-b-2">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ $venta->numero_venta }}</h2>
                <p class="text-gray-600">{{ $venta->created_at->format('d/m/Y H:i') }}</p>
                <span class="mt-2 inline-block px-4 py-2 text-sm font-semibold rounded-full 
                    {{ $venta->estado == 'completada' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $venta->estado == 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $venta->estado == 'cancelada' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ ucfirst($venta->estado) }}
                </span>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 mb-1">Vendedor</p>
                <p class="font-semibold">{{ $venta->usuario->name }}</p>
            </div>
        </div>

        <!-- Cliente -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Cliente</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Nombre</p>
                        <p class="font-semibold text-gray-800">{{ $venta->cliente->nombre_completo }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Teléfono</p>
                        <p class="font-semibold text-gray-800">{{ $venta->cliente->telefono }}</p>
                    </div>
                    @if($venta->cliente->email)
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-semibold text-gray-800">{{ $venta->cliente->email }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-500">Tipo de Vehículo</p>
                        <p class="font-semibold text-gray-800">{{ ucfirst($venta->cliente->tipo_vehiculo) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Productos</h3>
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Producto</th>
                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Cantidad</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">Precio Unit.</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($venta->detalles as $detalle)
                    <tr>
                        <td class="px-4 py-4">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $detalle->producto->nombre }}</p>
                                <p class="text-sm text-gray-500">{{ $detalle->producto->codigo }}</p>
                                <p class="text-xs text-gray-400">{{ $detalle->producto->categoria->nombre }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="font-semibold">{{ $detalle->cantidad }}</span>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <span class="text-gray-800">Q{{ number_format($detalle->precio_unitario, 2) }}</span>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <span class="font-bold text-gray-800">Q{{ number_format($detalle->subtotal, 2) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totales -->
        <div class="flex justify-end">
            <div class="w-full md:w-1/2 space-y-3">
                <div class="flex justify-between text-lg">
                    <span class="text-gray-600">Subtotal:</span>
                    <span class="font-semibold">Q{{ number_format($venta->subtotal, 2) }}</span>
                </div>
                @if($venta->descuento > 0)
                <div class="flex justify-between text-lg">
                    <span class="text-gray-600">Descuento:</span>
                    <span class="font-semibold text-red-600">-Q{{ number_format($venta->descuento, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between text-2xl border-t-2 pt-3">
                    <span class="font-bold text-gray-800">Total:</span>
                    <span class="font-bold text-green-600">Q{{ number_format($venta->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Método de pago -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                </svg>
                <span class="font-semibold text-blue-900">Método de pago:</span>
                <span class="text-blue-800">{{ ucfirst($venta->metodo_pago) }}</span>
            </div>
        </div>

        <!-- Notas -->
        @if($venta->notas)
        <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-sm font-semibold text-yellow-800 mb-1">Notas:</p>
            <p class="text-yellow-900">{{ $venta->notas }}</p>
        </div>
        @endif

    </div>

</div>
@endsection