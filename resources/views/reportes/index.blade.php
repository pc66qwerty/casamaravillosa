@extends('layouts.app')

@section('title', 'Reportes - Casa Maravillosa')
@section('page-title', 'Reportes y Análisis')
@section('page-subtitle', 'Visualiza el desempeño de tu negocio')

@section('content')
<div class="space-y-6">

    <!-- Filtro de fechas -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('reportes.index') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" value="{{ $fecha_inicio }}" 
                       class="border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Fin</label>
                <input type="date" name="fecha_fin" value="{{ $fecha_fin }}" 
                       class="border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Generar Reporte
            </button>
            <button type="button" onclick="window.print()" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700">
                Imprimir
            </button>
            <a href="{{ route('reportes.exportar', ['fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin]) }}" 
               class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/>
                </svg>
                Exportar Excel
            </a>
        </form>
    </div>

    <!-- Resumen general -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-md p-6 text-white">
            <p class="text-blue-100 text-sm mb-1">Total Ventas</p>
            <p class="text-4xl font-bold">{{ $ventas_resumen->total_ventas }}</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-md p-6 text-white">
            <p class="text-green-100 text-sm mb-1">Ingresos Totales</p>
            <p class="text-4xl font-bold">Q{{ number_format($ventas_resumen->ingresos_totales, 0) }}</p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-md p-6 text-white">
            <p class="text-purple-100 text-sm mb-1">Ticket Promedio</p>
            <p class="text-4xl font-bold">Q{{ number_format($ventas_resumen->ticket_promedio, 0) }}</p>
        </div>
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-md p-6 text-white">
            <p class="text-orange-100 text-sm mb-1">Descuentos</p>
            <p class="text-4xl font-bold">Q{{ number_format($ventas_resumen->descuentos_totales, 0) }}</p>
        </div>
    </div>

    <!-- Ventas por método de pago -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Ventas por Método de Pago</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @foreach($ventas_por_metodo as $metodo)
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-500 mb-1">{{ ucfirst($metodo->metodo_pago) }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ $metodo->cantidad }}</p>
                <p class="text-sm font-semibold text-green-600">Q{{ number_format($metodo->total, 2) }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Productos más vendidos -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Top 10 Productos Más Vendidos</h3>
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">#</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Producto</th>
                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Cantidad Vendida</th>
                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">Ingresos</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($productos_mas_vendidos as $index => $producto)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-bold flex items-center justify-center">
                            {{ $index + 1 }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-semibold text-gray-800">{{ $producto->nombre }}</p>
                        <p class="text-sm text-gray-500">{{ $producto->codigo }}</p>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-lg font-bold text-blue-600">{{ $producto->cantidad_vendida }}</span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <span class="font-bold text-green-600">Q{{ number_format($producto->ingresos, 2) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">No hay datos</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Clientes Top -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Top 10 Mejores Clientes</h3>
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">#</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Cliente</th>
                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-500">Compras</th>
                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-500">Total Gastado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($clientes_top as $index => $cliente)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <span class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 font-bold flex items-center justify-center">
                            {{ $index + 1 }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-semibold text-gray-800">{{ $cliente->nombre_completo }}</p>
                        <p class="text-sm text-gray-500">{{ $cliente->telefono }}</p>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-lg font-bold text-purple-600">{{ $cliente->ventas_count }}</span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <span class="font-bold text-green-600">Q{{ number_format($cliente->ventas_sum_total, 2) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">No hay datos</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Productos con stock bajo -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
            </svg>
            Alertas de Stock Bajo
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($productos_stock_bajo as $producto)
            <div class="border-2 border-orange-200 rounded-lg p-4 bg-orange-50">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">{{ $producto->nombre }}</p>
                        <p class="text-xs text-gray-500">{{ $producto->codigo }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-200 text-orange-800">
                        {{ $producto->categoria->nombre }}
                    </span>
                </div>
                <div class="flex justify-between items-center mt-3 pt-3 border-t border-orange-200">
                    <div>
                        <p class="text-xs text-gray-600">Stock Actual</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $producto->stock_actual }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Mínimo</p>
                        <p class="text-lg font-semibold text-gray-700">{{ $producto->stock_minimo }}</p>
                    </div>
                    <a href="{{ route('inventario.ajuste', $producto) }}" 
                       class="bg-orange-600 text-white px-3 py-1 rounded text-xs hover:bg-orange-700">
                        Reabastecer
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-8 text-gray-500">
                ¡Excelente! No hay productos con stock bajo
            </div>
            @endforelse
        </div>
    </div>

    <!-- Gráfica de ventas diarias -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Evolución de Ventas Diarias</h3>
        <div class="h-80">
            <canvas id="ventasDiariasChart"></canvas>
        </div>
    </div>

    <!-- Movimientos de inventario recientes -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Movimientos de Inventario Recientes</h3>
        <div class="space-y-3 max-h-96 overflow-y-auto">
            @forelse($movimientos_inventario as $movimiento)
            <div class="border border-gray-200 rounded-lg p-3 hover:border-blue-300 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                            {{ $movimiento->tipo_movimiento == 'entrada' ? 'bg-green-100 text-green-600' : '' }}
                            {{ $movimiento->tipo_movimiento == 'salida' ? 'bg-red-100 text-red-600' : '' }}
                            {{ $movimiento->tipo_movimiento == 'ajuste' ? 'bg-blue-100 text-blue-600' : '' }}">
                            @if($movimiento->tipo_movimiento == 'entrada')
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z"/>
                            </svg>
                            @elseif($movimiento->tipo_movimiento == 'salida')
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" transform="rotate(180 10 10)"/>
                            </svg>
                            @else
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $movimiento->producto->nombre }}</p>
                            <p class="text-xs text-gray-500">{{ $movimiento->motivo }}</p>
                            <p class="text-xs text-gray-400">{{ $movimiento->created_at->format('d/m/Y H:i') }} - {{ $movimiento->usuario->name ?? 'Sistema' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Stock resultante</p>
                        <p class="text-xl font-bold text-gray-800">{{ $movimiento->cantidad_actual }}</p>
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
                </div>
            </div>
            @empty
            <p class="text-center text-gray-500 py-8">No hay movimientos en este período</p>
            @endforelse
        </div>
    </div>

</div>

<!-- Script para la gráfica -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('ventasDiariasChart').getContext('2d');
    
    const fechas = {!! json_encode($ventas_diarias->pluck('fecha')->map(function($f) {
        return date('d/m', strtotime($f));
    })) !!};
    
    const cantidades = {!! json_encode($ventas_diarias->pluck('cantidad')) !!};
    const totales = {!! json_encode($ventas_diarias->pluck('total')) !!};

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: fechas,
            datasets: [
                {
                    label: 'Cantidad de Ventas',
                    data: cantidades,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    yAxisID: 'y',
                    tension: 0.4
                },
                {
                    label: 'Ingresos (Q)',
                    data: totales,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    yAxisID: 'y1',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Cantidad de Ventas'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Ingresos (Q)'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                }
            }
        }
    });
</script>
@endsection