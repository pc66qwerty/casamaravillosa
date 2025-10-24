@extends('layouts.app')

@section('title', 'Dashboard - Casa Maravillosa')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Bienvenido al panel de control')

@section('content')
<div class="space-y-6">
    
    <!-- Tarjetas de Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total Clientes -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-purple-200 text-sm font-medium mb-1">Total Clientes</p>
                    <h3 class="text-4xl font-bold">{{ $total_clientes }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"/>
                    </svg>
                    +{{ $incremento_clientes }}%
                </span>
                <span class="text-purple-200">este mes</span>
            </div>
        </div>
        
        <!-- Interacciones -->
        <div class="bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-pink-200 text-sm font-medium mb-1">Interacciones</p>
                    <h3 class="text-4xl font-bold">{{ $total_interacciones }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"/>
                    </svg>
                    +{{ $incremento_interacciones }}
                </span>
                <span class="text-pink-200">desde ayer</span>
            </div>
        </div>
        
        <!-- Recordatorios -->
        <div class="bg-gradient-to-br from-cyan-400 to-blue-500 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-cyan-100 text-sm font-medium mb-1">Recordatorios</p>
                    <h3 class="text-4xl font-bold">{{ $recordatorios_urgentes }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <span class="flex items-center gap-1 bg-red-500 bg-opacity-50 px-2 py-1 rounded">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                    </svg>
                    {{ $recordatorios_urgentes }} urgentes
                </span>
                <span class="text-cyan-100">pendientes</span>
            </div>
        </div>
        
        <!-- Ventas del Mes -->
        <div class="bg-gradient-to-br from-orange-400 to-amber-500 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-orange-100 text-sm font-medium mb-1">Ventas del Mes</p>
                    <h3 class="text-4xl font-bold">Q{{ number_format($ventas_mes, 0) }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"/>
                    </svg>
                    +{{ $incremento_ventas }}%
                </span>
                <span class="text-orange-100">vs anterior</span>
            </div>
        </div>
        
    </div>
    
    <!-- Gráficas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Ventas Mensuales -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800">Ventas Mensuales</h3>
                <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option>2025</option>
                    <option>2024</option>
                </select>
            </div>
            
            <div class="h-64">
                <canvas id="ventasChart"></canvas>
            </div>
        </div>
        
        <!-- Distribución de Clientes -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800">Distribución de Clientes</h3>
                <a href="#" class="text-primary text-sm hover:underline">Ver detalles</a>
            </div>
            
            <div class="flex items-center justify-center h-64">
                <canvas id="clientesChart"></canvas>
            </div>
            
            <div class="mt-6 space-y-3">
                @foreach($distribucion_clientes as $item)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full {{ $loop->index == 0 ? 'bg-blue-500' : ($loop->index == 1 ? 'bg-purple-500' : 'bg-pink-500') }}"></div>
                        <span class="text-sm text-gray-600">{{ $item['categoria'] }}</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-800">{{ $item['porcentaje'] }}%</span>
                </div>
                @endforeach
            </div>
        </div>
        
    </div>
    
</div>

<!-- Script para las gráficas -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfica de Ventas Mensuales
    const ventasCtx = document.getElementById('ventasChart').getContext('2d');
    new Chart(ventasCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($ventas_mensuales, 'mes')) !!},
            datasets: [{
                label: 'Ventas (Q)',
                data: {!! json_encode(array_column($ventas_mensuales, 'monto')) !!},
                borderColor: '#5b6cf2',
                backgroundColor: 'rgba(91, 108, 242, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#5b6cf2',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Q' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
    
    // Gráfica de Distribución de Clientes
    const clientesCtx = document.getElementById('clientesChart').getContext('2d');
    new Chart(clientesCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_column($distribucion_clientes, 'categoria')) !!},
            datasets: [{
                data: {!! json_encode(array_column($distribucion_clientes, 'porcentaje')) !!},
                backgroundColor: [
                    '#3b82f6',
                    '#8b5cf6',
                    '#ec4899'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%'
        }
    });
</script>
@endsection